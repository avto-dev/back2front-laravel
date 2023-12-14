<?php

declare(strict_types = 1);

namespace AvtoDev\Back2Front\Tests\Unit;

use AvtoDev\Back2Front\Tests\AbstractTestCase;
use Illuminate\Config\Repository as ConfigRepository;

/**
 * @covers \AvtoDev\Back2Front\ServiceProvider
 */
class ServiceProviderTest extends AbstractTestCase
{
    /**
     * @var string
     */
    protected $config_key = 'back-to-front';

    /**
     * @return void
     */
    public function testConfigExists(): void
    {
        $configs = $this->app->make(ConfigRepository::class)->get($this->config_key);

        $this->assertIsArray($configs);

        foreach (['max_recursion_depth', 'date_format', 'stack_name'] as $item) {
            $this->assertArrayHasKey($item, $configs);
        }
    }
}
