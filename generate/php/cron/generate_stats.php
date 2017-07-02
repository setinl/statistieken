<?php

require_once '../passwords/pass.php';
require_once '../common.php';
require_once '../sql/sql.php';
require_once '../compress.php';	
require_once '../xml_parser.php';
require_once '../country.php';
require_once '../user_gz.php';
require_once '../team_gz.php';
require_once '../list_snl_team.php';
require_once '../list_all_teams.php';
require_once '../list_all_countries.php';
require_once '../list_users.php';
require_once '../stats_add.php';
require_once '../status.php';
require_once '../download_gz.php';
require_once '../create_table.php';
require_once '../generate_stats.php';

echo updateStats();

?>	
