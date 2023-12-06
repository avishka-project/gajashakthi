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
$table = 'fixed_assets';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`id`', 'dt' => 'id', 'field' => 'id' ),
	array( 'db' => '`ca`.`asset_category`', 'dt' => 'asset_category', 'field' => 'asset_category' ),
	array( 'db' => '`pa`.`name`', 'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`emp`.`service_no`', 'dt' => 'service_no', 'field' => 'service_no' ),
	array( 'db' => '`emp`.`emp_name_with_initial`', 'dt' => 'emp_name_with_initial', 'field' => 'emp_name_with_initial' ),
	array( 'db' => '`u`.`code`', 'dt' => 'code', 'field' => 'code' ),
	array( 'db' => '`u`.`region`', 'dt' => 'region', 'field' => 'region' ),
	array( 'db' => '`u`.`department`', 'dt' => 'department', 'field' => 'department' ),
	array( 'db' => '`u`.`clientbranch`', 'dt' => 'clientbranch', 'field' => 'clientbranch' ),
	array( 'db' => '`u`.`opening_value`', 'dt' => 'opening_value', 'field' => 'opening_value' ),
	array( 'db' => '`u`.`dateofpurchase`', 'dt' => 'dateofpurchase', 'field' => 'dateofpurchase' ),
	array( 'db' => '`u`.`rate`', 'dt' => 'rate', 'field' => 'rate' ),
	array( 'db' => '`u`.`addition_deletion`', 'dt' => 'addition_deletion', 'field' => 'addition_deletion' ),
	array( 'db' => '`u`.`closing_value`', 'dt' => 'closing_value', 'field' => 'closing_value' ),
	array( 'db' => '`u`.`acc_dep_2022`', 'dt' => 'acc_dep_2022', 'field' => 'acc_dep_2022' ),
	array( 'db' => '`u`.`dep_2023`', 'dt' => 'dep_2023', 'field' => 'dep_2023' ),
	array( 'db' => '`u`.`acc_dep_2023`', 'dt' => 'acc_dep_2023', 'field' => 'acc_dep_2023' ),
	array( 'db' => '`u`.`writtendown_2023`', 'dt' => 'writtendown_2023', 'field' => 'writtendown_2023' ),
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

$joinQuery = "FROM `fixed_assets` AS `u` 
LEFT JOIN `assetcategories` AS `ca` ON (`ca`.`id` = `u`.`asset_category_id`)
LEFT JOIN `assetparticulars` AS `pa` ON (`pa`.`id` = `u`.`particular_id`)
LEFT JOIN `employees` AS `emp` ON (`emp`.`id` = `u`.`employee_id`)";

$extraWhere = "`u`.`status` IN (1, 2)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
