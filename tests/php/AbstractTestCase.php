<?php

declare(strict_types = 1);

namespace AvtoDev\Back2Front\Tests;

use Illuminate\Foundation\Application;
use AvtoDev\Back2Front\ServiceProvider;
use AvtoDev\DevTools\Tests\PHPUnit\AbstractLaravelTestCase;

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
