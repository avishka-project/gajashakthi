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
$table = 'empattendances';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`id`', 'dt' => 'id', 'field' => 'id'),
	array( 'db' => '`u`.`date`', 'dt' => 'date', 'field' => 'date'),
	array( 'db' => '`ub`.`branch_name`', 'dt' => 'branch_name', 'field' => 'branch_name'),
	array( 'db' => '`uc`.`name`', 'dt' => 'holidayname', 'field' => 'name' ),
	array( 'db' => '`u`.`emp_serviceno`', 'dt' => 'emp_serviceno', 'field' => 'emp_serviceno' ),
	array( 'db' => '`ud`.`emp_fullname`', 'dt' => 'emp_fullname', 'field' => 'emp_fullname' ),
	array( 'db' => '`ue`.`shift_name`', 'dt' => 'shift_name', 'field' => 'shift_name' )
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

$branch = $_POST['branch'];
$employee = $_POST['employee'];
$fromdate = $_POST['fromdate'];
$todate = $_POST['todate'];

$joinQuery = "FROM `empattendances` AS `u` 
            LEFT JOIN `customerbranches` AS `ub` ON (`ub`.`id` = `u`.`customerbranch_id`)
			LEFT JOIN `holiday_types` AS `uc` ON (`uc`.`id` = `u`.`holiday_id`)
			LEFT JOIN `employees` AS `ud` ON (`ud`.`id` = `u`.`emp_id`)
			LEFT JOIN `shift_types` AS `ue` ON (`ue`.`id` = `u`.`shift_id`)";
	
  $extraWhere = "`u`.`delete_status` = 0  AND `u`.`approve_status` = 0";

  if($branch != ''){
	$extraWhere = " `u`.`customerbranch_id` = '$branch'";
 }
 if ($employee != ''){
	$extraWhere = "`u`.`emp_id` = '$employee'";
 }


 if ($fromdate != '' && $todate != ''){
	$extraWhere = "`u`.date BETWEEN '$fromdate' AND '$todate'";
 }

 
echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
