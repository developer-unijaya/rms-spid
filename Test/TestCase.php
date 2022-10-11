<?php

namespace DeveloperUnijaya\RmsSpid\Test;

use DeveloperUnijaya\RmsSpid\RmsSpidServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            RmsSpidServiceProvider::class,
        ];
    }

    public function markTestAsPassed(): void
    {
        $this->assertTrue(true);
    }

}
