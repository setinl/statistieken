<?php

function BackupSeti()
{
    $array = GetPassWordSqlReadWrite();
    $sql_password_rw = $array["sql_password_rw"];
    
    $DBUSER= "setiatnl";
    $DBPASSWD= $sql_password_rw;
    $DATABASE= "__seti";
    $DATABASES= "__seti_stats";
 
    set_time_limit(1200);
    
    $filename = "backup_seti-" . date("d-m-Y") . ".sql.gz";    
    $path =  BackupFolder($filename);     
    $cmd = "mysqldump --user=$DBUSER --password=$DBPASSWD $DATABASE | gzip --best >$path 2>$path.log";
//    echo $cmd;
    exec( $cmd,$ret_arr, $ret_code);  
    LoggingAdd("(BackupSeti) Finished: ". $DATABASE,TRUE);

    $filename = "backup_seti_stats-" . date("d-m-Y") . ".sql.gz";    
    $path =  BackupFolder($filename);    
    $cmd = "mysqldump --user=$DBUSER --password=$DBPASSWD $DATABASES | gzip --best >$path 2>$path.log";  
    exec( $cmd, $ret_arr, $ret_code );
    LoggingAdd("(BackupSeti) Finished: ". $DATABASES,TRUE);
}

?>