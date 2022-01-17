<?php
declare(strict_types=1);

namespace App\Services\Autocode;

use App\Models\Csr;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Medal;
use App\Models\Player;
use App\Models\ServiceRecord;
use App\Services\Autocode\Enums\Filter;
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

    public function competitive(Player $player): ?Csr
    {
        $response = $this->pendingRequest->get('stats/csrs', [
            'gamertag' => $player->gamertag,
            'season' => 1
        ]);

        if ($response->throw()->successful()) {
            $data = $response->json();
            $data['player'] = $player;
            Csr::fromHaloDotApi($data);
        }

        return $player->csrs->first();
    }

    public function matches(Player $player, bool $forceUpdate = false): Collection
    {
        $perPage = 25;
        $count = $perPage;
        $offset = 0;
        $firstPulledGameId = null;

        while ($count !== 0) {
            $response = $this->pendingRequest->post('stats/matches/list', [
                'gamertag' => $player->gamertag,
                'limit' => [
                    'count' => $perPage,
                    'offset' => $offset
                ]
            ]);

            if ($response->throw()->successful()) {
                $data = $response->json();
                $count = count(Arr::get($data, 'data', []));
                $offset += $perPage;

                foreach (Arr::get($data, 'data') as $gameData) {
                    $game = Game::fromHaloDotApi((array)$gameData);
                    $firstPulledGameId = $firstPulledGameId ?? $game->id ?? null;

                    // Due to limitation `fromHaloDotApi` only takes an array.
                    $gameData['_leaf']['player'] = Player::fromGamertag(Arr::get($data, 'additional.gamertag'));
                    $gameData['_leaf']['game'] = $game;

                    GamePlayer::fromHaloDotApi($gameData);

                    if (!$forceUpdate && $game && $game->id === $player->last_game_id_pulled) {
                        break 2;
                    }
                }
            }
        }

        // Save the Player with the latest game pulled
        $player->last_game_id_pulled = $firstPulledGameId;
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
        $response = $this->pendingRequest->get('metadata/medals/list')->throw();

        $data = $response->json();
        foreach (Arr::get($data, 'data') as $medal) {
            Medal::fromHaloDotApi($medal);
        }

        return Medal::all();
    }

    public function serviceRecord(Player $player): ?ServiceRecord
    {
        $response = $this->pendingRequest->get('stats/service-record/multiplayer', [
            'gamertag' => $player->gamertag,
            'filter' => Filter::MATCHMADE_RANKED,
        ]);

        if ($response->throw()->successful()) {
            $data = $response->json();
            $data['_leaf']['player'] = $player;
            ServiceRecord::fromHaloDotApi($data);
        }

        return $player->serviceRecord;
    }
}
