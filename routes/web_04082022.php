<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});


Auth::routes();




/* User Role Permission*/

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('roles','RoleController');
Route::resource('permissions','PermissionController');
Route::resource('users','UserController');
Route::resource('permission','PermissionController');
Route::resource('rolepermission','RolePermissionController');
Route::resource('userrole','RoleUserController');
Route::resource('userpermission','UserPermissionController');

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('FingerprintDevice', 'FingerprintDeviceController');
//Route::post('addFingerprintDevice',['uses' => 'FingerprintDeviceController@store', 'as' => 'addFingerprintDevice']); 

Route::get('EmloyeeList',['uses' => 'EmployeeController@employeelist', 'as' => 'EmloyeeList']); 
Route::get('addEmployee',['uses' => 'EmployeeController@index', 'as' => 'addEmployee']);
Route::get('employee_list_dt',['uses' => 'EmployeeController@employee_list_dt', 'as' => 'employee_list_dt']);
Route::post('empoyeeUpdate',['uses' => 'EmployeeController@edit', 'as' => 'empoyeeUpdate']); 
Route::post('empoyeeRegister',['uses' => 'EmployeeController@store', 'as' => 'empoyeeRegister']); 
Route::post('addUserLogin',['uses' => 'EmployeeController@usercreate', 'as' => 'addUserLogin']); 
Route::get('EmployeeDestroy/destroy/{id}', 'EmployeeController@destroy');
Route::get('exportEmpoloyee', 'EmployeeController@exportempoloyee')->name('exportEmpoloyee');
Route::get('/viewEmployee/{id}',['uses' => 'EmployeeController@show', 'as' => 'viewEmployee']);
Route::get('/contactDetails/{id}',['uses' => 'EmployeeController@showcontact', 'as' => 'contactDetails']);
Route::post('contactUpdate',['uses' => 'EmployeeController@editcontact', 'as' => 'contactUpdate']);
Route::get('/viewEmergencyContacts/{id}',['uses' => 'EmployeeController@showcontact', 'as' => 'viewEmergencyContacts']);

Route::post('empoyeeAttachment',['uses' => 'EmployeeAttachmentController@create', 'as' => 'empoyeeAttachment']);
Route::post('contactAttachment',['uses' => 'EmployeeAttachmentController@createcontact', 'as' => 'contactAttachment']);
Route::post('qulificationAttachment',['uses' => 'EmployeeAttachmentController@createqulification', 'as' => 'qulificationAttachment']);
Route::post('dependetAttachment',['uses' => 'EmployeeAttachmentController@createdependent', 'as' => 'dependetAttachment']);
Route::post('immigrationAttachment',['uses' => 'EmployeeImmigrationController@immigrationattacment', 'as' => 'immigrationAttachment']);


Route::get('/viewEmergencyContacts', function () {
    return view('Employee.viewEmergencyContacts');
});

Route::get('getDependentDetail/{id}',['uses' => 'EmployeeDependentController@edit_json', 'as' => 'getDependentDetail']);
Route::post('dependentUpdate',['uses' => 'EmployeeDependentController@update', 'as' => 'dependentUpdate']);
Route::get('dependent_delete/{id}',['uses' => 'EmployeeDependentController@destroy', 'as' => 'dependent_delete']);
Route::get('/viewDependents/{id}',['uses' => 'EmployeeDependentController@show', 'as' => 'viewDependents']);
Route::post('dependentInsert',['uses' => 'EmployeeDependentController@create', 'as' => 'dependentInsert']);
Route::get('dependentUpdate/{id}',['uses' => 'EmployeeDependentController@edit', 'as' => 'dependentUpdate']);

Route::get('/viewEmergencyContacts/{id}',['uses' => 'EmployeeEmergencyContacts@show', 'as' => 'viewEmergencyContacts']);
Route::post('emergencyContactInsert',['uses' => 'EmployeeEmergencyContacts@create', 'as' => 'emergencyContactInsert']);
Route::get('getEmergencyContactDetail/{id}',['uses' => 'EmployeeEmergencyContacts@edit_json', 'as' => 'getEmergencyContactDetail']);
Route::post('emergencyContactUpdate',['uses' => 'EmployeeEmergencyContacts@update', 'as' => 'emergencyContactUpdate']);
Route::get('emergency_contact_delete/{id}',['uses' => 'EmployeeEmergencyContacts@destroy', 'as' => 'emergency_contact_delete']);



