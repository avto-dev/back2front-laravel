<?php

declare(strict_types=1);

namespace AvtoDev\BackendToFrontendVariablesStack\Tests\Unit;

use Illuminate\Contracts\View\Factory as ViewFactory;
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

        $view->addNamespace('stubs', __DIR__ . '/../stubs/view');

        $data = [
            'foo' => 'bar',
            'baz' => 123,
            123   => 'asd',
        ];

        foreach ($data as $key => $value) {
            $service->put($key, $value);
        }

        $rendered = $view->make('stubs::view')->render();

        foreach ($data as $key => $value) {
            $this->assertContains((string) $key, $rendered);
            $this->assertContains((string) $value, $rendered);
        }
    }
}
