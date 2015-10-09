<?php namespace SocialEngine\SnifferRules;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use SocialEngine\SnifferRules\Command\SniffCommand;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $app = $this->app;

        if (!$app->runningInConsole()) {
            return;
        }

        $source = realpath(__DIR__ . '/config/config.php');

        if (class_exists('Illuminate\Foundation\Application', false)) {
            // L5
            $this->publishes([$source => config_path('sniffer-rules.php')]);
            $this->mergeConfigFrom($source, 'sniffer-rules');
        } elseif (class_exists('Laravel\Lumen\Application', false)) {
            // Lumen
            $app->configure('sniffer-rules');
            $this->mergeConfigFrom($source, 'sniffer-rules');
        } else {
            // L4
            $this->package('socialengine/sniffer-rules', null, __DIR__);
        }
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('command.sniffer-rules', function ($app) {
            return new SniffCommand;
        });

        $this->commands('command.sniffer-rules');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['command.sniffer-rules.sniff'];
    }
}
