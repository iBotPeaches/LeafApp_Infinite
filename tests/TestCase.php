<?php

namespace Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use JMac\Testing\Integrations\PHPUnit\VerifiesDoubles;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;
    use VerifiesDoubles;
}
