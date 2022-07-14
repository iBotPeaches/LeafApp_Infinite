<?php
declare(strict_types=1);

namespace Tests\Feature\Jobs;

use App\Jobs\FindMatchesFromMatchup;
use App\Models\Matchup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FindMatchesFromMatchupTest extends TestCase
{
    use WithFaker;

    public function testAutocodeMarkedAsDisabled(): void
    {
        // Arrange
        Config::set('services.autocode.disabled', true);

        Http::preventStrayRequests();

        $matchup = Matchup::factory()->createOne();

        // Act
        FindMatchesFromMatchup::dispatchSync($matchup);

        // Assert
        Http::assertNothingSent();
    }
}
