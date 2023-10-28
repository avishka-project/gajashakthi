<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'customerrequests';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`cusrequest`.`id`', 'dt' => 'id', 'field' => 'id' ),
	array( 'db' => '`cusrequest`.`fromdate`', 'dt' => 'fromdate', 'field' => 'fromdate' ),
	array( 'db' => '`cusrequest`.`todate`', 'dt' => 'todate', 'field' => 'todate' ),
	array( 'db' => '`cus`.`name`', 'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`cusbranch`.`branch_name`', 'dt' => 'branch_name', 'field' => 'branch_name' ),
	array( 'db' => '`cusrequest`.`requeststatus`', 'dt' => 'requeststatus', 'field' => 'requeststatus' ),
	array( 'db' => '`cusrequest`.`approve_01`', 'dt' => 'approve_01', 'field' => 'approve_01' ),
	array( 'db' => '`cusrequest`.`approve_02`', 'dt' => 'approve_02', 'field' => 'approve_02' ),
	array( 'db' => '`cusrequest`.`approve_03`', 'dt' => 'approve_03', 'field' => 'approve_03' ),
	array( 'db' => '`cusrequest`.`approve_status`', 'dt' => 'approve_status', 'field' => 'approve_status' ),
	array( 'db' => '`cusrequest`.`status`', 'dt' => 'status', 'field' => 'status' ),
);

// SQL server connection information
require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('ssp.customized.class.php' );

$joinQuery = "FROM `customerrequests` AS `cusrequest` 
JOIN `customers` AS `cus` ON (`cus`.`id` = `cusrequest`.`customer_id`) 
JOIN `customerbranches` AS `cusbranch` ON (`cusbranch`.`id` = `cusrequest`.`customerbranch_id`) 
 ";

$extraWhere = "`cusrequest`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);