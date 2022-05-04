<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use App\Models\Championship;
use App\Models\Player;
use App\Services\FaceIt\TournamentInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class DownloadAppearance extends Command
{
    protected $signature = 'app:download-appearance';
    protected $description = 'Iterates profile(s) to find appearances to download.';

    public function handle(): int
    {
        Player::query()
            ->whereNotNull('emblem_url')
            ->whereNotNull('backdrop_url')
            ->cursor()
            ->each(function (Player $player) {
                $emblemFilename = $this->getFilenameFromAsset($player->emblem_url);
                $backdropFilename = $this->getFilenameFromAsset($player->backdrop_url);
            });
    }

    public function getFilenameFromAsset(string $asset): string
    {

    }
}
