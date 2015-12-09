<?php 

namespace Wzulfikar\SlackSlashCommand;

use Exception;

class SlackSlashCommand {

	public $config;
	public $payload;

	private $executableClass;
	private $slashCommandInterfaceNamespace = 'Wzulfikar\SlackSlashCommand\SlackSlashCommandInterface';

	/**
	 * see: https://api.slack.com/slash-commands
	 * 
	 * @param array $payload
	 */
	public function __construct(array $payload, $executableClass = '')
	{
		// fetch config file
		$this->config  = (object) require (__DIR__.'/config/slack_slash_command.php');

		$this->payload = (object) $payload;
		$this->executableClass = $executableClass ?: $this->getExecutableClass();
		
	}

	public function exec(){		
  	try {
	  	$slashCommand = new $this->executableClass($this->payload);

	  	$this->verifyClassInterface($slashCommand);

	  	$slashCommand->exec();

  		echo "<br/>Done " . date('d-m-Y H:ia',time());

  	} catch (Exception $e) {

  		echo sprintf('[ERROR] %s. Called from %s:%s', $e->getMessage(), $e->getFile(), $e->getLine());

  	}
	}

	/**
	 * get name of slash command
	 * 
	 * @return string
	 */
	public function getName(){
		return str_replace('/', '', $this->payload->command);
	}

	/**
	 * return executable class (class that implements SlashCommandInterface)
	 * 
	 * @return string
	 */
	private function getExecutableClass(){
		$classPath = str_replace('/', '\\', $this->config->commands_dir) . '\Slash' . ucfirst($this->getName());
		$className = $this->executableClass ?: $classPath; 

		if(!class_exists($className))
			die("Oops! Cannot find `{$className}` :(");
		
		return $className;
	}

	/**
	 * verify that given executable class already impelement SlashCommandInterface
	 * 
	 * @param  object $class
	 * @return void
	 */
	private function verifyClassInterface($class){
		if(!in_array($this->slashCommandInterfaceNamespace, class_implements($this->executableClass)))
			throw new Exception("Class ".get_class($class)." doesn't implement SlashCommandInterface", 1);
	}
}