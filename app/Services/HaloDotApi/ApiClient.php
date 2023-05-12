<?php

declare(strict_types=1);

namespace App\Services\HaloDotApi;

use App\Enums\Mode as SystemMode;
use App\Models\Category;
use App\Models\Csr;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Level;
use App\Models\Medal;
use App\Models\Player;
use App\Models\PlayerBan;
use App\Models\Playlist;
use App\Models\Season;
use App\Models\ServiceRecord;
use App\Models\Team;
use App\Services\HaloDotApi\Enums\Mode;
use App\Support\Session\SeasonSession;
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
        $urlSafeGamertag = urlencode($gamertag);
        $response = $this->asHaloInfinite()->get("appearance/players/{$urlSafeGamertag}/spartan-id");

        if ($response->successful()) {
            return Player::fromHaloDotApi($response->json());
        }

        return null;
    }

    public function competitive(Player $player, ?string $seasonCsrKey = null): ?Csr
    {
        // Handle when -1 (no season) is sent here.
        $season = $seasonCsrKey === SeasonSession::$allSeasonKey ? null : $seasonCsrKey;
        $currentSeasonNumber = (int) config('services.halodotapi.competitive.season');
        $season ??= Season::latestOfSeason($currentSeasonNumber)?->csr_key;

        $queryParams = [];
        if ($season) {
            $queryParams['season_csr'] = $season;
        }

        $response = $this->asHaloInfinite()->get("stats/multiplayer/players/{$player->url_safe_gamertag}/csrs", $queryParams);

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
            $response = $this->asHaloInfinite()->get("stats/multiplayer/players/{$player->url_safe_gamertag}/matches", [
                'type' => (string) $mode->value,
                'count' => $perPage,
                'offset' => $offset,
            ]);

            if ($response->throw()->successful()) {
                $data = $response->json();
                $count = (int) Arr::get($data, 'additional.total');
                $offset += $perPage;

                foreach (Arr::get($data, 'data') as $gameData) {
                    // HaloDotAPI - We can longer trust "type" as its not returning the matching value from the filtered search.
                    // This field may be deprecated in future. So force set it based on filter param.
                    // https://github.com/iBotPeaches/LeafApp_Infinite/issues/560
                    Arr::set($gameData, 'properties.type', (string) $mode->value);

                    $game = Game::fromHaloDotApi((array) $gameData);
                    $firstPulledGameId = $firstPulledGameId ?? $game->id ?? null;

                    // Due to limitation `fromHaloDotApi` only takes an array.
                    $gameData['_leaf']['player'] = $player;
                    $gameData['_leaf']['game'] = $game;

                    GamePlayer::fromHaloDotApi($gameData);

                    if (! $forceUpdate && $game && $game->id === $player->$lastGameIdVariable) {
                        break 2;
                    }
                }
            }
        }

        // Save the Player with the latest game pulled (Custom vs Matchmaking)
        $player->$lastGameIdVariable = $firstPulledGameId ?? $player->$lastGameIdVariable;
        $player->saveOrFail();

        return GamePlayer::query()
            ->where('player_id', $player->id)
            ->limit(25)
            ->get();
    }

    public function match(string $matchUuid): ?Game
    {
        $response = $this->asHaloInfinite()->get("stats/multiplayer/matches/{$matchUuid}")->throw();
        $data = $response->json();

        return Game::fromHaloDotApi((array) (Arr::get($data, 'data')));
    }

    public function metadataMedals(): Collection
    {
        $response = $this->asHaloInfinite()->get('metadata/multiplayer/medals')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $medal) {
            Medal::fromHaloDotApi($medal);
        }

        return Medal::all();
    }

    public function metadataMaps(): Collection
    {
        $response = $this->asHaloInfinite()->get('metadata/multiplayer/maps')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $map) {
            Level::fromHaloDotApi($map);
        }

        return Level::all();
    }

    public function metadataTeams(): Collection
    {
        $response = $this->asHaloInfinite()->get('metadata/multiplayer/teams')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $team) {
            Team::fromHaloDotApi($team);
        }

        return Team::all();
    }

    public function metadataPlaylists(): Collection
    {
        $response = $this->asHaloInfinite()->get('metadata/multiplayer/playlists')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $playlist) {
            Playlist::fromHaloDotApi($playlist);
        }

        return Playlist::all();
    }

    public function metadataCategories(): Collection
    {
        $response = $this->asHaloInfinite()->get('metadata/multiplayer/modes/categories')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $category) {
            Category::fromHaloDotApi($category);
        }

        return Category::all();
    }

    public function metadataSeasons(): Collection
    {
        $response = $this->asHaloInfinite()->get('metadata/multiplayer/seasons')->throw();
        $data = $response->json();

        foreach (Arr::get($data, 'data') as $season) {
            Season::fromHaloDotApi($season);
        }

        return Season::all();
    }

    public function serviceRecord(Player $player, ?string $seasonIdentifier = null): ?ServiceRecord
    {
        $url = "stats/multiplayer/players/{$player->url_safe_gamertag}/service-record/matchmade/";
        $season = $seasonIdentifier === SeasonSession::$allSeasonKey ? null : $seasonIdentifier;
        $seasonModel = Season::ofSeasonIdentifierOrKey($seasonIdentifier);
        $defaultFilters = [SystemMode::MATCHMADE_PVP(), SystemMode::MATCHMADE_RANKED()];

        foreach (($seasonModel?->getAvailableFilters() ?? $defaultFilters) as $filter) {
            $queryParams = [
                'filter' => $filter->toUrlSlug(),
            ];

            if ($season) {
                $queryParams['season_id'] = $seasonIdentifier;
            }

            $response = $this->asHaloInfinite()->get($url, $queryParams);

            // If we have a 403 - Chances are its because season x is not available.
            // This is recoverable. Just return and say its okay (because its empty and ok)
            if ($response->status() === ResponseAlias::HTTP_FORBIDDEN) {
                continue;
            }

            $data = $response->throw()->json();

            $item = Arr::get($data, 'data');
            $item['_leaf']['player'] = $player;
            $item['_leaf']['filter'] = $filter;
            $item['_leaf']['season'] = $seasonModel;

            ServiceRecord::fromHaloDotApi($item);
        }

        return $player->serviceRecord;
    }

    public function banSummary(Player $player): Collection
    {
        $response = $this->asHaloInfinite()->get('tooling/players/'.$player->url_safe_gamertag.'/bansummary')->throw();
        $data = $response->json();

        foreach (Arr::get($data, 'data') as $ban) {
            $ban['_leaf']['player'] = $player;

            PlayerBan::fromHaloDotApi($ban);
        }

        return $player->bans;
    }

    public function xuid(string $gamertag): ?string
    {
        $url = '/tooling/xbox-network/players/'.$gamertag.'/details';
        $response = $this->asXbox()->get($url)->throw();
        $data = $response->json();

        return Arr::get($data, 'data.xuid');
    }

    private function getPendingRequest(): PendingRequest
    {
        return Http::asJson()
            ->withUserAgent('Leaf - '.config('sentry.release', 'dirty'))
            ->withHeaders([
                'Halo.API-Version' => config('services.halodotapi.version', '2023-04-07'),
            ])
            ->timeout(180)
            ->withToken($this->config['key']);
    }

    private function asXbox(): PendingRequest
    {
        return $this->getPendingRequest()
            ->baseUrl($this->config['domain'].'/tooling/xbox-network');
    }

    private function asHaloInfinite(): PendingRequest
    {
        return $this->getPendingRequest()
            ->baseUrl($this->config['domain'].'/games/halo-infinite/');
    }
}
