<?php 

use Wzulfikar\SlackSlashCommand\SlackSlashCommand; 

Route::post(config('slack_slash_command.route'),function(){
  $slashCommand = new SlackSlashCommand( Request::all() );
  $slashCommand->exec();
});