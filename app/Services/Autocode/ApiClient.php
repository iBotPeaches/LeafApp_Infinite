<?php
declare(strict_types=1);

namespace App\Services\Autocode;

use App\Models\Csr;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use App\Models\ServiceRecord;
use App\Services\Autocode\Enums\Experience;
use App\Services\Autocode\Enums\Mode;
use App\Services\Autocode\Enums\Playlist;
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

                    // Due to limitation `fromHaloDotApi` only takes an array.
                    $gameData['_leaf']['player'] = Player::fromGamertag(Arr::get($data, 'additional.gamertag'));
                    $gameData['_leaf']['game'] = $game;

                    $gamePlayer = GamePlayer::fromHaloDotApi($gameData);

                    // To prevent loading ALL games to the beginning of time. We look for our first game/player
                    // combo that we already recognize in the database. This means we processed that user/game
                    // and everything prior was already processed.
                    if ($gamePlayer instanceof GamePlayer && !$gamePlayer->wasRecentlyCreated && !$forceUpdate) {
                        break 2;
                    }
                }
            }
        }

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

    public function serviceRecord(Player $player): ?ServiceRecord
    {
        $response = $this->pendingRequest->get('stats/service-record', [
            'gamertag' => $player->gamertag,
            'experience' => Experience::ARENA,
            'playlist' => Playlist::RANKED_ALL,
        ]);

        if ($response->throw()->successful()) {
            $data = $response->json();
            $data['_leaf']['player'] = $player;
            ServiceRecord::fromHaloDotApi($data);
        }

        return $player->serviceRecord;
    }
}
