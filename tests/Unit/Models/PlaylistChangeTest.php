<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Playlist;
use App\Models\PlaylistChange;
use Tests\TestCase;

class PlaylistChangeTest extends TestCase
{
    public function test_relation_properties(): void
    {
        /** @var PlaylistChange $playlistChange */
        $playlistChange = PlaylistChange::factory()->createOne();

        $this->assertInstanceOf(Playlist::class, $playlistChange->playlist);
    }
}
