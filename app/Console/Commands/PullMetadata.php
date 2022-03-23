<?php

namespace App\Console\Commands;

use App\Services\Autocode\InfiniteInterface;
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
        $this->client->metadataMedals();
        $this->client->metadataMaps();
        $this->client->metadataTeams();
        $this->client->metadataPlaylists();
        $this->client->metadataCategories();

        return CommandAlias::SUCCESS;
    }
}
