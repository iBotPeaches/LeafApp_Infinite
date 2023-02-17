<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Adapters\FileUtilInterface;
use App\Enums\QueueName;
use App\Models\MatchupTeam;
use App\Services\Tinify\ImageInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PullLogoFromMatchupTeam implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public MatchupTeam $matchupTeam;
    public string $avatar;

    public function __construct(MatchupTeam $matchupTeam, string $avatar)
    {
        $this->matchupTeam = $matchupTeam;
        $this->avatar = $avatar;
        $this->onQueue(QueueName::APPEARANCE);
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->matchupTeam->id.'appearance'))->dontRelease(),
        ];
    }

    public function handle(): void
    {
        $logo = $this->getStoragePathFromUrl($this->matchupTeam->faceit_id, 'logos');
        $this->downloadIfMissing($logo.'.png', $this->avatar);
    }

    private function downloadIfMissing(?string $filename, ?string $url): void
    {
        if ($filename && $url) {
            if (! Storage::exists($filename)) {
                /** @var ImageInterface $client */
                $client = resolve(ImageInterface::class);

                Storage::put($filename, (string) resolve(FileUtilInterface::class)->getFileContents(
                    $client->optimize($url)
                ));
            }
        }
    }

    private function getStoragePathFromUrl(?string $faceidId, string $type): ?string
    {
        return $faceidId !== null ? 'public/images/'.$type.'/'.$faceidId : null;
    }
}
