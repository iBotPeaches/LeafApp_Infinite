<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\FindMatchesFromMatchup;
use App\Models\Matchup;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class PullMatchupGame extends Command
{
    protected $signature = 'app:pull-matchup {matchupId}';
    protected $description = 'Pull Matchup and find associated game(s)';

    public function handle(): int
    {
        $matchupId = $this->argument('matchupId');
        $matchup = Matchup::query()
            ->where('faceit_id', $matchupId)
            ->firstOrFail();

        FindMatchesFromMatchup::dispatchSync($matchup);

        return CommandAlias::SUCCESS;
    }
}
