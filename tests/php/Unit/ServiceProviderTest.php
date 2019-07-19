<?php

declare(strict_types = 1);

namespace AvtoDev\Back2Front\Tests\Unit;

use AvtoDev\Back2Front\Tests\AbstractTestCase;
use Illuminate\Config\Repository as ConfigRepository;

/**
 * Class ServiceProviderTest.
 *
 * @group back-to-front
 */
class ServiceProviderTest extends AbstractTestCase
{
    /**
     * @var string Ключ конфига
     */
    protected $config_key = 'back-to-front';

    /**
     * Check config.
     */
    public function testConfigExists(): void
    {
        $configs = $this->app->make(ConfigRepository::class)->get($this->config_key);

        $this->assertInternalType('array', $configs);

        foreach (['max_recursion_depth', 'date_format', 'stack_name'] as $item) {
            $this->assertArrayHasKey($item, $configs);
        }
    }
}
