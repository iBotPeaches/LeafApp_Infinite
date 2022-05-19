<?php
declare(strict_types = 1);

namespace App\Console\Commands;

use App\Models\Championship;
use App\Models\Game;
use App\Models\Matchup;
use App\Models\Medal;
use App\Models\Player;
use App\Models\Scrim;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Symfony\Component\Console\Command\Command as CommandAlias;

class SitemapGenerate extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.';

    public function handle(): int
    {
        $sitemapFolder = public_path('sitemaps');
        File::makeDirectory($sitemapFolder, 0755, true, true);

        Sitemap::create()
            ->add(Championship::all())
            ->writeToFile($sitemapFolder . '/sitemap_championships.xml');

        Sitemap::create()
            ->add(Scrim::all())
            ->writeToFile($sitemapFolder . '/sitemap_scrims.xml');

        Sitemap::create()
            ->add(Medal::all())
            ->writeToFile($sitemapFolder . '/sitemap_medals.xml');

        $playerIndex = 0;
        Player::query()
            ->withoutEagerLoads()
            ->chunkById(40000, function (Collection $players) use ($sitemapFolder, &$playerIndex) {
                Sitemap::create()
                    ->add($players)
                    ->writeToFile(sprintf($sitemapFolder . '/sitemap_players_%d.xml', $playerIndex++));
            });

        $matchupIndex = 0;
        Matchup::query()
            ->withoutEagerLoads()
            ->chunkById(25000, function (Collection $matchups) use ($sitemapFolder, &$matchupIndex) {
                Sitemap::create()
                    ->add($matchups)
                    ->writeToFile(sprintf($sitemapFolder . '/sitemap_matchups_%d.xml', $matchupIndex++));
            });

        // Index
        $sitemapIndex = SitemapIndex::create()->add('/sitemaps/sitemap_championships.xml');
        $sitemapIndex->add('/sitemaps/sitemap_scrims.xml');
        $sitemapIndex->add('/sitemaps/sitemap_medals.xml');

        for ($i = 0; $i < $playerIndex; $i++) {
            $sitemapIndex->add(sprintf('/sitemaps/sitemap_players_%d.xml', $i));
        }

        for ($i = 0; $i < $matchupIndex; $i++) {
            $sitemapIndex->add(sprintf('/sitemaps/sitemap_matchups_%d.xml', $i));
        }

        $sitemapIndex->writeToFile($sitemapFolder . '/sitemap.xml');

        return CommandAlias::SUCCESS;
    }
}
