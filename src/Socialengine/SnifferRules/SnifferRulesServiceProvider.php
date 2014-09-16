<?php namespace Socialengine\SnifferRules;

use Illuminate\Support\ServiceProvider;
use Socialengine\SnifferRules\Command\SniffCommand;

class SnifferRulesServiceProvider extends ServiceProvider
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
        $this->package('socialengine/sniffer-rules');
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
}
