<?php

namespace {$commandsNamespace};

use Exception;
use Willdone\SlackSlashCommand\SlackSlashCommandInterface;

/**
 * This class will handle Slack Slash Command `/{$commandName}`.
 * Generated using `php artisan make:slack_slash_command {$commandName}`
 */
class {$className} implements SlackSlashCommandInterface {

	public $payload;

	/**
	 * Receive $payload from slack slash command.
	 * see: https://api.slack.com/slash-commands
	 * 
	 * @param [type] $payload [description]
	 */
	public function __construct($payload)
	{
		$this->payload = $payload;

		// Uncomment below code if you want to verify the token
		// $this->verifyPayloadToken( env('SLACK_SLASH_USERS_TOKEN', '1yWa8rKVGG2qkLwtri3QyOfY') );
	}

	/**
	 * Will be fired when `/{$commandName}` is executed
	 * 
	 * @return mixed
	 */
	public function exec(){

		// Implement your code here :)
		echo 'Hello world from ' . get_class($this);
	}

	private function verifyPayloadToken($token)
	{
		if($this->payload->token != $token)
			throw new Exception("Payload Token Mismatch for `{$this->payload->command}`", 1);
	}
}