Route::get('/viewImmigration/{id}',['uses' => 'EmployeeImmigrationController@show', 'as' => 'viewImmigration']);
Route::post('immigrationInsert',['uses' => 'EmployeeImmigrationController@create', 'as' => 'immigrationInsert']); 


Route::get('/viewJobDetails/{id}',['uses' => 'EmployeeImmigrationController@show', 'as' => 'viewJobDetails']);


Route::get('/viewJobDetails', function () {
    return view('Employee.viewJobDetails');
});


Route::get('/viewSalaryDetails/{id}',['uses' => 'EmployeeSalaryController@show', 'as' => 'viewSalaryDetails']);


Route::get('/viewQualifications/{id}',['uses' => 'EmployeeEducationController@show', 'as' => 'viewQualifications']);
Route::get('/viewbankDetails/{id}',['uses' => 'EmployeeBankController@show', 'as' => 'viewbankDetails']);
Route::post('BankInsert',['uses' => 'EmployeeBankController@store', 'as' => 'BankInsert']);
Route::get('empBank/destroy/{id}', 'EmployeeBankController@destroy');
Route::get('empBankReport',['uses' => 'EmployeeBankController@empBankReport', 'as' => 'empBankReport']);
Route::get('bank_report_list',['uses' => 'EmployeeBankController@bank_report_list', 'as' => 'bank_report_list']);



Route::get('/viewPassport/{id}',['uses' => 'EmployeePassportController@show', 'as' => 'viewPassport']);
Route::post('passportInsert',['uses' => 'EmployeePassportController@store', 'as' => 'passportInsert']); 
Route::post('passportAttachment',['uses' => 'EmployeePassportController@passportattacment', 'as' => 'passportAttachment']);
Route::get('passportEdit/{emp_pass_id}',['uses' => 'EmployeePassportController@Edit', 'as' => 'passportEdit']);
Route::get('passportDestroy/{emp_pass_id}',['uses' => 'EmployeePassportController@Destroy', 'as' => 'passportDestroy']);
Route::post('passportUpdate',['uses' => 'EmployeePassportController@Update', 'as' => 'passportUpdate']);





/*-- Jobs Title----*/
Route::resource('WorkExprience', 'EmployeeExperienceController');
Route::post('WorkExprienceInsert',['uses' => 'EmployeeExperienceController@create', 'as' => 'WorkExprienceInsert']); 
Route::post('WorkExprience/update', 'EmployeeExperienceController@update')->name('WorkExprience.update');
Route::get('WorkExprience/destroy/{id}', 'EmployeeExperienceController@destroy');
/*-- End Jobs Title----*/

/*-- EmployeeSkill----*/
Route::resource('EmployeeSkill', 'EmployeeSkillController');
Route::post('skillInsert',['uses' => 'EmployeeSkillController@create', 'as' => 'skillInsert']);
Route::post('EmployeeSkill/update', 'EmployeeSkillController@update')->name('EmployeeSkill.update');
Route::get('EmployeeSkill/destroy/{id}', 'EmployeeSkillController@destroy');
/*-- End EmployeeSkill----*/

/*-- Education----*/
Route::resource('EmployeeEducation', 'EmployeeEducationController');
Route::post('educationInsert',['uses' => 'EmployeeEducationController@create', 'as' => 'educationInsert']); 
Route::post('EmployeeEducation/update', 'EmployeeEducationController@update')->name('EmployeeEducation.update');
Route::get('EmployeeEducation/destroy/{id}', 'EmployeeEducationController@destroy');
/*-- End Education----*/



