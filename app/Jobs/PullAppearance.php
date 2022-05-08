<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Adapters\FileUtils;
use App\Enums\QueueName;
use App\Models\Player;
use App\Services\Autocode\InfiniteInterface;
use App\Services\Tinify\ImageInterface;
use App\Support\Image\ImageHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PullAppearance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->onQueue(QueueName::APPEARANCE);
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->player->id . 'appearance'))->dontRelease()
        ];
    }

    public function handle(): void
    {
        /** @var InfiniteInterface $client */
        $client = resolve(InfiniteInterface::class);

        $player = $client->appearance($this->player->gamertag);
        $emblemUrl = $player->getRawOriginal('emblem_url');
        $backdropUrl = $player->getRawOriginal('backdrop_url');

        $emblem = $this->getStoragePathFromUrl($emblemUrl, 'emblems');
        $this->downloadIfMissing($emblem, $emblemUrl);

        $backdrop = $this->getStoragePathFromUrl($backdropUrl, 'backdrops');
        $this->downloadIfMissing($backdrop, $backdropUrl);
    }

    private function downloadIfMissing(?string $filename, string $url): void
    {
        if ($filename) {
            if (! Storage::exists($filename)) {
                /** @var ImageInterface $client */
                $client = resolve(ImageInterface::class);

                Storage::put($filename, FileUtils::getFileContents($client->optimize($url)));
            }
        }
    }

    private function getStoragePathFromUrl(string $url, string $type): ?string
    {
        $filename = ImageHelper::getInternalFilenameFromAutocode($url);
        return $filename !== null ? 'public/images/' . $type . '/' . $filename : null;
    }
}
