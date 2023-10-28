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
$table = 'employees';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`id`', 'dt' => 'id', 'field' => 'id' ),
	array( 'db' => '`u`.`emp_id`', 'dt' => 'emp_id', 'field' => 'emp_id' ),
	array( 'db' => '`u`.`emp_name_with_initial`', 'dt' => 'emp_name_with_initial', 'field' => 'emp_name_with_initial' ),
	array( 'db' => '`u`.`service_no`', 'dt' => 'service_no', 'field' => 'service_no' ),
	array( 'db' => '`u`.`emp_status`', 'dt' => 'emp_status', 'field' => 'emp_status' ),
	array( 'db' => '`u`.`emp_join_date`', 'dt' => 'emp_join_date', 'field' => 'emp_join_date' ),
	array( 'db' => '`ua`.`emp_status`', 'dt' => 'emp_status', 'field' => 'emp_status' ),
	array( 'db' => '`uc`.`location`', 'dt' => 'location', 'field' => 'location' ),
	array( 'db' => '`ub`.`title`', 'dt' => 'title', 'field' => 'title' ),
	array( 'db' => '`ue`.`name`', 'dt' => 'dep_name', 'field' => 'name' )
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

$department = $_POST['department'];
$employee = $_POST['employee'];
// $location = $_POST['location'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];

$joinQuery = "FROM `employees` AS `u` LEFT JOIN `employment_statuses` AS `ua` ON (`ua`.`id` = `u`.`emp_status`) 
            LEFT JOIN `job_titles` AS `ub` ON (`ub`.`id` = `u`.`emp_job_code`)
			LEFT JOIN `branches` AS `uc` ON (`uc`.`id` = `u`.`emp_location`)
			LEFT JOIN `departments` AS `ue` ON (`ue`.`id` = `u`.`emp_department`)";
	
$extraWhere = "`u`.`deleted` = 0 AND `u`.`emp_category` = 2";

 if($department != ''){
	$extraWhere = " `ue`.`id` = '$department' AND `u`.`emp_category` = 2 AND `u`.`deleted` = 0";
 }
 if ($employee != ''){
	$extraWhere = "`u`.`id` = '$employee' AND `u`.`emp_category` = 2 AND `u`.`deleted` = 0";
 }

//  if ($location != ''){
// 	$extraWhere = "`u`.`emp_location` = '$location'";
//  }

 if ($from_date != '' && $to_date != ''){
	$extraWhere = "`u`.emp_join_date BETWEEN '$from_date' AND '$to_date' AND `u`.`emp_category` = 2 AND `u`.`deleted` = 0";
 }

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
