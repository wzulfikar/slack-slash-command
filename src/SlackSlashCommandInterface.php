<?php 

namespace Wzulfikar\SlackSlashCommand;

interface SlackSlashCommandInterface {
	
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