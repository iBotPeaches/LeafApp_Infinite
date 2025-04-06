<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Playlist;
use App\Models\PlaylistStat;
use Tests\TestCase;

class PlaylistStatTest extends TestCase
{
    public function test_relation_properties(): void
    {
        $playlistStat = PlaylistStat::factory()->create();

        $this->assertInstanceOf(Playlist::class, $playlistStat->playlist);
    }
}
