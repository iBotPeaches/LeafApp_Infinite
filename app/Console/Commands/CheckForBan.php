<?php

namespace App\Console\Commands;

use App\Models\Player;
use App\Models\PlayerBan;
use App\Services\HaloDotApi\InfiniteInterface;
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

        $bans = $this->client->banSummary($player);

        if ($bans->isEmpty()) {
            $this->output->success('No bans detected!');

            return CommandAlias::SUCCESS;
        }

        $this->output->error('Ban(s) detected!');
        $player->is_cheater = true;
        $player->saveOrFail();

        $this->table([
            'Message',
            'Expires At',
            'Type',
            'Scope',
        ], $bans->map(function (PlayerBan $ban) { // @phpstan-ignore-line
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
