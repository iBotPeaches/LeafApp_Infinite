<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Services\HaloDotApi\InfiniteInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class PullHaloData extends Command
{
    protected $signature = 'app:pull-halo-data {player}';
    protected $description = 'Pull all data from API';

    protected InfiniteInterface $client;

    public function __construct(InfiniteInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle(): int
    {
        /** @var Player|null $player */
        $player = Player::query()->find($this->argument('player'));

        if (empty($player)) {
            $this->error('Player not found.');
            return CommandAlias::FAILURE;
        }

        $this->client->competitive($player);
        //$this->client->matches($player, true);

        return CommandAlias::SUCCESS;
    }
}
