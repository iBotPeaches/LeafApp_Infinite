<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\System\VersionHelper;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use JMac\Testing\Double;
use Tests\TestCase;

class VersionHelperTest extends TestCase
{
    public function test_missing_version_file(): void
    {
        // Arrange
        $filesystem = Double::for(Filesystem::class);
        $filesystem->allows('exists')->returns(true);
        $filesystem->allows('get')->returns(null);
        File::swap($filesystem);

        // Act & Assert
        $this->assertNull(VersionHelper::getVersionString());
    }

    public function test_valid_version_file(): void
    {
        // Arrange
        $filesystem = Double::for(Filesystem::class);
        $filesystem->allows('exists')->returns(true);
        $filesystem->allows('get')->returns('v3.3.3');
        File::swap($filesystem);

        // Act & Assert
        $this->assertEquals('v3.3.3', VersionHelper::getVersionString());
    }
}
