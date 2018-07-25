<?php

namespace Gegosoft\Qtum\Providers;

use Gegosoft\Qtum\Client as QtumClient;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__.'/../../config/config.php');

        $this->publishes([$path => config_path('qtumd.php')], 'config');
        $this->mergeConfigFrom($path, 'qtumd');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAliases();

        $this->registerClient();
    }

    /**
     * Register aliases.
     *
     * @return void
     */
    protected function registerAliases()
    {
        $aliases = [
            'qtumd' => 'Gegosoft\Qtum\Client',
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ((array) $aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Register client.
     *
     * @return void
     */
    protected function registerClient()
    {
        $this->app->singleton('qtumd', function ($app) {
            return new QtumClient([
                'scheme' => $app['config']->get('qtum.scheme', 'http'),
                'host'   => $app['config']->get('qtum.host', 'localhost'),
                'port'   => $app['config']->get('qtum.port', 8332),
                'user'   => $app['config']->get('qtum.user'),
                'pass'   => $app['config']->get('qtum.password'),
                'ca'     => $app['config']->get('qtum.ca'),
            ]);
        });
    }
}
