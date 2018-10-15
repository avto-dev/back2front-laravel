<?php

declare(strict_types = 1);

namespace AvtoDev\BackendToFrontendVariablesStack;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
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
     * Register Blade directives.
     *
     * @return void
     */
    protected function registerBlade()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $blade) {
            $blade->directive('back_to_front_data', function ($stack_name = null) {
                /** @var ConfigRepository $config */
                $config = $this->app->make(ConfigRepository::class);

                $stack_name = Str::slug((\is_string($stack_name) && $stack_name !== '')
                    ? $stack_name
                    : $config->get(static::getConfigRootKeyName() . '.stack_name'));

                return \sprintf(
                    '<?php echo \'<script type="text/javascript">
                                Object.defineProperty(
                                    window, "%s", 
                                    {
                                        writable: false, 
                                        value:  \', resolve( \'%s\' )->toJson() , \' 
                                    }
                                );
                            </script>\'; ?>',
                    $stack_name,
                    BackendToFrontendVariablesInterface::class
                );
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
