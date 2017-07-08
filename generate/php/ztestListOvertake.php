<?php
require_once 'passwords/pass.php';
require_once 'common.php';
require_once 'sql/sql.php';
require_once 'list_snl_team.php';


    LoggingOpen("testlists");

    $sql = connectSqlSeti();
    
    $table_name = SQL_TABLE_LIST_SNL_TEAM;
    $status = listSnlTeamOvertake($sql, $table_name);
    if ($status == true)
    {
        echo "<br>OK";
    }
    else
    {
        echo "<br>ERROR";
    }
    
    mysqli_close($sql);
    LoggingClose()  
?>