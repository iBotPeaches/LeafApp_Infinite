<?php

namespace App\Console\Commands;

use App\Adapters\FileUtilInterface;
use App\Models\Overview;
use App\Services\Tinify\ImageInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PullOverviewMapImages extends Command
{
    protected $signature = 'app:pull-overview-images';

    protected $description = 'Pull overview images for maps.';

    public function handle(): int
    {
        $overviews = Overview::query()->cursor();
        $bar = $this->output->createProgressBar($overviews->count());

        $overviews->each(function (Overview $overview) use ($bar) {
            $bar->advance();

            $url = $overview->thumbnail_url;
            $client = resolve(ImageInterface::class);
            $filename = public_path('images/maps/'.$overview->slug.'.jpg');

            if (Http::head($url)->successful()) {
                Storage::put($filename, (string) resolve(FileUtilInterface::class)->getFileContents(
                    $client->optimize($url)
                ));
            }
        });

        $bar->finish();

        return self::SUCCESS;
    }
}
