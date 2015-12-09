<?php 
/**
 * Config file for slash command
 */
return [
	'token'=>env('SLACK_SLASH_COMMAND_TOKEN'),
	'commandsNamespace'=>'Willdone\SlackSlashCommand\Commands'
];