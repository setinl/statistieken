<?php

require 'common.php';
require 'sql/sql.php';
require 'country.php';
require 'create_table.php';
require 'status.php';
require 'list_all_countries.php';
require 'list_snl_team.php';
require 'list_all_teams.php';
require 'list_users.php';


$min_rac = 200000;
$command = "SELECT ".SQL_TEAM_ID." FROM ".SQL_TABLE_TEAMS." WHERE ".SQL_RAC.">'$min_rac' LIMIT 1000";
echo $command;
die ();

LoggingOpen(LOCAL_LOGGING_FOLDER."test");

$sql = connectSqlSeti();

//listSnlTeam($sql);
//listUsers($sql);
//listAllTeams($sql);			
//listCountries($sql);

listSnlTeam($sql);

mysqli_close($sql);	

LoggingClose();	

echo "ready";

?>