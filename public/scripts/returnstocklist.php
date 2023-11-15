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
$table = 'return_stocks';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`id`', 'dt' => 'id', 'field' => 'id' ),
	array( 'db' => '`u`.`quality_percentage`', 'dt' => 'quality_percentage', 'field' => 'quality_percentage' ),
	array( 'db' => '`ua`.`inventorylist_id`', 'dt' => 'inventorylist_id', 'field' => 'inventorylist_id' ),
	array( 'db' => '`ua`.`name`', 'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`ua`.`uniform_size`', 'dt' => 'uniform_size', 'field' => 'uniform_size' ),
	array( 'db' => '`u`.`qty`', 'dt' => 'qty', 'field' => 'qty' ),
	array( 'db' => '`u`.`unit_price`', 'dt' => 'unit_price', 'field' => 'unit_price' ),
	array( 'db' => '`st`.`storename`', 'dt' => 'storename', 'field' => 'storename' ),
	array( 'db' => '`u`.`status`', 'dt' => 'status', 'field' => 'status' )
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

$store = $_POST['store'];

$joinQuery = "FROM `return_stocks` AS `u` 
LEFT JOIN `inventorylists` AS `ua` ON (`ua`.`id` = `u`.`item_id`)
LEFT JOIN (SELECT `id`,name AS `storename` FROM `storelists`) AS `st` ON (`st`.`id` = `u`.`store_id`)";

$extraWhere = "`u`.`status` IN (1, 2)";

if($store != ''){
	$extraWhere = " `u`.`store_id` = '$store' AND `u`.`status` IN (1, 2)";
 }

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