/*-- Jobs Title----*/
Route::resource('JobTitle', 'JobTitleController');
Route::get('JobTitle',['uses' => 'JobTitleController@index', 'as' => 'JobTitle']); 
Route::post('addJobTitle',['uses' => 'JobTitleController@store', 'as' => 'addJobTitle']); 
Route::post('JobTitle/update', 'JobTitleController@update')->name('JobTitle.update');
Route::get('JobTitle/destroy/{id}', 'JobTitleController@destroy');
/*-- End Jobs Title----*/

/*-- Pay Grade Title----*/
Route::resource('PayGrade', 'PayGradeController');
Route::get('PayGrade',['uses' => 'PayGradeController@index', 'as' => 'PayGrade']); 
Route::post('addPayGrade',['uses' => 'PayGradeController@store', 'as' => 'addPayGrade']); 
Route::post('PayGrade/update', 'PayGradeController@update')->name('PayGrade.update');
Route::get('PayGrade/destroy/{id}', 'PayGradeController@destroy');
/*-- Pay Grade Title----*/

/*-- Employment Status----*/
Route::resource('EmploymentStatus', 'EmploymentStatusController');
Route::get('EmploymentStatus',['uses' => 'EmploymentStatusController@index', 'as' => 'EmploymentStatus']); 
Route::post('addEmploymentStatus',['uses' => 'EmploymentStatusController@store', 'as' => 'addEmploymentStatus']); 
Route::post('EmploymentStatus/update', 'EmploymentStatusController@update')->name('EmploymentStatus.update');
Route::get('EmploymentStatus/destroy/{id}', 'EmploymentStatusController@destroy');

/*-- Employment Status----*/

/*--  Job Category----*/
Route::resource('JobCategory', 'JobCategoryController');
Route::get('JobCategory',['uses' => 'JobCategoryController@index', 'as' => 'JobCategory']); 
Route::post('addJobCategory',['uses' => 'JobCategoryController@store', 'as' => 'addJobCategory']); 
Route::post('JobCategory/update', 'JobCategoryController@update')->name('JobCategory.update');
Route::get('JobCategory/destroy/{id}', 'JobCategoryController@destroy');
/*-- Job Category----*/


/*-- End Jobs----*/


/*-- Start Qulification----*/
/*-- Skills----*/
Route::resource('Skill', 'SkillController');
Route::get('Skill',['uses' => 'SkillController@index', 'as' => 'Skill']); 
Route::post('addSkill',['uses' => 'SkillController@store', 'as' => 'addSkill']); 
Route::post('Skill/update', 'SkillController@update')->name('Skill.update');
Route::get('Skill/destroy/{id}', 'SkillController@destroy');
/*-- Skills----*/

/*-- Education----*/
Route::resource('Education', 'EducationController');
Route::get('Education',['uses' => 'EducationController@index', 'as' => 'Education']); 
Route::post('addEducation',['uses' => 'EducationController@store', 'as' => 'addEducation']); 
Route::post('Education/update', 'EducationController@update')->name('Education.update');
//Route::post('updateEducation', 'EducationController@update')->name('updateEducation');
Route::get('Education/destroy/{id}', 'EducationController@destroy');
/*-- Education----*/
/*-- End Qulification----*/



/*-- Jobs Title----*/
Route::resource('Branch', 'BranchController');
Route::get('Branch',['uses' => 'BranchController@index', 'as' => 'Branch']); 
Route::post('addBranch',['uses' => 'BranchController@store', 'as' => 'addBranch']); 
Route::post('Branch/update', 'BranchController@update')->name('Branch.update');
Route::get('Branch/destroy/{id}', 'BranchController@destroy');
/*-- End Jobs Title----*/


Route::resource('Attendance', 'AttendanceController');
Route::get('Attendance',['uses' => 'AttendanceController@index', 'as' => 'Attendance']);
Route::get('late_attendance_by_time',['uses' => 'AttendanceController@late_attendance_by_time', 'as' => 'late_attendance_by_time']);
Route::get('late_attendance_by_time_approve',['uses' => 'AttendanceController@late_attendance_by_time_approve', 'as' => 'late_attendance_by_time_approve']);
Route::get('late_attendances_all',['uses' => 'AttendanceController@late_attendances_all', 'as' => 'late_attendances_all']);
Route::get('late_attendance_list_approved',['uses' => 'AttendanceController@late_attendance_list_approved', 'as' => 'late_attendance_list_approved']);

