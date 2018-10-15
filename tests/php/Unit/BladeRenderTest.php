<?php

declare(strict_types=1);

namespace AvtoDev\BackendToFrontendVariablesStack\Tests\Unit;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use AvtoDev\BackendToFrontendVariablesStack\Tests\AbstractTestCase;
use AvtoDev\BackendToFrontendVariablesStack\Contracts\BackendToFrontendVariablesInterface;

class BladeRenderTest extends AbstractTestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->artisan('view:clear');
    }

    /**
     * Rendering test.
     *
     * @return void
     */
    public function testRendering()
    {
        /** @var BackendToFrontendVariablesInterface $service */
        $service = $this->app->make(BackendToFrontendVariablesInterface::class);
        /** @var ViewFactory $view */
        $view = $this->app->make(ViewFactory::class);
        /** @var ConfigRepository $config */
        $config = $this->app->make(ConfigRepository::class);

        $view->addNamespace('stubs', __DIR__ . '/../stubs/view');

        $data = [
            'foo' => 'bar',
            'baz' => 123,
            321   => 'asd',
        ];

        foreach ($data as $key => $value) {
            $service->put($key, $value);
        }

        $rendered = $view->make('stubs::view')->render();

        $this->assertRegExp("~window,\s?['\"]{$config->get('back-to-front.stack_name')}['\"],~", $rendered);

        foreach ($data as $key => $value) {
            $this->assertContains((string) $key, $rendered);
            $this->assertContains((string) $value, $rendered);
        }
    }

    /**
     * Rendering test.
     *
     * @return void
     */
    public function testRenderCaching()
    {
        /** @var BackendToFrontendVariablesInterface $service */
        $service = $this->app->make(BackendToFrontendVariablesInterface::class);
        /** @var ViewFactory $view */
        $view = $this->app->make(ViewFactory::class);

        $view->addNamespace('stubs', __DIR__ . '/../stubs/view');

        // Set first state
        $service->put('foo', 'bar');

        $rendered = $view->make('stubs::view')->render();

        $this->assertContains('foo', $rendered);

        // Set another state
        $service->put('test_key', 'bar2');
        $service->forget('foo');

        $rendered2 = $view->make('stubs::view')->render();

        // See actual data
        $this->assertNotContains('foo', $rendered2);
        $this->assertContains('test_key', $rendered2);
    }
}
