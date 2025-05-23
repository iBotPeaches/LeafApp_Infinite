<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\System\VersionHelper;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class VersionHelperTest extends TestCase
{
    public function test_missing_version_file(): void
    {
        // Arrange
        File::shouldReceive('exists')
            ->andReturnTrue();
        File::shouldReceive('get')
            ->andReturnNull();

        // Act & Assert
        $this->assertNull(VersionHelper::getVersionString());
    }

    public function test_valid_version_file(): void
    {
        // Arrange
        File::shouldReceive('exists')
            ->andReturnTrue();
        File::shouldReceive('get')
            ->andReturn('v3.3.3');

        // Act & Assert
        $this->assertEquals('v3.3.3', VersionHelper::getVersionString());
    }
}
