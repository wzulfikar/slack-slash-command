<?php 

namespace Wzulfikar\SlackSlashCommand;

interface SlashCommandInterface {
	
	/**
	 * 
	 * @param $payload object
	 */
	function __construct($payload);

	/**
	 * do the execution here
	 * @return mixed
	 */
	function exec();
}