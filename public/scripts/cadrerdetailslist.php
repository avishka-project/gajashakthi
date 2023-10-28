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
	array( 'db' => '`sub`.`subregion`', 'dt' => 'subregion', 'field' => 'subregion' ),
	array( 'db' => '`jobtitile`.`title`', 'dt' => 'title', 'field' => 'title' ),
	array( 'db' => '`cusrequestdetails`.`count`', 'dt' => 'count', 'field' => 'count' ),
	array( 'db' => '`cusrequestdetails`.`shift_id`', 'dt' => 'shift_id', 'field' => 'shift_id' ),
	array( 'db' => '`cusrequestdetails`.`holiday_id`', 'dt' => 'holiday_id', 'field' => 'holiday_id' ),
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
LEFT JOIN `customers` AS `cus` ON (`cus`.`id` = `cusrequest`.`customer_id`) 
LEFT JOIN `customerbranches` AS `cusbranch` ON (`cusbranch`.`id` = `cusrequest`.`customerbranch_id`) 
LEFT JOIN `customerrequestdetails` AS `cusrequestdetails` ON (`cusrequest`.`id` = `cusrequestdetails`.`customerrequest_id`)
LEFT JOIN `job_titles` AS `jobtitile` ON (`jobtitile`.`id` = `cusrequestdetails`.`job_title_id`)
LEFT JOIN (SELECT * FROM `subregions` WHERE `status` IN (1, 2)) AS `sub` ON (`sub`.`id` = `cusbranch`.`subregion_id`)  ";

$extraWhere = "`cusrequest`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);