Route::get('late_attendance/destroy/{id}', 'AttendanceController@destroy_late_attendacne');

//Attendance.delete post
Route::post('Attendance.delete', 'AttendanceController@delete')->name('Attendance.delete');


//incomplete_attendances
Route::get('incomplete_attendances',['uses' => 'AttendanceController@incomplete_attendances', 'as' => 'incomplete_attendances']);

Route::get('attendance_by_time_report_list',['uses' => 'AttendanceController@attendance_by_time_report_list', 'as' => 'attendance_by_time_report_list']);
Route::post('lateAttendance_mark_as_late',['uses' => 'AttendanceController@lateAttendance_mark_as_late', 'as' => 'lateAttendance_mark_as_late']);

Route::get('attendance_by_time_approve_report_list',['uses' => 'AttendanceController@attendance_by_time_approve_report_list', 'as' => 'attendance_by_time_approve_report_list']);
Route::post('lateAttendance_mark_as_late_approve',['uses' => 'AttendanceController@lateAttendance_mark_as_late_approve', 'as' => 'lateAttendance_mark_as_late_approve']);

Route::get('late_types_sel2',['uses' => 'AttendanceController@late_types_sel2', 'as' => 'late_types_sel2']);
Route::get('AttendanceEdit',['uses' => 'AttendanceController@attendanceedit', 'as' => 'AttendanceEdit']);
Route::get('attendance_list_for_edit',['uses' => 'AttendanceController@attendance_list_for_edit', 'as' => 'attendance_list_for_edit']);

Route::post('attendance_add_bulk_submit',['uses' => 'AttendanceController@attendance_add_bulk_submit', 'as' => 'attendance_add_bulk_submit']);
Route::post('attendance_add_dept_wise_submit',['uses' => 'AttendanceController@attendance_add_dept_wise_submit', 'as' => 'attendance_add_dept_wise_submit']);
//post get_attendance_monthly_summery_by_emp_id
Route::post('get_attendance_monthly_summery_by_emp_id',['uses' => 'AttendanceController@get_attendance_monthly_summery_by_emp_id', 'as' => 'get_attendance_monthly_summery_by_emp_id']);


Route::get('AttendanceEditBulk',['uses' => 'AttendanceController@AttendanceEditBulk', 'as' => 'AttendanceEditBulk']);
Route::get('AttendanceApprovel',['uses' => 'AttendanceController@attendanceapprovel', 'as' => 'AttendanceApprovel']);
Route::get('attendance_list_for_approve',['uses' => 'AttendanceController@attendance_list_for_approve', 'as' => 'attendance_list_for_approve']);
Route::post('AttendentAprovelBatch', 'AttendanceController@AttendentAprovelBatch')->name('AttendentAprovelBatch');

Route::get('attendance_list_for_bulk_edit',['uses' => 'AttendanceController@attendance_list_for_bulk_edit', 'as' => 'attendance_list_for_bulk_edit']);
Route::post('AttendanceEditBulkSubmit',['uses' => 'AttendanceController@AttendanceEditBulkSubmit', 'as' => 'AttendanceEditBulkSubmit']);
Route::post('attendance_list_for_month_edit',['uses' => 'AttendanceController@attendance_list_for_month_edit', 'as' => 'attendance_list_for_month_edit']);
Route::post('attendance_update_bulk_submit',['uses' => 'AttendanceController@attendance_update_bulk_submit', 'as' => 'attendance_update_bulk_submit']);


Route::get('/AttendentUpdate', 'AttendanceController@getAttendance');
Route::get('/AttendentView', 'AttendanceController@getAttendance');
Route::get('/getAttendanceApprovel', 'AttendanceController@getAttendanceApprovel');
Route::post('AttendentAprovel', 'AttendanceController@AttendentAprovel')->name('AttendentAprovel');

