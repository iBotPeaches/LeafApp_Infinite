<?php
declare(strict_types=1);

namespace App\Services\HaloDotApi;

use App\Models\Csr;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Player;
use App\Models\ServiceRecord;
use App\Services\HaloDotApi\Enums\Mode;
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
            ->baseUrl($config['domain'] . '/games/hi/')
            ->withToken($config['key'], 'Cryptum-Token')
            ->withHeaders([
                'Cryptum-API-Version' => $config['version']
            ]);
    }

    public function appearance(string $gamertag): ?Player
    {
        $response = $this->pendingRequest->get('appearance/players/' . $gamertag);

        if ($response->successful()) {
            return Player::fromHaloDotApi($response->json());
        }

        return null;
    }

    public function competitive(Player $player): ?Csr
    {
        $response = $this->pendingRequest->get('stats/players/' . $player->gamertag . '/csrs');

        if ($response->throw()->successful()) {
            $data = $response->json();
            $data['player'] = $player;

            return Csr::fromHaloDotApi($data);
        }

        return null;
    }

    public function matches(Player $player, bool $forceUpdate = false): Collection
    {
        $currentPage = 1;
        $nextPage = 1;

        while ($currentPage <= $nextPage) {
            $response = $this->pendingRequest->get('stats/players/' . $player->gamertag .'/matches', [
                'mode' => Mode::MATCHMADE,
                'page' => $nextPage
            ]);

            if ($response->throw()->successful()) {
                $data = $response->json();
                $currentPage = (int)Arr::get($data, 'paging.page', 1);
                $nextPage = (int)Arr::get($data, 'paging.next');

                foreach (Arr::get($data, 'data') as $gameData) {
                    $game = Game::fromHaloDotApi((array)$gameData);

                    // Due to limitation `fromHaloDotApi` only takes an array.
                    $gameData['player'] = Player::fromGamertag(Arr::get($data, 'additional.gamertag'));
                    $gameData['game'] = $game;

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

    public function serviceRecord(Player $player): ?ServiceRecord
    {
        $response = $this->pendingRequest->get('stats/players/' . $player->gamertag . '/service-record/global');

        if ($response->throw()->successful()) {
            $data = $response->json();
            $data['player'] = $player;

            return ServiceRecord::fromHaloDotApi($data);
        }

        return null;
    }
}
