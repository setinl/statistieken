<?php
require '../../generate/php/common.php';

function BackupSeti()
{
    $DBUSER="setiatnl";
    $DBPASSWD="23gbowMLMvTGOkypDSWlStl8vevRn1";
    $DATABASE="__seti";
    $DATABASES="__seti_stats";
 
    set_time_limit(1200);
    
    $filename = "backup_seti-" . date("d-m-Y") . ".sql.gz";    
    $path =  BackupFolder($filename);     
    $cmd = "mysqldump --verbose --user=$DBUSER --password=$DBPASSWD $DATABASE | gzip --best >$path 2>$path.log";
    exec( $cmd, $ret_arr, $ret_code );  
    echo "ret_arr: <br />";
    print_r($ret_arr);
    echo '<br>';
    echo 'Backup: ' . $cmd. '<br>';
    $filename = "backup_seti_stats-" . date("d-m-Y") . ".sql.gz";    
    $path =  BackupFolder($filename);    
    $cmd = "mysqldump --verbose --user=$DBUSER --password=$DBPASSWD $DATABASES | gzip --best >$path 2>$path.log";
    exec( $cmd, $ret_arr, $ret_code );
    echo "ret_arr: <br />";
    print_r($ret_arr);
    echo '<br>';    
    echo 'Backup: ' . $cmd. '<br>';    
}

BackupSeti();

?>