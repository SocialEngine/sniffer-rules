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

        if ($app::VERSION > '5.0') {
            $this->publishes([
                __DIR__ . '/config/config.php' => config_path('sniffer-rules.php'),
            ]);
        } else {
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
