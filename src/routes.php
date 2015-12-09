<?php 

Route::post('slack',function(){
  $slashCommand = new App\Libraries\SlackIncoming\SlackSlashCommand( Request::all() );
  $slashCommand->exec();
});