Route::post('/AttendentUpdateLive', 'AttendanceController@attendentUpdateLive');
Route::post('/AttendentInsertLive', 'AttendanceController@attendentinsertlive');
Route::post('/AttendentDeleteLive', 'AttendanceController@attendentdeletelive');
Route::get('/getAttendentChart', 'AttendanceController@getAttendentChart');
Route::get('/getBranchAttendentChart', 'AttendanceController@getBranchAttendentChart');
Route::get('/Attendentdetails/{id}',['uses' => 'AttendanceController@attendentdetails', 'as' => 'Attendentdetails']);

//get_incomplete_attendance_by_employee_data
Route::post('get_incomplete_attendance_by_employee_data', 'AttendanceController@get_incomplete_attendance_by_employee_data')->name('get_incomplete_attendance_by_employee_data');

//mark_as_no_pay
Route::post('mark_as_no_pay', 'AttendanceController@mark_as_no_pay')->name('mark_as_no_pay');



Route::post('Attendance/update', 'AttendanceController@update')->name('Attendance.update');
Route::get('Attendance/destroy/{id}', 'AttendanceController@destroy');
//Route::post('Attendance/getdevicedata', 'AttendanceController@getdevicedata');
Route::post('/Attendance/getdevicedata', 'AttendanceController@getdevicedata')->name('Attendance.getdevicedata');
Route::get('exportAttendance', 'AttendanceController@exportattendances')->name('exportAttendance');  


/*-- Attendent Type----*/
Route::resource('AttendanceType', 'AttendanceTypeController');
Route::get('AttendanceType',['uses' => 'AttendanceTypeController@index', 'as' => 'AttendanceType']); 
Route::post('addAttendanceType',['uses' => 'AttendanceTypeController@store', 'as' => 'addAttendanceType']); 
Route::post('AttendanceType/update', 'AttendanceTypeController@update')->name('AttendanceType.update');
Route::get('job/destroy/{id}', 'AttendanceTypeController@destroy');
/*-- End Attendent Type----*/


Route::resource('FingerprintUser', 'FingerprintUserController');
Route::post('addFingerprintUser',['uses' => 'FingerprintUserController@store', 'as' => 'addFingerprintUser']);
Route::get('FingerprintUser',['uses' => 'FingerprintUserController@index', 'as' => 'FingerprintUser']); 
Route::post('FingerprintUser/update', 'FingerprintUserController@update')->name('FingerprintUser.update');
Route::get('FingerprintUser/destroy/{id}', 'FingerprintUserController@destroy');
Route::get('exportFPUser', 'FingerprintUserController@exportfpuser')->name('exportFPUser');
Route::post('FingerprintUser/getdeviceuserdata', 'FingerprintUserController@getdeviceuserdata');


Route::resource('FingerprintDevice', 'FingerprintDeviceController');
Route::post('addFingerprintDevice',['uses' => 'FingerprintDeviceController@store', 'as' => 'addFingerprintDevice']); 
Route::get('FingerprintDevice',['uses' => 'FingerprintDeviceController@index', 'as' => 'FingerprintDevice']); 
Route::post('FingerprintDevice/update', 'FingerprintDeviceController@update')->name('FingerprintDevice.update');
Route::get('FingerprintDevice/destroy/{id}', 'FingerprintDeviceController@destroy');


Route::resource('LeaveType', 'LeaveTypeController');
Route::post('addLeaveType',['uses' => 'LeaveTypeController@store', 'as' => 'addLeaveType']); 
Route::get('LeaveType',['uses' => 'LeaveTypeController@index', 'as' => 'LeaveType']); 
Route::post('LeaveType/update', 'LeaveTypeController@update')->name('LeaveType.update');
Route::get('LeaveType/destroy/{id}', 'LeaveTypeController@destroy');
Route::get('LeaveBalance',['uses' => 'LeaveTypeController@LeaveBalance', 'as' => 'LeaveBalance']);
Route::get('leave_balance_list',['uses' => 'LeaveTypeController@leave_balance_list', 'as' => 'leave_balance_list']);




