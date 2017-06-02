<?php

/*
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */


require_once  '../passwords/pass_read.php';
require_once( 'sql/sql_read_table.php' );


// DB table to use
$table = 'list_all_countries';

// Table's primary key
$primaryKey = 'country';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
   	array( 'db' => 'country', 'dt' => 0 ),
	array( 'db' => 'total_credit', 'dt' => 1 ),
	array( 'db' => 'rac', 'dt' => 2 ),
	array( 'db' => 'rank_rac', 'dt' => 3 ),
 	array( 'db' => 'rank_credit', 'dt' => 4 ),
   	array( 'db' => 'active', 'dt' => 5 ),	
);

// SQL server connection information
$sql_details = sqlTables();

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( 'ssp.class.php' );

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);


