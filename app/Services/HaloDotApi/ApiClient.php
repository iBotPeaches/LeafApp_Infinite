<?php

declare(strict_types=1);

namespace App\Services\HaloDotApi;

use App\Enums\Mode as SystemMode;
use App\Models\Category;
use App\Models\Csr;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Map;
use App\Models\Medal;
use App\Models\Player;
use App\Models\Playlist;
use App\Models\ServiceRecord;
use App\Models\Team;
use App\Services\HaloDotApi\Enums\Language;
use App\Services\HaloDotApi\Enums\Mode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ApiClient implements InfiniteInterface
{
    public function __construct(
        private readonly array $config
    ) {
        //
    }

    public function appearance(string $gamertag): ?Player
    {
        $response = $this->getPendingRequest()->get('appearance/players/spartan-id', [
            'gamertag' => $gamertag,
        ]);

        if ($response->successful()) {
            return Player::fromHaloDotApi($response->json());
        }

        return null;
    }

    public function competitive(Player $player, ?int $season = null): ?Csr
    {
        // Handle when -1 (no season) is sent here.
        $season = $season === -1 ? null : $season;

        $season ??= (int) config('services.autocode.competitive.season');
        $queryParams = [
            'gamertag' => $player->gamertag,
            'season' => $season,
        ];

        // Handle upstream API breaking when we send latest season (ie 2), but with no version.
        // Returns are returned with S2V3, but invalid when S2 only is asked for.
        if ($season === (int) config('services.autocode.competitive.season')) {
            $queryParams['version'] = (int) config('services.autocode.competitive.version');
        }

        $response = $this->getPendingRequest()->get('stats/players/csrs', $queryParams);

        if ($response->throw()->successful()) {
            $data = $response->json();
            $data['player'] = $player;
            Csr::fromHaloDotApi($data);
        }

        return $player->csrs->first();
    }

    public function matches(Player $player, Mode $mode, bool $forceUpdate = false): Collection
    {
        $perPage = 25;
        $count = $perPage;
        $offset = 0;
        $firstPulledGameId = null;
        $lastGameIdVariable = $mode->getLastGameIdVariable();

        while ($count !== 0) {
            $response = $this->getPendingRequest()->post('stats/players/matches', [
                'gamertag' => $player->gamertag,
                'type' => (string) $mode->value,
                'language' => Language::US,
                'count' => $perPage,
                'offset' => $offset,
            ]);

            if ($response->throw()->successful()) {
                $data = $response->json();
                $count = (int) Arr::get($data, 'additional.count');
                $offset += $perPage;

                foreach (Arr::get($data, 'data') as $gameData) {
                    // HaloDotAPI - We can longer trust "type" as its not returning the matching value from the filtered search.
                    // This field may be deprecated in future. So force set it based on filter param.
                    // https://github.com/iBotPeaches/LeafApp_Infinite/issues/560
                    Arr::set($gameData, 'type', (string) $mode->value);

                    $game = Game::fromHaloDotApi((array) $gameData);
                    $firstPulledGameId = $firstPulledGameId ?? $game->id ?? null;

                    // Due to limitation `fromHaloDotApi` only takes an array.
                    $gameData['_leaf']['player'] = Player::fromGamertag(
                        Arr::get($data, 'additional.parameters.gamertag')
                    );
                    $gameData['_leaf']['game'] = $game;

                    GamePlayer::fromHaloDotApi($gameData);

                    if (! $forceUpdate && $game && $game->id === $player->$lastGameIdVariable) {
                        break 2;
                    }
                }
            }
        }

        // Save the Player with the latest game pulled (Custom vs Matchmaking)
        $player->$lastGameIdVariable = $firstPulledGameId;
        $player->saveOrFail();

        return GamePlayer::query()
            ->where('player_id', $player->id)
            ->limit(25)
            ->get();
    }

    public function match(string $matchUuid): ?Game
    {
        $response = $this->getPendingRequest()->get('stats/matches', [
            'ids' => Arr::wrap($matchUuid),
        ])->throw();

        $data = $response->json();

        $lastMatch = null;
        foreach (Arr::get($data, 'data') as $match) {
            $lastMatch = Game::fromHaloDotApi((array) Arr::get($match, 'match', []));
        }

        return $lastMatch;
    }

    public function metadataMedals(): Collection
    {
        $response = $this->getPendingRequest()->get('metadata/multiplayer/medals')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $medal) {
            Medal::fromHaloDotApi($medal);
        }

        return Medal::all();
    }

    public function metadataMaps(): Collection
    {
        $response = $this->getPendingRequest()->get('metadata/multiplayer/maps')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $map) {
            Map::fromHaloDotApi($map);
        }

        return Map::all();
    }

    public function metadataTeams(): Collection
    {
        $response = $this->getPendingRequest()->get('metadata/multiplayer/teams')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $team) {
            Team::fromHaloDotApi($team);
        }

        return Team::all();
    }

    public function metadataPlaylists(): Collection
    {
        $response = $this->getPendingRequest()->get('metadata/multiplayer/playlists')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $playlist) {
            Playlist::fromHaloDotApi($playlist);
        }

        return Playlist::all();
    }

    public function metadataCategories(): Collection
    {
        $response = $this->getPendingRequest()->get('metadata/multiplayer/gamevariants')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $category) {
            Category::fromHaloDotApi($category);
        }

        return Category::all();
    }

    public function serviceRecord(Player $player, int $season = 1): ?ServiceRecord
    {
        foreach ([SystemMode::MATCHMADE_PVP(), SystemMode::MATCHMADE_RANKED()] as $filter) {
            $url = 'stats/players/service-record/multiplayer/matchmade/'.$filter->toUrlSlug();
            $season = $season === -1 ? null : $season;

            $response = $this->getPendingRequest()->get($url, [
                'gamertag' => $player->gamertag,
                'season' => $season,
            ]);

            // If we have a 403 - Chances are its because season x is not available.
            // This is recoverable. Just return and say its okay (because its empty and ok)
            if ($response->status() === ResponseAlias::HTTP_FORBIDDEN) {
                continue;
            }

            $data = $response->throw()->json();

            $item = Arr::get($data, 'data');
            $item['_leaf']['player'] = $player;
            $item['_leaf']['filter'] = $filter;
            $item['_leaf']['season'] = $season;
            $item['_leaf']['privacy'] = Arr::get($data, 'additional.privacy');

            ServiceRecord::fromHaloDotApi($item);
        }

        return $player->serviceRecord;
    }

    private function getPendingRequest(): PendingRequest
    {
        return Http::asJson()
            ->baseUrl($this->config['domain'].'/games/halo-infinite/')
            ->timeout(180)
            ->withToken($this->config['key']);
    }
}
