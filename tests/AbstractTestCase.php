<?php

namespace AvtoDev\BackendToFrontendVariablesStack\Tests;

use AvtoDev\BackendToFrontendVariablesStack\StackServiceProvider;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;
use Illuminate\Foundation\Application;

abstract class AbstractTestCase extends AbstractLaravelTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function afterApplicationBootstrapped(Application $app)
    {
        $app->register(StackServiceProvider::class);
    }
}
