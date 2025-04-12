<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\Playlist\HashRotations;
use Tests\TestCase;

class HashRotationTest extends TestCase
{
    public function test_hash_rotations_sorts_automatically(): void
    {
        // Arrange
        $rotations = [
            ['name' => 'Map A', 'id' => 1],
            ['name' => 'Map B', 'id' => 2],
            ['name' => 'Map C', 'id' => 3],
        ];

        $rotatedRotations = [
            ['name' => 'Map C', 'id' => 3],
            ['name' => 'Map A', 'id' => 1],
            ['name' => 'Map B', 'id' => 2],
        ];

        // Act
        $hash = HashRotations::execute($rotations);
        $rotatedHash = HashRotations::execute($rotatedRotations);

        // Assert
        $this->assertSame($hash, $rotatedHash);
    }
}
