<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Services\XboxApi\XboxInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AddXuidToExistingAccounts extends Command
{
    protected $signature = 'app:pull-xuid';
    protected $description = 'Pull XUID from accounts';

    protected XboxInterface $xboxApi;

    public function __construct(XboxInterface $xboxApi)
    {
        parent::__construct();
        $this->xboxApi = $xboxApi;
    }

    public function handle(): int
    {
        Player::query()
            ->whereNull('xuid')
            ->cursor()
            ->each(function (Player $player) {
                $player->xuid = $this->xboxApi->xuid($player->gamertag);
                $player->saveOrFail();
            });

        return CommandAlias::SUCCESS;
    }
}
