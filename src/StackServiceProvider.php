<?php

declare(strict_types = 1);

namespace AvtoDev\BackendToFrontendVariablesStack;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use AvtoDev\BackendToFrontendVariablesStack\Service\BackendToFrontendVariablesStack;
use AvtoDev\BackendToFrontendVariablesStack\Contracts\BackendToFrontendVariablesInterface;

class StackServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->initializeConfigs();

        $this->initializeAssets();

        $this->registerHelpers();

        $this->registerService();

        $this->registerBlade();
    }

    /**
     * Register Blade directives.
     *
     * @return void
     */
    protected function registerBlade()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $blade) {
            $blade->directive('back_to_front_data', function ($stack_name = null) {
                /** @var BackendToFrontendVariablesInterface $service */
                $service    = $this->app->make(BackendToFrontendVariablesInterface::class);
                $stack_name = \trim($stack_name ?? config('back-to-front.data_name'), ' \'"');
                $tag_text   = '<script type="text/javascript">' .
                              '    Object.defineProperty(window, "' . $stack_name . '", {' .
                              '        writable: false,' .
                              '        value: ' . $service->toJson() .
                              '    });' .
                              '</script>';

                return "<?php echo '{$tag_text}'; ?>";
            });
        });
    }

    /**
     * Register package service.
     *
     * @return void
     */
    protected function registerService()
    {
        $this->app->singleton(BackendToFrontendVariablesInterface::class, BackendToFrontendVariablesStack::class);
    }

    /**https://github.com/avto-dev/app-version-laravel/blob/master/tests/BladeRenderTest.php
     * Gets config key name.
     *
     * @return string
     */
    public static function getConfigRootKeyName()
    {
        return basename(static::getConfigPath(), '.php');
    }

    /**
     * Get config file path.
     *
     * @return string
     */
    public static function getConfigPath()
    {
        return __DIR__ . '/config/back-to-front.php';
    }

    /**
     * Get assets path.
     *
     * @return string
     */
    public static function getAssetsDirPath()
    {
        return __DIR__ . '/assets';
    }

    /**
     * Register helpers file.
     *
     * @return void
     */
    public function registerHelpers()
    {
        require_once __DIR__ . '/helpers.php';
    }

    /**
     * Initialize configs.
     *
     * @return void
     */
    protected function initializeConfigs()
    {
        $this->mergeConfigFrom(static::getConfigPath(), static::getConfigRootKeyName());

        $this->publishes([
            \realpath(static::getConfigPath()) => config_path(\basename(static::getConfigPath())),
        ], 'config');
    }

    /**
     * Initialize assets.
     *
     * @return void
     */
    protected function initializeAssets()
    {
        $this->publishes([
            \realpath(static::getAssetsDirPath()) => public_path('vendor/back-to-front'),
        ], 'assets');
    }
}
