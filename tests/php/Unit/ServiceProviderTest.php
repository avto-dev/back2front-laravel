<?php

namespace AvtoDev\BackendToFrontendVariablesStack\Tests\Unit;

use Illuminate\Config\Repository as ConfigRepository;
use AvtoDev\BackendToFrontendVariablesStack\Tests\AbstractTestCase;

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

        $this->assertIsArray($configs);

        foreach (['max_recursion_depth', 'date_format', 'stack_name'] as $item) {
            $this->assertArrayHasKey($item, $configs);
        }
    }
}
