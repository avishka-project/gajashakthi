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
$table = 'issues';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`id`', 'dt' => 'id', 'field' => 'id' ),
	array( 'db' => '`u`.`issuing`', 'dt' => 'issuing', 'field' => 'issuing' ),
	array( 'db' => '`u`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`u`.`issue_type`', 'dt' => 'issue_type', 'field' => 'issue_type' ),
	array( 'db' => '`u`.`payment_type`', 'dt' => 'payment_type', 'field' => 'payment_type' ),
	array( 'db' => '`u`.`remark`', 'dt' => 'remark', 'field' => 'remark' ),
    array( 'db' => '`ua`.`service_no`', 'dt' => 'service_no', 'field' => 'service_no' ),
    array( 'db' => '`ua`.`emp_name_with_initial`', 'dt' => 'emp_name_with_initial', 'field' => 'emp_name_with_initial' ),
	array( 'db' => '`cb`.`branch_name`', 'dt' => 'branch_name', 'field' => 'branch_name' ),
	array( 'db' => '`dp`.`name`', 'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`u`.`approve_01`', 'dt' => 'approve_01', 'field' => 'approve_01' ),
	array( 'db' => '`u`.`approve_02`', 'dt' => 'approve_02', 'field' => 'approve_02' ),
	array( 'db' => '`u`.`approve_03`', 'dt' => 'approve_03', 'field' => 'approve_03' ),
	array( 'db' => '`u`.`approve_status`', 'dt' => 'approve_status', 'field' => 'approve_status' ),
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

$joinQuery = "FROM `issues` AS `u` 
LEFT JOIN `employees` AS `ua` ON (`ua`.`id` = `u`.`employee_id`)
LEFT JOIN `customerbranches` AS `cb` ON (`cb`.`id` = `u`.`location_id`)
LEFT JOIN `departments` AS `dp` ON (`dp`.`id` = `u`.`department_id`)";

$extraWhere = "`u`.`status` IN (1, 2) AND `u`.`return_status`=0";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
