<?php namespace SocialEngine\SnifferRules\Support;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Service provider that registers SniffCommand into Laravel application
 *
 */
class ServiceProvider extends BaseServiceProvider
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
        $this->package('SocialEngine/sniffer-rules');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('command.sniffer-rules', function ($app) {
            return new \SocialEngine\SnifferRules\Command\SniffCommand;
        });

        $this->commands('command.sniffer-rules');
    }
}
