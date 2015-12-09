<?php 
/**
 * Config file for slash command
 */
return [
	// route that handle incoming post request from slack slash command
  'route'					=> 'slack',

	// directory to store implementations of slack slash commands
	'commands_dir'  =>'App/SlackSlashCommands',
];