<?php 

namespace Willdone\SlackSlashCommand;

use Exception;

class SlackSlashCommand {

	public $config;
	public $payload;

	private $executableClass;
	private $slashCommandInterfaceNamespace = 'Willdone\SlackSlashCommand\SlackSlashCommandInterface';

	/**
	 * see: https://api.slack.com/slash-commands
	 * 
	 * @param array $payload
	 */
	public function __construct(array $payload, $executableClass = '')
	{
		// fetch config file
		$this->config  = require (__DIR__.'/slack_slash_command.php');

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
		$className = $this->executableClass ?: $this->config['commandsNamespace'] . '\Slash' . ucfirst($this->getName());

		if(!class_exists($className))
			throw new Exception("Class {$className} does not exist", 1);
		
		return $className;
	}

	/**
	 * verify that given executable class already impelement SlashCommandInterface
	 * 
	 * @param  stdObj $class
	 * @return void
	 */
	private function verifyClassInterface($class){
		if(!in_array($this->slashCommandInterfaceNamespace, class_implements($this->executableClass)))
			throw new Exception("Class ".get_class($class)." doesn't implement SlashCommandInterface", 1);
	}
}