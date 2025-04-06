<?php

namespace App\Console\Commands;

use App\Jobs\ProcessPlaylistAnalytic;
use App\Models\Playlist;
use Illuminate\Console\Command;

class RefreshPlaylistAnalytics extends Command
{
    protected $signature = 'analytics:playlists:refresh';

    protected $description = 'Refreshes cache table of playlist stats.';

    public function handle(): int
    {
        Playlist::query()
            ->where('is_active', true)
            ->each(function (Playlist $playlist) {
                $startTime = time();
                $this->output->writeln('Processing: '.$playlist->name);

                ProcessPlaylistAnalytic::dispatchSync($playlist);

                $this->output->writeln('Processed in '.(time() - $startTime).' seconds.');
            });

        return self::SUCCESS;
    }
}
