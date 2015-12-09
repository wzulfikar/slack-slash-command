<?php

namespace Wzulfikar\SlackSlashCommand;

use Illuminate\Console\Command;

class GeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:slack-slash-command {className?}';

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
        $command      = $this->argument('className') ?: $this->ask('What is the command name?');
        $commandName  = str_replace('/', '', $command);
        $commandsDir  = config('slack_slash_command.commands_dir');

        if(! $commandsDir ){
            $this->error("Cannot fetch commands dir from config file. Have you done `php artisan vendor:publish`?");
            exit();
        }

        if (!file_exists($commandsDir)) {
            mkdir( $commandsDir, 0777);
            $this->info("The directory $commandsDir was successfully created.");
        }

        $className    = sprintf('Slash%s',ucfirst($commandName));
        $classPath    = $commandsDir . '/' . $className . '.php';

        if (file_exists($classPath)) {
            if (! $this->confirm('File '.$classPath.' already exists. Do you wish to continue & overwrite current file? [y|N]')){
                $this->info('See ya ;)');
                exit();
            }
        }

        // prepare template
        $template = file_get_contents(__DIR__ . ('/SlackSlashCommandTemplate.php'));
        $template = str_replace('{$className}', $className, $template);
        $template = str_replace('{$commandName}', $commandName, $template);
        $template = str_replace('{$commandsNamespace}', str_replace('/', '\\', $commandsDir), $template);

        // create class definition from template
        file_put_contents($classPath, $template);

        $this->info(sprintf('Created class implementation of slack slash command:'));
        $this->info(sprintf('%s', $classPath));
    }
}
