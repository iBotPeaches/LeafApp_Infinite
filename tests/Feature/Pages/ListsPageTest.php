<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ListsPageTest extends TestCase
{
    public function test_example_banned_players(): void
    {
        // Arrange & Act
        $response = $this->get('/lists/banned');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }
}
