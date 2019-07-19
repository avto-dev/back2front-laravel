<?php

namespace AvtoDev\BackendToFrontendVariablesStack\Tests;

use Illuminate\Foundation\Application;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;
use AvtoDev\BackendToFrontendVariablesStack\ServiceProvider;

abstract class AbstractTestCase extends AbstractLaravelTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function afterApplicationBootstrapped(Application $app)
    {
        $app->register(ServiceProvider::class);
    }
}
