<?php
declare(strict_types=1);

namespace Tests\Feature\Pages;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LegalPageTest extends TestCase
{
    public function testExampleLegal(): void
    {
        // Arrange & Act
        $response = $this->get('/legal');

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }
}