Route::resource('LeaveApply', 'LeaveController');
Route::post('addLeaveApply',['uses' => 'LeaveController@store', 'as' => 'addLeaveApply']); 
Route::get('LeaveApply',['uses' => 'LeaveController@index', 'as' => 'LeaveApply']);
Route::get('leave_list_dt',['uses' => 'LeaveController@leave_list_dt', 'as' => 'leave_list_dt']);
Route::post('LeaveApply/update', 'LeaveController@update')->name('LeaveApply.update');
Route::get('LeaveApply/destroy/{id}', 'LeaveController@destroy');
Route::post('/getEmployeeLeaveStatus', 'LeaveController@getemployeeleavestatus');

Route::get('LeaveApprovel',['uses' => 'LeaveController@approvelindex', 'as' => 'LeaveApprovel']); 
Route::get('leave_approve_list_dt',['uses' => 'LeaveController@leave_approve_list_dt', 'as' => 'leave_approve_list_dt']);
Route::post('approvelupdate', 'LeaveController@approvelupdate')->name('approvelupdate');

Route::get('Leaveleavecreate', 'LeaveController@leaveleavecreate')->name('leaveleavecreate'); 


Route::get('EmpoloyeeReport',['uses' => 'Report@getemployeelist', 'as' => 'EmpoloyeeReport']);
Route::get('employee_report_list',['uses' => 'Report@employee_report_list', 'as' => 'employee_report_list']);

Route::get('AttendenceReport', 'Report@empoloyeeattendentall')->name('AttendenceReport');
Route::get('exportAttendanceReport', 'Report@exportattendances')->name('exportAttendanceReport');  
Route::get('exportEmployeeReport', 'Report@exportempoloyeereport')->name('exportEmployeeReport');  
Route::post('attendentfilter', 'Report@attendentfilter')->name('attendentfilter');  
Route::get('attendentreportbydate', function () {
    return view('Report.attendentreportbydate');
});
Route::get('attendetreportbyemployee', 'Report@attendentbyemployee')->name('attendetreportbyemployee'); 
Route::get('attendance_report_list', 'Report@attendance_report_list')->name('attendance_report_list');

//post get_attendance_by_employee_data
Route::post('get_attendance_by_employee_data', 'Report@get_attendance_by_employee_data')->name('get_attendance_by_employee_data');
//get_attendance_by_employee_data_excel
Route::post('get_attendance_by_employee_data_excel', 'Report@get_attendance_by_employee_data_excel')->name('get_attendance_by_employee_data_excel');



Route::post('/employee/fetch_data', 'Report@employee_fetch_data')->name('employee.fetch_data');
Route::get('employee_list_from_attendance_sel2', 'Report@employee_list_from_attendance_sel2')->name('employee_list_from_attendance_sel2');
Route::get('location_list_from_attendance_sel2', 'Report@location_list_from_attendance_sel2')->name('location_list_from_attendance_sel2');
Route::post('/employee/fetch_leave_data', 'Report@fetch_leave_data')->name('employee.fetch_leave_data');
Route::post('/employee/fetch_attend_data', 'Report@fetch_attend_data')->name('employee.fetch_attend_data');
Route::post('attendentbyemployeefilter', 'Report@attendentbyemployeefilter')->name('attendentbyemployeefilter');  
Route::post('leavedatafilter', 'Report@leavedatafilter')->name('leavedatafilter');  
Route::post('atenddatafilter', 'Report@atenddatafilter')->name('atenddatafilter');  
Route::get('/leaveReport', 'Report@leavereport')->name('leaveReport'); 
Route::get('/leave_report_list', 'Report@leave_report_list')->name('leave_report_list');
Route::get('/employee_list_from_leaves_sel2', 'Report@employee_list_from_leaves_sel2')->name('employee_list_from_leaves_sel2');
Route::get('/attendetreport', 'Report@attendetreport')->name('attendetreport');
Route::get('/daterange', 'Report@daterange');
Route::post('/daterange/fetch_data', 'Report@fetch_data')->name('daterange.fetch_data');
Route::post('/daterange/filter_data', 'Report@filter_data')->name('daterange.filter_data');
Route::get('LateAttendance',['uses' => 'Report@lateattendent', 'as' => 'LateAttendance']);
Route::get('late_attendance_report_list',['uses' => 'Report@late_attendance_report_list', 'as' => 'late_attendance_report_list']);
Route::get('exportLateAttend', 'Report@exportLateattend')->name('exportLateAttend');
Route::get('/LateAttendentView', 'AttendanceController@getlateAttendance');
//ot_approve
Route::get('/ot_approve', 'AttendanceController@ot_approve')->name('ot_approve');
//get_ot_details post
Route::post('/get_ot_details', 'AttendanceController@get_ot_details')->name('get_ot_details');
//ot_approve_post
Route::post('/ot_approve_post', 'AttendanceController@ot_approve_post')->name('ot_approve_post');

