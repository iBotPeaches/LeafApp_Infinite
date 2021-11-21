<?php
declare(strict_types=1);

namespace Tests\Mocks;

use Illuminate\Foundation\Testing\WithFaker;

abstract class BaseMock
{
    use WithFaker;

    public function __construct()
    {
        $this->setUpFaker();
    }
}
