<?php
declare(strict_types=1);

namespace Tests\Feature\Console;

use App\Enums\MedalCategory;
use App\Enums\MedalType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mocks\Metadata\MockMedalsService;
use Tests\TestCase;

class PullMetadataTest extends TestCase
{
    use WithFaker;

    public function testValidDataPull(): void
    {
        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockMetadataResponse = (new MockMedalsService())->success();

        Arr::set($mockMetadataResponse, 'data.2.category', MedalCategory::MODE);
        Arr::set($mockMetadataResponse, 'data.3.type', MedalType::LEGENDARY);

        Http::fakeSequence()->push($mockMetadataResponse, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:pull-metadata')
            ->assertExitCode(CommandAlias::SUCCESS);
    }

    public function testInvalidPullNewCategory(): void
    {
        // Expectations
        $this->expectException(\InvalidArgumentException::class);

        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockMetadataResponse = (new MockMedalsService())->success();

        Arr::set($mockMetadataResponse, 'data.0.category', 'invalid-category');
        Http::fakeSequence()->push($mockMetadataResponse, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:pull-metadata')
            ->assertExitCode(CommandAlias::FAILURE);
    }

    public function testInvalidPullNewType(): void
    {
        // Expectations
        $this->expectException(\InvalidArgumentException::class);

        // Arrange
        $gamertag = $this->faker->word . $this->faker->numerify;
        $mockMetadataResponse = (new MockMedalsService())->success();

        Arr::set($mockMetadataResponse, 'data.0.difficulty', 'invalid-type');
        Http::fakeSequence()->push($mockMetadataResponse, Response::HTTP_OK);

        // Act & Assert
        $this
            ->artisan('app:pull-metadata')
            ->assertExitCode(CommandAlias::FAILURE);
    }
}
