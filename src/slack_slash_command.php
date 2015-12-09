<?php 
/**
 * Config file for slash command
 */
return [
	'token'=>env('SLACK_SLASH_COMMAND_TOKEN'),
	'commandsNamespace'=>'App\Libraries\SlackIncoming\Commands'
];