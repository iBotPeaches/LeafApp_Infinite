<?php

namespace App\Console\Commands;

use App\Services\HaloDotApi\InfiniteInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class PullMetadata extends Command
{
    protected $signature = 'app:pull-metadata';

    protected $description = 'Pulls Metadata from endpoints';

    protected InfiniteInterface $client;

    public function __construct(InfiniteInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle(): int
    {
        $this->line('Pulling Medals...');
        $this->client->metadataMedals();

        $this->line('Pulling Maps...');
        $this->client->metadataMaps();

        $this->line('Pulling Teams...');
        $this->client->metadataTeams();

        $this->line('Pulling Playlists...');
        $this->client->metadataPlaylists();

        $this->line('Pulling Categories...');
        $this->client->metadataCategories();

        return CommandAlias::SUCCESS;
    }
}
