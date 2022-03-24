<?php
declare(strict_types=1);

namespace App\Services\Autocode;

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
use App\Services\Autocode\Enums\Filter;
use App\Services\Autocode\Enums\Language;
use App\Services\Autocode\Enums\Mode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ApiClient implements InfiniteInterface
{
    private PendingRequest $pendingRequest;

    public function __construct(array $config)
    {
        $this->pendingRequest = Http::asJson()
            ->baseUrl($config['domain'] . '/infinite@'. $config['version'])
            ->timeout(180)
            ->withToken($config['key']);
    }

    public function appearance(string $gamertag): ?Player
    {
        $response = $this->pendingRequest->get('appearance', [
            'gamertag' => $gamertag
        ]);

        if ($response->successful()) {
            return Player::fromHaloDotApi($response->json());
        }

        return null;
    }

    public function competitive(Player $player, int $season = 1, int $version = 2): ?Csr
    {
        $response = $this->pendingRequest->get('stats/players/csrs', [
            'gamertag' => $player->gamertag,
            'season' => $season,
            'version' => $version
        ]);

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
            $response = $this->pendingRequest->post('stats/players/matches', [
                'gamertag' => $player->gamertag,
                'type' => (string)$mode->value,
                'language' => Language::US,
                'count' => $perPage,
                'offset' => $offset,
            ]);

            if ($response->throw()->successful()) {
                $data = $response->json();
                $count = (int)Arr::get($data, 'additional.count');
                $offset += $perPage;

                foreach (Arr::get($data, 'data.matches') as $gameData) {
                    $gameData['_leaf']['mode'] = $mode;
                    $game = Game::fromHaloDotApi((array)$gameData);
                    $firstPulledGameId = $firstPulledGameId ?? $game->id ?? null;

                    // Due to limitation `fromHaloDotApi` only takes an array.
                    $gameData['_leaf']['player'] = Player::fromGamertag(
                        Arr::get($data, 'additional.parameters.gamertag')
                    );
                    $gameData['_leaf']['game'] = $game;

                    GamePlayer::fromHaloDotApi($gameData);

                    if (!$forceUpdate && $game && $game->id === $player->$lastGameIdVariable) {
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
        $response = $this->pendingRequest->get('stats/matches/retrieve', [
            'id' => $matchUuid
        ])->throw();

        $data = $response->json();

        return Game::fromHaloDotApi((array)Arr::get($data, 'data', []));
    }

    public function metadataMedals(): Collection
    {
        $response = $this->pendingRequest->get('metadata/multiplayer/medals')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $medal) {
            Medal::fromHaloDotApi($medal);
        }

        return Medal::all();
    }

    public function metadataMaps(): Collection
    {
        $response = $this->pendingRequest->get('metadata/multiplayer/maps')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $map) {
            Map::fromHaloDotApi($map);
        }

        return Map::all();
    }

    public function metadataTeams(): Collection
    {
        $response = $this->pendingRequest->get('metadata/multiplayer/teams')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $team) {
            Team::fromHaloDotApi($team);
        }

        return Team::all();
    }

    public function metadataPlaylists(): Collection
    {
        $response = $this->pendingRequest->get('metadata/multiplayer/playlists')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $playlist) {
            Playlist::fromHaloDotApi($playlist);
        }

        return Playlist::all();
    }

    public function metadataCategories(): Collection
    {
        $response = $this->pendingRequest->get('metadata/multiplayer/gamevariants')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $category) {
            Category::fromHaloDotApi($category);
        }

        return Category::all();
    }

    public function serviceRecord(Player $player, Filter $filter): ?ServiceRecord
    {
        $response = $this->pendingRequest->get('stats/players/service-record/multiplayer', [
            'gamertag' => $player->gamertag,
            'filter' => (string)$filter->value,
        ]);

        if ($response->throw()->successful()) {
            $data = $response->json();

            foreach ([SystemMode::MATCHMADE_PVP(), SystemMode::MATCHMADE_RANKED()] as $filter) {
                $item = Arr::get($data, 'data.records.' . $filter->toHistorySlug());
                $item['_leaf']['player'] = $player;
                $item['_leaf']['filter'] = $filter;
                $item['_leaf']['privacy'] = Arr::get($data, 'data.privacy');

                ServiceRecord::fromHaloDotApi($item);
            }
        }

        return $player->serviceRecord;
    }
}
