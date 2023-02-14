<?php

declare(strict_types = 1);

namespace AvtoDev\Back2Front;

use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->initializeConfigs();
        $this->initializeAssets();
        $this->registerHelpers();
        $this->registerService();
        $this->registerBlade();
    }

    /**
     * Get config file path.
     *
     * @return string
     */
    public static function getConfigPath(): string
    {
        return __DIR__ . '/../config/back-to-front.php';
    }

    /**
     * Gets config key name.
     *
     * @return string
     */
    public static function getConfigRootKeyName(): string
    {
        return \basename(static::getConfigPath(), '.php');
    }

    /**
     * Get assets path.
     *
     * @return string
     */
    public static function getAssetsDirPath(): string
    {
        return __DIR__ . '/../assets';
    }

    /**
     * Register helpers file.
     *
     * @return void
     */
    public function registerHelpers(): void
    {
        require_once __DIR__ . '/../helpers/helpers.php';
    }

    /**
     * Initialize configs.
     *
     * @return void
     */
    protected function initializeConfigs(): void
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
    protected function initializeAssets(): void
    {
        $this->publishes([
            \realpath(static::getAssetsDirPath()) => public_path('vendor/back-to-front'),
        ], 'assets');
    }

    /**
     * Register package service.
     *
     * @return void
     */
    protected function registerService(): void
    {
        $this->app->singleton(Back2FrontInterface::class, Back2FrontStack::class);
    }

    /**
     * Register Blade directives.
     *
     * @return void
     */
    protected function registerBlade(): void
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $blade): void {
            $blade->directive('back_to_front_data', function ($stack_name = null): string {
                /** @var ConfigRepository $config */
                $config = $this->app->make(ConfigRepository::class);

                /** @var string $stack_name */
                $stack_name = (\is_string($stack_name) && $stack_name !== '')
                    ? $stack_name
                    : $config->get(static::getConfigRootKeyName() . '.stack_name');

                $stack_name = Str::slug($stack_name);

                return \sprintf(
                    '<?php echo \'<script type="text/javascript">
                                Object.defineProperty(
                                    window, "%s",
                                    {
                                        writable: false,
                                        value:  \', \Illuminate\Container\Container::getInstance()->make(\'%s\')->toJson() , \'
                                    }
                                );
                            </script>\'; ?>',
                    $stack_name,
                    Back2FrontInterface::class
                );
            });
        });
    }
}
