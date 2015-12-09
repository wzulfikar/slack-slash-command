<?php 

use Willdone\SlackSlashCommand\SlackSlashCommand; 

Route::post('slack',function(){
  $slashCommand = new SlackSlashCommand( Request::all() );
  $slashCommand->exec();
});