<?php

namespace App\Libraries\SlackIncoming\Commands;

use Exception;
use App\Models\User;
use Maknz\Slack\Facades\Slack;
use App\Libraries\SlackIncoming\SlashCommandInterface;

/**
 * e.g: 
 * /users [username/name/email] [command] [value_for_command]
 */
class SlashUsers implements SlashCommandInterface {

	public $text;
	public $users;
	public $payload;
	public $command;
	public $identifier;

	public function __construct($payload)
	{
		$this->payload = $payload;

		$this->verifyPayloadToken( env('SLACK_SLASH_USERS_TOKEN', '1yWa8rKVGG2qkLwtri3QyOfY') );
	}

	public function exec(){
		$this->texts 		  = explode(' ', $this->payload->text);
		$this->identifier = array_shift($this->texts);
		$this->users  	  = $this->getUsers($this->identifier);
		$this->command    = $this->texts;

		if( !$this->users->count() )
			die('Sorry, no user is identifiable with '.$this->identifier);
		
		if( $this->users->count() == 1 ){
			$user = $this->users->first();

			echo $this->getSingleOutput($user->toArray());

			if($this->canExecCommand()){
				echo $this->execCommand($this->command) 
					? sprintf("\n".'*Done executing %s for `%s`*', implode(' ', $this->command), $this->identifier)
					: sprintf("\n".'*Failed executing %s for `%s`*', implode(' ', $this->command), $this->identifier);
			}

			die();
		}

		// users found is more than one..

		if( $this->command ){
			// allow command execution only if single user is found
			echo "Oops! Cannot execute {$this->command['0']}. We'll list all the users instead. ";
		}

		echo $this->getBatchOutput('User identifiable with ' . $this->identifier, 
																$this->users->toArray()
															);
		die();
	}

	private function canExecCommand(){
		// check if command is in list of executable commands
		return ! empty($this->command['0'])
				&& ! empty($this->command['1']);
	}

	private function execCommand(array $command){
		list($command, $value) = $command;

		if(strpos('set', $command) >= 0){
			$user 		 = $this->users->first();
			$attribute = str_replace('set:', '', $command);
			$user->$attribute = $value;
			return $user->save();
		}
	}

	private function getUsers($user){

		if($this->parseEmail($user))
			return User::where('email','LIKE','%'.$user.'%')->get();
		
		return User::where('name','LIKE','%'.$user.'%')
						 ->orWhere('username','LIKE',$user)
						 ->get();
	}

	private function parseEmail($email){
		return strpos('@', $email) >= 0;
	}

	private function verifyPayloadToken($token)
	{
		if($this->payload->token != $token)
			throw new Exception("Slack Slash Command `{$this->payload->command}` Payload Token Mismatch", 1);
	}

	private function getSingleOutput($data)
  {
    $temp = '';
    foreach ($data as $key => $value) {
      $temp .= sprintf('*%s*', $key);
      $temp .= "\n";
      $temp .= $value;
      $temp .= "\n";
    }
    return $temp;
  }

  public function getBatchOutput($title, $messages)
  {
    $output = '';
    foreach ($messages as $keys => $values) {
      $number = sprintf('%d of %d', ($keys + 1), sizeof($messages));
      $temp   = sprintf('`%s (%s)`%s', $title, $number, "\n");
      foreach ($values as $key => $value) {
        $temp .= sprintf('*%s*', $key);
        $temp .= "\n";
        $temp .= $value;
        $temp .= "\n";
      }
      $output .= $temp;
    }
    return $output;
  }
}