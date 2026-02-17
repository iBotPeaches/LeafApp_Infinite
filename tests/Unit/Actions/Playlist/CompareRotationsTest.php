<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Playlist;

use App\Actions\Playlist\CompareRotations;
use Tests\TestCase;

class CompareRotationsTest extends TestCase
{
    public function test_compare_rotations_detects_added_maps(): void
    {
        // Arrange
        $previous = [
            ['name' => 'Slayer on Aquarius', 'weight' => 100],
        ];
        $current = [
            ['name' => 'Slayer on Aquarius', 'weight' => 50],
            ['name' => 'Slayer on Bazaar', 'weight' => 50],
        ];

        // Act
        $result = CompareRotations::execute($current, $previous);

        // Assert
        // Aquarius percentage changed from 100% to 50%, so it should be marked as changed
        $this->assertEquals('changed', $result['maps']['Aquarius']['type']);
        $this->assertEquals(-50.0, $result['maps']['Aquarius']['difference']);
        $this->assertEquals('added', $result['maps']['Bazaar']['type']);
        $this->assertEquals(50.0, $result['maps']['Bazaar']['current']);
    }

    public function test_compare_rotations_detects_removed_maps(): void
    {
        // Arrange
        $previous = [
            ['name' => 'Slayer on Aquarius', 'weight' => 50],
            ['name' => 'Slayer on Bazaar', 'weight' => 50],
        ];
        $current = [
            ['name' => 'Slayer on Aquarius', 'weight' => 100],
        ];

        // Act
        $result = CompareRotations::execute($current, $previous);

        // Assert
        // Aquarius percentage changed from 50% to 100%, so it should be marked as changed
        $this->assertEquals('changed', $result['maps']['Aquarius']['type']);
        $this->assertEquals(50.0, $result['maps']['Aquarius']['difference']);
        $this->assertEquals('removed', $result['maps']['Bazaar']['type']);
        $this->assertEquals(50.0, $result['maps']['Bazaar']['previous']);
    }

    public function test_compare_rotations_detects_percentage_changes(): void
    {
        // Arrange
        $previous = [
            ['name' => 'Slayer on Aquarius', 'weight' => 25],
            ['name' => 'Slayer on Bazaar', 'weight' => 75],
        ];
        $current = [
            ['name' => 'Slayer on Aquarius', 'weight' => 75],
            ['name' => 'Slayer on Bazaar', 'weight' => 25],
        ];

        // Act
        $result = CompareRotations::execute($current, $previous);

        // Assert
        $this->assertEquals('changed', $result['maps']['Aquarius']['type']);
        $this->assertEquals(50.0, $result['maps']['Aquarius']['difference']); // 75% - 25% = 50%
        $this->assertEquals('changed', $result['maps']['Bazaar']['type']);
        $this->assertEquals(-50.0, $result['maps']['Bazaar']['difference']); // 25% - 75% = -50%
    }

    public function test_compare_rotations_handles_gametypes(): void
    {
        // Arrange
        $previous = [
            ['name' => 'Arena:Slayer on Aquarius', 'weight' => 100],
        ];
        $current = [
            ['name' => 'Arena:Slayer on Aquarius', 'weight' => 50],
            ['name' => 'Arena:Strongholds on Aquarius', 'weight' => 50],
        ];

        // Act
        $result = CompareRotations::execute($current, $previous);

        // Assert
        // Slayer percentage changed from 100% to 50%, so it should be marked as changed
        $this->assertEquals('changed', $result['gametypes']['Slayer']['type']);
        $this->assertEquals(-50.0, $result['gametypes']['Slayer']['difference']);
        $this->assertEquals('added', $result['gametypes']['Strongholds']['type']);
        $this->assertEquals(50.0, $result['gametypes']['Strongholds']['current']);
    }
}