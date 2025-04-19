<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Models\PlayerBan;
use App\Services\DotApi\InfiniteInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CheckForBan extends Command
{
    protected $signature = 'app:check-for-ban {gamertag}';

    protected $description = 'Check a gamertag for a ban.';

    protected InfiniteInterface $client;

    public function __construct(InfiniteInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle(): int
    {
        /** @var Player|null $player */
        $player = Player::query()
            ->where('gamertag', $this->argument('gamertag'))
            ->first();

        if (empty($player)) {
            $this->error('Player not found.');

            return CommandAlias::FAILURE;
        }

        $wasBanned = $player->checkForBanFromDotApi();

        if (! $wasBanned) {
            $this->output->success('No bans detected!');

            return CommandAlias::SUCCESS;
        } else {
            $this->output->error('Ban(s) detected!');
        }

        $this->table([
            'Message',
            'Expires At',
            'Type',
            'Scope',
        ], $player->bans->where('ends_at', '>', now())->map(function (PlayerBan $ban) {
            return [
                'messsage' => $ban->message,
                'expires_at' => $ban->ends_at->toIso8601ZuluString(),
                'type' => $ban->type,
                'scope' => $ban->scope,
            ];
        })->toArray());

        return CommandAlias::SUCCESS;
    }
}
