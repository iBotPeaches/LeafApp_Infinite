<?php

declare(strict_types=1);

namespace Tests\Feature\Pages;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_example_feature(): void
    {
        // Arrange & Act
        $response = $this->get('/about');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }
}
