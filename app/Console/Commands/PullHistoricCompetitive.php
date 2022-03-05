<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use App\Jobs\PullCompetitiveHistory;
use App\Models\Player;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class PullHistoricCompetitive extends Command
{
    protected $signature = 'app:pull-historic-competitive';
    protected $description = 'Slowly chunks record(s) for CSR update.';

    public function handle(): int
    {
        $csrKey = config('services.autocode.competitive.season')
            . config('services.autocode.competitive.version');

        Player::query()
            ->where('last_csr_key', '<>', $csrKey)
            ->orWhereNull('last_csr_key')
            ->orderByDesc('id')
            ->limit(rand(35, 60))
            ->cursor()
            ->each(function (Player $player) use ($csrKey) {
                PullCompetitiveHistory::dispatch($player);

                $player->last_csr_key = $csrKey;
                $player->saveOrFail();
            });

        return CommandAlias::SUCCESS;
    }
}