Route::get('ot_report', 'Report@ot_report')->name('ot_report');
Route::get('ot_report_list',['uses' => 'Report@ot_report_list', 'as' => 'ot_report_list']);
Route::get('ot_report_list_month',['uses' => 'Report@ot_report_list_month', 'as' => 'ot_report_list_month']);
//ot_report_list_view_more post
Route::post('ot_report_list_view_more', 'Report@ot_report_list_view_more')->name('ot_report_list_view_more');

//ot_approved
Route::get('/ot_approved', 'AttendanceController@ot_approved')->name('ot_approved');
//ot_approved_list
Route::get('/ot_approved_list', 'AttendanceController@ot_approved_list')->name('ot_approved_list');

//ot_approved_delete post
Route::post('/ot_approved_delete', 'AttendanceController@ot_approved_delete')->name('ot_approved_delete');


Route::get('no_pay_report', 'Report@no_pay_report')->name('no_pay_report');
Route::get('no_pay_report_list_month',['uses' => 'Report@no_pay_report_list_month', 'as' => 'no_pay_report_list_month']);
Route::post('no_pay_days_data',['uses' => 'Report@no_pay_days_data', 'as' => 'no_pay_days_data']);

Route::get('/copy_att_to_employee_work_rates', 'AttendanceController@copy_att_to_employee_work_rates');

Route::get('/attendance_list_ajax', 'AttendanceController@attendance_list_ajax');


Route::resource('ShiftType', 'ShiftTypeController');
Route::post('addShiftType',['uses' => 'ShiftTypeController@store', 'as' => 'addShiftType']); 
Route::get('ShiftType',['uses' => 'ShiftTypeController@index', 'as' => 'ShiftType']); 
Route::post('ShiftType/update', 'ShiftTypeController@update')->name('ShiftType.update');
Route::get('ShiftType/destroy/{id}', 'ShiftTypeController@destroy');


Route::resource('Shift', 'ShiftController');
Route::post('addShift',['uses' => 'ShiftController@store', 'as' => 'addShift']); 
Route::get('Shift',['uses' => 'ShiftController@index', 'as' => 'Shift']); 
Route::get('shift_list_dt',['uses' => 'ShiftController@shift_list_dt', 'as' => 'shift_list_dt']);
Route::post('Shift/update', 'ShiftController@update')->name('Shift.update');
Route::get('Shift/destroy/{id}', 'ShiftController@destroy');
Route::post('/Shiftupdate', 'ShiftController@Shiftupdate');
Route::get('/Getshift', 'ShiftController@getshift');

Route::resource('Holiday', 'HolidayController');
Route::post('addHoliday',['uses' => 'HolidayController@store', 'as' => 'addHoliday']); 
Route::get('Holiday',['uses' => 'HolidayController@index', 'as' => 'Holiday']); 
Route::post('Holiday/update', 'HolidayController@update')->name('Holiday.update');
Route::get('Holiday/destroy/{id}', 'HolidayController@destroy');

Route::resource('Worklevel', 'WorkLevelController');
Route::post('addWorklevel',['uses' => 'WorkLevelController@store', 'as' => 'addWorklevel']); 
Route::get('Worklevel',['uses' => 'WorkLevelController@index', 'as' => 'Worklevel']); 
Route::post('Worklevel/update', 'WorkLevelController@update')->name('Worklevel.update');
Route::get('Worklevel/destroy/{id}', 'WorkLevelController@destroy');

