<?php

namespace Wzulfikar\SlackSlashCommand;

use Illuminate\Support\ServiceProvider;

class SlackSlashCommandServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {   
        // Publish a config file
        $this->publishes([
            __DIR__.'/config/slack_slash_command.php' => config_path('slack_slash_command.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        
        $this->registerCommands();
    }

    /**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->registerGeneratorCommand();
    }

    /**
     * Register the 'make:slack-slash-command' command.
     *
     * @return void
     */
    protected function registerGeneratorCommand()
    {
        $this->commands('command.generator');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [

            'command.make.generator'
        ];
    }
}
