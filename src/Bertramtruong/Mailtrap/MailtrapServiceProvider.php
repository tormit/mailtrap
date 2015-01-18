<?php namespace Bertramtruong\Mailtrap;

use Illuminate\Support\ServiceProvider;

class MailtrapServiceProvider extends ServiceProvider
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
        $this->package('bertramtruong/mailtrap');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfiguration();
    }

    public function registerConfiguration()
    {
        // Is it possible to register the config?
        if (method_exists($this->app['config'], 'package')) {
            $this->app['config']->package('bertramtruong/mailtrap', __DIR__ . '/config');
        } else {
            // Load the config for now..
            $config = $this->app['files']->getRequire(__DIR__ . '/config/config.php');
            $this->app['config']->set('mailtrap::config', $config);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('mailtrap');
    }

}