/*-- Company Info----*/
Route::resource('Company', 'CompanyController');
Route::get('Company',['uses' => 'CompanyController@index', 'as' => 'Company']); 
Route::post('addCompany',['uses' => 'CompanyController@store', 'as' => 'addCompany']); 
Route::post('Company/update', 'CompanyController@update')->name('Company.update');
Route::get('Company/destroy/{id}', 'CompanyController@destroy');
Route::get('company_list_sel2', 'CompanyController@company_list_sel2');
/*-- End Company Info----*/

/*-- Department Info----*/
Route::resource('Department', 'DepartmentController');
Route::get('DepartmentShow/{id}',['uses' => 'DepartmentController@index', 'as' => 'DepartmentShow']);
Route::post('addDepartment',['uses' => 'DepartmentController@store', 'as' => 'addDepartment']);
Route::post('Department/update', 'DepartmentController@update')->name('Department.update');
Route::get('Department/destroy/{id}', 'DepartmentController@destroy');
Route::get('department_list_sel2', 'DepartmentController@department_list_sel2');

/*-- End Department Info----*/

/*-- Bank Info----*/
Route::resource('Bank', 'BankController');
Route::get('Bank',['uses' => 'BankController@index', 'as' => 'Bank']);
Route::post('addBank',['uses' => 'BankController@store', 'as' => 'addBank']);
Route::post('Bank/update', 'BankController@update')->name('Bank.update');
Route::get('Bank/destroy/{id}', 'BankController@destroy');
Route::get('bank_list', 'BankController@bank_list');
Route::get('banks_list_dt',['uses' => 'BankController@banks_list_dt', 'as' => 'banks_list_dt']);
/*-- End Bank Info----*/

/*-- bank_branch Info----*/
Route::resource('bank_branch', 'BankBranchController');
Route::get('bank_branch_show/{id}',['uses' => 'BankBranchController@index', 'as' => 'bank_branch_show']);
Route::post('addBankBranch',['uses' => 'BankBranchController@store', 'as' => 'addBankBranch']);
Route::post('BankBranch/update', 'BankBranchController@update')->name('BankBranch.update');
Route::get('BankBranch/destroy/{id}', 'BankBranchController@destroy');
Route::get('BankBranchEdit/{id}', 'BankBranchController@edit');
Route::get('branch_list', 'BankBranchController@branch_list');
//bank_branches_list_dt
Route::get('bank_branches_list_dt',['uses' => 'BankBranchController@bank_branches_list_dt', 'as' => 'bank_branches_list_dt']);

/*-- End bank_branch Info----*/

//resource OccupationGroup
Route::resource('OccupationGroup', 'OccupationGroupController');
//occupation_group_list_dt
Route::get('occupation_group_list_dt',['uses' => 'OccupationGroupController@occupation_group_list_dt', 'as' => 'occupation_group_list_dt']);
//OccupationGroup.update_manual
Route::post('OccupationGroup.update_manual', 'OccupationGroupController@update_manual')->name('OccupationGroup.update_manual');
//OccupationGroup.fetch_single
Route::get('OccupationGroup.fetch_single', 'OccupationGroupController@fetch_single')->name('OccupationGroup.fetch_single');

/*-- common routes --*/
Route::get('employee_list_sel2', 'EmployeeController@employee_list_sel2')->name('employee_list_sel2');
Route::get('location_list_sel2', 'EmployeeController@location_list_sel2')->name('location_list_sel2');
Route::post('get_dept_emp_list', 'EmployeeController@get_dept_emp_list')->name('get_dept_emp_list');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/clear-cache', function() {
    Artisan::call('config:clear'); 
    Artisan::call('config:cache'); 
    Artisan::call('cache:clear'); 
    Artisan::call('view:clear'); 
    Artisan::call('route:clear'); 
    Artisan::call('route:cache'); 
    Artisan::call('clear-compiled'); 
    Artisan::call('optimize');
});