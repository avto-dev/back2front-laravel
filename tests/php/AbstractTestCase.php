<?php

declare(strict_types = 1);

namespace AvtoDev\Back2Front\Tests;

use AvtoDev\Back2Front\ServiceProvider;
use Illuminate\Contracts\Console\Kernel;

abstract class AbstractTestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @param string ...$service_providers
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = require __DIR__ . '/../../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $app->register(ServiceProvider::class);

        return $app;
    }
}
