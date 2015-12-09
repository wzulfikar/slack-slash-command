<?php

namespace Wzulfikar\SlackSlashCommand;

use Illuminate\Console\Command;

class GenerateSlackSlashCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:slack_slash_command {className?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate class implementation of SlackSlashCommandInterface.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // prepare class name from given command
        $command     = $this->argument('className') ?: $this->ask('What is the command name?');
        $commandName = str_replace('/', '', $command);
        $className   = sprintf('Slash%s',ucfirst($commandName));
        $classPath   = app_path('Libraries/SlackIncoming/Commands/' . $className . '.php');

        // prepare template
        $template = file_get_contents(app_path('Libraries/SlackIncoming/SlackSlashCommandTemplate.php'));
        $template = str_replace('{$className}', $className, $template);
        $template = str_replace('{$commandName}', $commandName, $template);

        // create class definition from template
        file_put_contents($classPath, $template);

        $this->info(sprintf('Created class implementation of slack slash command:'));
        $this->info(sprintf('%s', $classPath));
    }
}
