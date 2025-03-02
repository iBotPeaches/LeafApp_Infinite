<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Championship;
use App\Services\FaceIt\TournamentInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class PullChampionship extends Command
{
    protected $signature = 'app:championship {championshipId}';

    protected $description = 'Pulls Championship from FaceIt';

    protected TournamentInterface $client;

    public function __construct(TournamentInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle(): int
    {
        $championshipId = $this->argument('championshipId');
        $championship = $this->client->championship($championshipId);

        if ($championship instanceof Championship) {
            $this->client->bracket($championship);
        }

        return CommandAlias::SUCCESS;
    }
}
