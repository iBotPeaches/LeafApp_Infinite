<?php
declare(strict_types=1);

namespace App\Services\FaceIt;

use App\Models\Championship;
use App\Models\Matchup;
use App\Models\Pivots\TeamPlayer;
use App\Models\Team;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ApiClient implements TournamentInterface
{
    private PendingRequest $pendingRequest;

    public function __construct(array $config)
    {
        $this->pendingRequest = Http::asJson()
            ->baseUrl($config['domain'] . '/data/v4/')
            ->withToken($config['key']);
    }

    public function championship(string $championshipId): ?Championship
    {
        $response = $this->pendingRequest->get('championships/' . $championshipId)->throw();
        $data = $response->json();

        return Championship::fromFaceItApi((array)$data);
    }

    public function bracket(Championship $championship): Collection
    {
        $perPage = 100;
        $count = $perPage;
        $offset = 0;

        while ($count !== 0) {
            $response = $this->pendingRequest->get('championships/' . $championship->faceit_id . '/matches', [
                'type' => 'past',
                'offset' => $offset,
                'limit' => $perPage
            ])->throw();

            $data = $response->json();
            $count = count(Arr::get($data, 'items', []));
            $offset += $perPage;

            foreach (Arr::get($data, 'items', []) as $matchupData) {
                $matchupData['_leaf']['championship'] = $championship;
                $matchup = Matchup::fromFaceItApi((array)$matchupData);

                foreach (Arr::get($matchupData, 'teams', []) as $teamId => $teamData) {
                    $teamData['_leaf']['matchup'] = $matchup;
                    $teamData['_leaf']['raw_matchup'] = $matchupData;
                    $teamData['_leaf']['team_id'] = $teamId;
                    $team = Team::fromFaceItApi((array)$teamData);

                    foreach (Arr::get($teamData, 'roster', []) as $playerData) {
                        $playerData['_leaf']['team'] = $team;
                        $playerData['_leaf']['matchup'] = $matchup;
                        TeamPlayer::fromFaceItApi($playerData);
                    }
                }
            }
        }


        return $championship->matchups;
    }
}
