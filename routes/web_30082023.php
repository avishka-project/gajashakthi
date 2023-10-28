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

Route::get('/download_file/{file}',['uses' => 'EmployeeController@download_file', 'as' => 'download_file']);
Route::get('attachment/destroy/{id}', 'EmployeeController@destroy_attachment');


Route::resource('EmployeeSelect', 'SelectEmployeeController');
Route::get('selectEmployee',['uses' => 'SelectEmployeeController@create', 'as' => 'selectEmployee']);
Route::post('/get_select_employee_details', 'SelectEmployeeController@get_select_employee_details')->name('get_select_employee_details');
Route::post('/select_employee_post', 'SelectEmployeeController@select_employee_post')->name('select_employee_post');
Route::get('selectEmployeeIndex',['uses' => 'SelectEmployeeController@index', 'as' => 'selectEmployeeIndex']);
Route::get('select_employee_list_dt',['uses' => 'SelectEmployeeController@select_employee_list_dt', 'as' => 'select_employee_list_dt']);
Route::get('EmployeeSelect/destroy/{id}', 'SelectEmployeeController@destroy');


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

//AttendanceDeviceClear
Route::get('AttendanceDeviceClear', 'AttendanceClearController@attendance_clear_list')->name('AttendanceDeviceClear');

//Attendance.cleardevicedata
Route::post('/Attendance/cleardevicedata', 'AttendanceController@cleardevicedata')->name('Attendance.cleardevicedata');

//attendance_clear_list
Route::get('attendance_clear_list_dt',['uses' => 'AttendanceClearController@attendance_clear_list_dt', 'as' => 'attendance_clear_list_dt']);

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

Route::get('/ot_approved_list_monthly', 'AttendanceController@ot_approved_list_monthly')->name('ot_approved_list_monthly');

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

// emptype controller routes
Route::get('/emptypes' ,'Emptypecontroller@index')->name('emptypes');
Route::post('/typeinsert' ,'Emptypecontroller@insert')->name('typeinsert');
Route::get('/typelist' ,'Emptypecontroller@typelist')->name('typelist');
Route::post('/typeedit' ,'Emptypecontroller@edit')->name('typeedit');
Route::post('/typeupdate' ,'Emptypecontroller@update')->name('typeupdate');
Route::post('/typedelete' ,'Emptypecontroller@delete')->name('typedelete');
Route::post('/typeapprove' ,'Emptypecontroller@approve')->name('typeapprove');
Route::get('/typestatus/{id}/{stasus}','Emptypecontroller@status')->name('typestatus');


// region controller routes
Route::get('/regions' ,'Regioncontroller@index')->name('regions');
Route::post('/regioninsert' ,'Regioncontroller@insert')->name('regioninsert');
Route::get('/regionlist' ,'Regioncontroller@typelist')->name('regionlist');
Route::post('/regionedit' ,'Regioncontroller@edit')->name('regionedit');
Route::post('/regionupdate' ,'Regioncontroller@update')->name('regionupdate');
Route::post('/regiondelete' ,'Regioncontroller@delete')->name('regiondelete');
Route::post('/regionapprove' ,'Regioncontroller@approve')->name('regionapprove');
Route::get('/regionstatus/{id}/{stasus}','Regioncontroller@status')->name('regionstatus');

// subregion controller routes
Route::get('/subregions' ,'Subregioncontroller@index')->name('subregions');
Route::post('/subreginsert' ,'Subregioncontroller@insert')->name('subreginsert');
Route::get('/subreglist' ,'Subregioncontroller@typelist')->name('subreglist');
Route::post('/subregedit' ,'Subregioncontroller@edit')->name('subregedit');
Route::post('/subregupdate' ,'Subregioncontroller@update')->name('subregupdate');
Route::post('/subregdelete' ,'Subregioncontroller@delete')->name('subregdelete');
Route::post('/subregapprove' ,'Subregioncontroller@approve')->name('subregapprove');
Route::get('/subregstatus/{id}/{stasus}','Subregioncontroller@status')->name('subregstatus');


// customer controller
Route::get('/customers' ,'Customercontroller@index')->name('customers');
Route::post('/customerinsert' ,'Customercontroller@insert')->name('customerinsert');
Route::get('/customerlist' ,'Customercontroller@list')->name('customerlist');
Route::post('/customeredit' ,'Customercontroller@edit')->name('customeredit');
Route::post('/customerupdate' ,'Customercontroller@update')->name('customerupdate');
Route::post('/customerdelete' ,'Customercontroller@delete')->name('customerdelete');
Route::post('/customerapprove' ,'Customercontroller@approve')->name('customerapprove');
Route::get('/customerstatus/{id}/{stasus}','Customercontroller@status')->name('customerstatus');


// customercontact controller
Route::get('/cuscontact/{id}' ,'Customercontactcontroller@index')->name('cuscontact');
Route::post('/cuscontactinsert' ,'Customercontactcontroller@insert')->name('cuscontactinsert');
Route::get('/cuscontactlist' ,'Customercontactcontroller@list')->name('cuscontactlist');
Route::post('/cuscontactedit' ,'Customercontactcontroller@edit')->name('cuscontactedit');
Route::post('/cuscontactupdate' ,'Customercontactcontroller@update')->name('cuscontactupdate');
Route::post('/cuscontactdelete' ,'Customercontactcontroller@delete')->name('cuscontactdelete');

// branch controller
Route::get('/branchers' ,'Branchcontroller@index')->name('branchers');
Route::post('/branchinsert' ,'Branchcontroller@insert')->name('branchinsert');
Route::get('/branchlist' ,'Branchcontroller@list')->name('branchlist');
Route::post('/branchedit' ,'Branchcontroller@edit')->name('branchedit');
Route::post('/branchupdate' ,'Branchcontroller@update')->name('branchupdate');
Route::post('/branchdelete' ,'Branchcontroller@delete')->name('branchdelete');
Route::post('/branchapprove' ,'Branchcontroller@approve')->name('branchapprove');
Route::get('/branchstatus/{id}/{stasus}','Branchcontroller@status')->name('branchstatus');


// customercontact controller
Route::get('/branchcontact/{id}' ,'Branchcontactcontroller@index')->name('branchcontact');
Route::post('/branchcontactinsert' ,'Branchcontactcontroller@insert')->name('branchcontactinsert');
Route::get('/branchcontactlist' ,'Branchcontactcontroller@list')->name('branchcontactlist');
Route::post('/branchcontactedit' ,'Branchcontactcontroller@edit')->name('branchcontactedit');
Route::post('/branchcontactupdate' ,'Branchcontactcontroller@update')->name('branchcontactupdate');
Route::post('/branchcontactdelete' ,'Branchcontactcontroller@delete')->name('branchcontactdelete');


// customer category controller routes
Route::get('/cuscategory' ,'Customercategorycontroller@index')->name('cuscategory');
Route::post('/cuscatinsert' ,'Customercategorycontroller@insert')->name('cuscatinsert');
Route::get('/cuscatlist' ,'Customercategorycontroller@list')->name('cuscatlist');
Route::post('/cuscatedit' ,'Customercategorycontroller@edit')->name('cuscatedit');
Route::post('/cuscatupdate' ,'Customercategorycontroller@update')->name('cuscatupdate');
Route::post('/cuscatdelete' ,'Customercategorycontroller@delete')->name('cuscatdelete');
Route::post('/cuscatapprove' ,'Customercategorycontroller@approve')->name('cuscatapprove');
Route::get('/cuscatstatus/{id}/{stasus}','Customercategorycontroller@status')->name('cuscatstatus');

// sub customer controller
Route::get('/subcustomers' ,'Subcustomercontroller@index')->name('subcustomers');
Route::post('/subcustomerinsert' ,'Subcustomercontroller@insert')->name('subcustomerinsert');
Route::get('/subcustomerlist' ,'Subcustomercontroller@list')->name('subcustomerlist');
Route::post('/subcustomeredit' ,'Subcustomercontroller@edit')->name('subcustomeredit');
Route::post('/subcustomerupdate' ,'Subcustomercontroller@update')->name('subcustomerupdate');
Route::post('/subcustomerdelete' ,'Subcustomercontroller@delete')->name('subcustomerdelete');
Route::post('/subcustomerapprove' ,'Subcustomercontroller@approve')->name('subcustomerapprove');
Route::get('/subcustomerstatus/{id}/{stasus}','Subcustomercontroller@status')->name('subcustomerstatus');


// sub customercontact controller
Route::get('/subcuscontact/{id}' ,'Subcustomercontactcontroller@index')->name('subcuscontact');
Route::post('/subcuscontactinsert' ,'Subcustomercontactcontroller@insert')->name('subcuscontactinsert');
Route::get('/subcuscontactlist' ,'Subcustomercontactcontroller@list')->name('subcuscontactlist');
Route::post('/subcuscontactedit' ,'Subcustomercontactcontroller@edit')->name('subcuscontactedit');
Route::post('/subcuscontactupdate' ,'Subcustomercontactcontroller@update')->name('subcuscontactupdate');
Route::post('/subcuscontactdelete' ,'Subcustomercontactcontroller@delete')->name('subcuscontactdelete');

Route::get('region_list_sel2', 'DepartmentController@region_list_sel2');
Route::get('subregion_list_sel2', 'DepartmentController@subregion_list_sel2');
Route::get('emptype_list_sel2', 'DepartmentController@emptype_list_sel2');

// Customers Request Controller Routes
Route::get('/customerrequest','CustomerrequestController@index')->name('customerrequest');
Route::post('/insert','CustomerrequestController@insert')->name('insert');
Route::get('/displaycustomerrequest','CustomerrequestController@displaycustomerrequest')->name('displaycustomerrequest');
Route::post('/delete','CustomerrequestController@delete')->name('delete');
Route::post('/customerrequestedit' ,'CustomerrequestController@edit')->name('customerrequestedit');
Route::post('/customerrequestapprove' ,'CustomerrequestController@approve')->name('customerrequestapprove');
Route::post('/customerrequestupdate' ,'CustomerrequestController@update')->name('customerrequestupdate');
Route::get('/customerrequeststatus/{id}/{stasus}','CustomerrequestController@status')->name('customerrequeststatus');
Route::get('/getsubcustomers/{customerId}', 'CustomerrequestController@getSubCustomers')->name('getsubcustomers');
Route::get('/getbranch/{subcustomerId}', 'CustomerrequestController@getbranch')->name('getbranch');
Route::post('/requestlist' ,'CustomerrequestController@requestlist')->name('requestlist');
Route::post('/requestdetailedit' ,'CustomerrequestController@editlist')->name('requestdetailedit');
Route::post('/customerrequestapprovel_details' ,'CustomerrequestController@approvel_details')->name('customerrequestapprovel_details');

// employee allocation controller routes
Route::get('/allocation' ,'EmpallocationController@index')->name('allocation');
Route::post('/requestlist' ,'EmpallocationController@requestlist')->name('requestlist');
Route::post('/requestdetails' ,'EmpallocationController@requestdetails')->name('requestdetails');
Route::post('/requestdetailslist' ,'EmpallocationController@requestdetailslist')->name('requestdetailslist');
Route::post('/alloctioninsert' ,'EmpallocationController@insert')->name('alloctioninsert');
Route::post('/alocationlist' ,'EmpallocationController@list')->name('alocationlist');
Route::post('/alloctionedit' ,'EmpallocationController@edit')->name('alloctionedit');
Route::post('/alloctiondetailedit' ,'EmpallocationController@editlist')->name('alloctiondetailedit');
Route::post('/alloctionupdate' ,'EmpallocationController@update')->name('alloctionupdate');
Route::post('/alloctiondelete' ,'EmpallocationController@delete')->name('alloctiondelete');
Route::post('/alloctiondetailedelete' ,'EmpallocationController@deletelist')->name('alloctiondetailedelete');
Route::post('/alloctionapprove' ,'EmpallocationController@approve')->name('alloctionapprove');
Route::get('/alloctionstatus/{id}/{stasus}','EmpallocationController@status')->name('alloctionstatus');
Route::post('/pervoiuslist' ,'EmpallocationController@perviousdatelist')->name('pervoiuslist');

//employee attendance controller routes
Route::get('/empattendance' ,'Empattendancescontroller@index')->name('empattendance');
Route::post('/allocationlist' ,'Empattendancescontroller@allocationlist')->name('allocationlist');
Route::post('/attendancelist' ,'Empattendancescontroller@list')->name('attendancelist');
Route::post('/attendenceonlist' ,'Empattendancescontroller@allocationdetails')->name('attendenceonlist');
Route::post('/attendenceontimeinsert' ,'Empattendancescontroller@insert')->name('attendenceontimeinsert');
Route::post('/attendenceedit' ,'Empattendancescontroller@edit')->name('attendenceedit');
Route::post('/attendenceupdate' ,'Empattendancescontroller@update')->name('attendenceupdate');

// employee attendance approve controller routes
Route::get('/attendanceapprove' ,'Empattendancesapprovecontroller@index')->name('attendanceapprove');
Route::get('/getsubcustomersattendance/{customerId}', 'Empattendancesapprovecontroller@getSubCustomers')->name('getsubcustomersattendance');
Route::get('/getbranchattendance/{subcustomerId}', 'Empattendancesapprovecontroller@getbranch')->name('getbranchattendance');
Route::post('/attendanceapprovelist' ,'Empattendancesapprovecontroller@list')->name('attendanceapprovelist');
Route::post('/attendanceapproveedit' ,'Empattendancesapprovecontroller@edit')->name('attendanceapproveedit');
Route::post('/attendanceapprove' ,'Empattendancesapprovecontroller@approve')->name('attendanceapprove');
Route::post('/attendencedelete' ,'Empattendancesapprovecontroller@delete')->name('attendencedelete');


// employee payment controller routes
Route::get('/employeepayment' ,'Employeepaymentcontroller@index')->name('employeepayment');
Route::post('/employeepaymentinsert' ,'Employeepaymentcontroller@insert')->name('employeepaymentinsert');
Route::get('/displayemployeepayment','Employeepaymentcontroller@displayemployeepayment')->name('displayemployeepayment');
Route::get('/employeepaymentlist' ,'Employeepaymentcontroller@typelist')->name('employeepaymentlist');
Route::post('/employeepaymentedit' ,'Employeepaymentcontroller@edit')->name('employeepaymentedit');
Route::post('/employeepaymentupdate' ,'Employeepaymentcontroller@update')->name('employeepaymentupdate');
Route::post('/employeepaymentdelete' ,'Employeepaymentcontroller@delete')->name('employeepaymentdelete');
Route::post('/employeepaymentapprove' ,'Employeepaymentcontroller@approve')->name('employeepaymentapprove');
Route::get('/employeepaymentstatus/{id}/{stasus}','Employeepaymentcontroller@status')->name('employeepaymentstatus');
Route::get('/getsubcustomers/{customerId}', 'Employeepaymentcontroller@getSubCustomers')->name('getsubcustomers');
Route::get('/getbranch/{subcustomerId}', 'Employeepaymentcontroller@getbranch')->name('getbranch');
Route::post('/detailedit' ,'Employeepaymentcontroller@editlist')->name('detailedit');
Route::post('/employeepaymentapprovel_details' ,'Employeepaymentcontroller@approvel_details')->name('employeepaymentapprovel_details');

Route::get('skill_list_sel2', 'SkillController@skill_list_sel2')->name('skill_list_sel2');

// Customers Request Controller Routes
Route::get('/getsubregion_id/{areaId}', 'CustomerrequestController@getSubregionId')->name('getsubregion_id');

// employee allocation controller routes
Route::get('/getbranchallocatoion/{subcustomerId}', 'EmpallocationController@getbranch')->name('getbranchallocatoion');
Route::get('/viewaallocation/{id}/{action}' ,'EmpallocationController@addallocation')->name('viewaallocation');
Route::get('/getempregion/{id}' ,'EmpallocationController@getempsubregion')->name('getempregion');
Route::get('/specialrequest' ,'EmpallocationController@specialrequest')->name('specialrequest');


// special request approve controller route
Route::get('/specialrequests' ,'SpecialrequestapproveController@index')->name('specialrequests');
Route::get('/specialrequestslist' ,'SpecialrequestapproveController@list')->name('specialrequestslist');
Route::post('/specialrequestsedit' ,'SpecialrequestapproveController@edit')->name('specialrequestsedit');
Route::post('/specialrequestsapprove' ,'SpecialrequestapproveController@approve')->name('specialrequestsapprove');

// regionalmanager controller routes
Route::get('/regionalmanger/{id}' ,'RegionalmanagerController@index')->name('regionalmanger');
Route::post('/regionalmangerinsert' ,'RegionalmanagerController@insert')->name('regionalmangerinsert');
Route::get('/regionalmangerlist' ,'RegionalmanagerController@list')->name('regionalmangerlist');
Route::post('/regionalmangeredit' ,'RegionalmanagerController@edit')->name('regionalmangeredit');
Route::post('/regionalmangerupdate' ,'RegionalmanagerController@update')->name('regionalmangerupdate');
Route::post('/regionalmangerdelete' ,'RegionalmanagerController@delete')->name('regionalmangerdelete');
Route::post('/regionalmangerapprove' ,'RegionalmanagerController@approve')->name('regionalmangerapprove');

// sub regionalmanager controller routes
Route::get('/subregionalmanger/{id}' ,'SubRegionalmanagerController@index')->name('subregionalmanger');
Route::post('/subregionalmangerinsert' ,'SubRegionalmanagerController@insert')->name('subregionalmangerinsert');
Route::get('/subregionalmangerlist' ,'SubRegionalmanagerController@list')->name('subregionalmangerlist');
Route::post('/subregionalmangeredit' ,'SubRegionalmanagerController@edit')->name('subregionalmangeredit');
Route::post('/subregionalmangerupdate' ,'SubRegionalmanagerController@update')->name('subregionalmangerupdate');
Route::post('/subregionalmangerdelete' ,'SubRegionalmanagerController@delete')->name('subregionalmangerdelete');
Route::post('/subregionalmangerapprove' ,'SubRegionalmanagerController@approve')->name('subregionalmangerapprove');

// Vehicle controller routes
Route::get('/vehicle' ,'Vehiclecontroller@index')->name('vehicle');
Route::get('/vehiclelist' ,'Vehiclecontroller@requestlist')->name('vehiclelist');
Route::post('/vehicleinsert' ,'Vehiclecontroller@insert')->name('vehicleinsert');
Route::post('/vehicleedit' ,'Vehiclecontroller@edit')->name('vehicleedit');
Route::post('/vehicleupdate' ,'Vehiclecontroller@update')->name('vehicleupdate');
Route::post('/vehicledelete' ,'Vehiclecontroller@delete')->name('vehicledelete');
Route::post('/vehicleapprove' ,'Vehiclecontroller@approve')->name('vehicleapprove');
Route::get('/vehiclestatus/{id}/{stasus}','Vehiclecontroller@status')->name('vehiclestatus');

// Vehicle allocate controller routes
Route::get('/vehicleallocate' ,'VehicleallocateController@index')->name('vehicleallocate');
Route::get('/vehicleallocatelist' ,'VehicleallocateController@list')->name('vehicleallocatelist');
Route::post('/vehicleallocateinsert' ,'VehicleallocateController@insert')->name('vehicleallocateinsert');
Route::post('/vehicleallocateedit' ,'VehicleallocateController@edit')->name('vehicleallocateedit');
Route::post('/vehicleallocateupdate' ,'VehicleallocateController@update')->name('vehicleallocateupdate');
Route::post('/vehicleallocatedelete' ,'VehicleallocateController@delete')->name('vehicleallocatedelete');
Route::post('/vehicleallocateapprove' ,'VehicleallocateController@approve')->name('vehicleallocateapprove');
Route::get('/vehicleallocatestatus/{id}/{stasus}','VehicleallocateController@status')->name('vehicleallocatestatus');


// Supplier controller routes
Route::get('/supplier' ,'Suppliercontroller@index')->name('supplier');
Route::get('/supplierlist' ,'Suppliercontroller@requestlist')->name('supplierlist');
Route::post('/supplierinsert' ,'Suppliercontroller@insert')->name('supplierinsert');
Route::post('/supplieredit' ,'Suppliercontroller@edit')->name('supplieredit');
Route::post('/supplierupdate' ,'Suppliercontroller@update')->name('supplierupdate');
Route::post('/supplierdelete' ,'Suppliercontroller@delete')->name('supplierdelete');
Route::post('/supplierapprove' ,'Suppliercontroller@approve')->name('supplierapprove');
Route::get('/supplierstatus/{id}/{stasus}','Suppliercontroller@status')->name('supplierstatus');

// Item controller routes
Route::get('/item' ,'Itemcontroller@index')->name('item');
Route::get('/itemlist' ,'Itemcontroller@requestlist')->name('itemlist');
Route::post('/iteminsert' ,'Itemcontroller@insert')->name('iteminsert');
Route::post('/itemedit' ,'Itemcontroller@edit')->name('itemedit');
Route::post('/itemupdate' ,'Itemcontroller@update')->name('itemupdate');
Route::post('/itemdelete' ,'Itemcontroller@delete')->name('itemdelete');
Route::post('/itemapprove' ,'Itemcontroller@approve')->name('itemapprove');
Route::get('/itemstatus/{id}/{stasus}','Itemcontroller@status')->name('itemstatus');

// Item Category controller routes
Route::get('/itemcategory' ,'Itemcategorycontroller@index')->name('itemcategory');
Route::get('/itemcategorylist' ,'Itemcategorycontroller@requestlist')->name('itemcategorylist');
Route::post('/itemcategoryinsert' ,'Itemcategorycontroller@insert')->name('itemcategoryinsert');
Route::post('/itemcategoryedit' ,'Itemcategorycontroller@edit')->name('itemcategoryedit');
Route::post('/itemcategoryupdate' ,'Itemcategorycontroller@update')->name('itemcategoryupdate');
Route::post('/itemcategorydelete' ,'Itemcategorycontroller@delete')->name('itemcategorydelete');
Route::post('/itemcategoryapprove' ,'Itemcategorycontroller@approve')->name('itemcategoryapprove');
Route::get('/itemcategorystatus/{id}/{stasus}','Itemcategorycontroller@status')->name('itemcategorystatus');

// suppliercontact controller
Route::get('/supcontact/{id}' ,'Suppliercontactcontroller@index')->name('supcontact');
Route::post('/supcontactinsert' ,'Suppliercontactcontroller@insert')->name('supcontactinsert');
Route::get('/supcontactlist' ,'Suppliercontactcontroller@list')->name('supcontactlist');
Route::post('/supcontactedit' ,'Suppliercontactcontroller@edit')->name('supcontactedit');
Route::post('/supcontactupdate' ,'Suppliercontactcontroller@update')->name('supcontactupdate');
Route::post('/supcontactdelete' ,'Suppliercontactcontroller@delete')->name('supcontactdelete');


// Issue controller routes
Route::get('/issue' ,'Issuecontroller@index')->name('issue');
Route::get('/issuelist' ,'Issuecontroller@requestlist')->name('issuelist');
Route::post('/issueinsert' ,'Issuecontroller@insert')->name('issueinsert');
Route::post('/issueedit' ,'Issuecontroller@edit')->name('issueedit');
Route::post('/issueupdate' ,'Issuecontroller@update')->name('issueupdate');
Route::post('/issuedelete' ,'Issuecontroller@delete')->name('issuedelete');
Route::post('/issueapprove' ,'Issuecontroller@approve')->name('issueapprove');
Route::post('/issuedetailedit' ,'Issuecontroller@editlist')->name('issuedetailedit');
Route::post('/issuedetaildelete' ,'Issuecontroller@deletelist')->name('issuedetaildelete');
Route::post('/issuedetailapprovel_details' ,'Issuecontroller@approvel_details')->name('issuedetailapprovel_details');
Route::get('/issuestatus/{id}/{stasus}','Issuecontroller@status')->name('issuestatus');


// Dead donation controller routes
Route::get('/deaddonation' ,'Deaddonationcontroller@index')->name('deaddonation');
Route::get('/deaddonationlist' ,'Deaddonationcontroller@requestlist')->name('deaddonationlist');
Route::post('/deaddonationinsert' ,'Deaddonationcontroller@insert')->name('deaddonationinsert');
Route::post('/deaddonationedit' ,'Deaddonationcontroller@edit')->name('deaddonationedit');
Route::post('/deaddonationupdate' ,'Deaddonationcontroller@update')->name('deaddonationupdate');
Route::post('/deaddonationdelete' ,'Deaddonationcontroller@delete')->name('deaddonationdelete');
Route::post('/deaddonationapprove' ,'Deaddonationcontroller@approve')->name('deaddonationapprove');
Route::get('/deaddonationstatus/{id}/{stasus}','Deaddonationcontroller@status')->name('deaddonationstatus');
Route::post('/deaddonationgetempname' ,'Deaddonationcontroller@getempname')->name('deaddonationgetempname');
Route::get('/getrelatives/{empId}', 'Deaddonationcontroller@getrelatives')->name('getrelatives');


// Dead donation assignallocation controller routes
Route::get('/assignallocation' ,'Deaddonationallocationcontroller@index')->name('assignallocation');
Route::get('/assignallocationlist' ,'Deaddonationallocationcontroller@requestlist')->name('assignallocationlist');
Route::post('/assignallocationinsert' ,'Deaddonationallocationcontroller@insert')->name('assignallocationinsert');
Route::post('/assignallocationedit' ,'Deaddonationallocationcontroller@edit')->name('assignallocationedit');
Route::post('/assignallocationupdate' ,'Deaddonationallocationcontroller@update')->name('assignallocationupdate');
Route::post('/assignallocationdelete' ,'Deaddonationallocationcontroller@delete')->name('assignallocationdelete');
Route::post('/assignallocationapprove' ,'Deaddonationallocationcontroller@approve')->name('assignallocationapprove');
Route::get('/assignallocationstatus/{id}/{stasus}','Deaddonationallocationcontroller@status')->name('assignallocationstatus');
Route::post('/assignallocationgetempname' ,'Deaddonationallocationcontroller@getempname')->name('assignallocationgetempname');


// Dead donation incomplete controller routes
Route::get('/incomplete' ,'Deaddonationincompletecontroller@index')->name('incomplete');
Route::get('/incompletelist' ,'Deaddonationincompletecontroller@requestlist')->name('incompletelist');
Route::get('/downloaddocument/{id}', 'Deaddonationincompletecontroller@download')->name('downloaddocument');
Route::post('/viewpdf', 'Deaddonationincompletecontroller@viewPDF')->name('viewpdf');
Route::post('/downloadpdf', 'Deaddonationincompletecontroller@downloadPDF')->name('downloadpdf');
Route::post('/upload', 'Deaddonationincompletecontroller@upload')->name('upload');
Route::post('/incompleteedit' ,'Deaddonationincompletecontroller@edit')->name('incompleteedit');
Route::post('/incompleteupdate' ,'Deaddonationincompletecontroller@update')->name('incompleteupdate');
Route::post('/incompletedelete' ,'Deaddonationincompletecontroller@delete')->name('incompletedelete');
Route::post('/incompleteapprove' ,'Deaddonationincompletecontroller@approve')->name('incompleteapprove');
Route::get('/incompletestatus/{id}/{stasus}','Deaddonationincompletecontroller@status')->name('incompletestatus');


// Dead donation last allocation controller routes
Route::get('/lastallocation' ,'Deaddonationlastallocationcontroller@index')->name('lastallocation');
Route::get('/lastallocationlist' ,'Deaddonationlastallocationcontroller@requestlist')->name('lastallocationlist');
Route::post('/lastallocationinsert' ,'Deaddonationlastallocationcontroller@insert')->name('lastallocationinsert');
Route::post('/lastallocationedit' ,'Deaddonationlastallocationcontroller@edit')->name('lastallocationedit');
Route::post('/lastallocationupdate' ,'Deaddonationlastallocationcontroller@update')->name('lastallocationupdate');
Route::post('/lastallocationdelete' ,'Deaddonationlastallocationcontroller@delete')->name('lastallocationdelete');
Route::post('/lastallocationapprove' ,'Deaddonationlastallocationcontroller@approve')->name('lastallocationapprove');
Route::get('/lastallocationstatus/{id}/{stasus}','Deaddonationlastallocationcontroller@status')->name('lastallocationstatus');


// Dead donation deaddonationdetail controller routes
Route::get('/deaddonationdetail' ,'Deaddonationdetailcontroller@index')->name('deaddonationdetail');
Route::get('/deaddonationdetaillist' ,'Deaddonationdetailcontroller@requestlist')->name('deaddonationdetaillist');
Route::post('/deaddonationdetaildelete' ,'Deaddonationdetailcontroller@delete')->name('deaddonationdetaildelete');
Route::post('/getdeaddonationdetails' ,'Deaddonationdetailcontroller@getdeaddonationdetails')->name('getdeaddonationdetails');
Route::get('/deaddonationdetailstatus/{id}/{stasus}','Deaddonationdetailcontroller@status')->name('deaddonationdetailstatus');

// Vehicle Service & Repair controller routes
Route::get('/vehicleserviceandrepair' ,'Vehicleserviceandrepaircontroller@index')->name('vehicleserviceandrepair');
Route::get('/vehicleserviceandrepairlist' ,'Vehicleserviceandrepaircontroller@requestlist')->name('vehicleserviceandrepairlist');
Route::post('/vehicleserviceandrepairinsert' ,'Vehicleserviceandrepaircontroller@insert')->name('vehicleserviceandrepairinsert');
Route::post('/vehicleserviceandrepairedit' ,'Vehicleserviceandrepaircontroller@edit')->name('vehicleserviceandrepairedit');
Route::post('/vehicleserviceandrepairupdate' ,'Vehicleserviceandrepaircontroller@update')->name('vehicleserviceandrepairupdate');
Route::post('/vehicleserviceandrepairdelete' ,'Vehicleserviceandrepaircontroller@delete')->name('vehicleserviceandrepairdelete');
Route::post('/vehicleserviceandrepairapprove' ,'Vehicleserviceandrepaircontroller@approve')->name('vehicleserviceandrepairapprove');
Route::get('/vehicleserviceandrepairstatus/{id}/{stasus}','Vehicleserviceandrepaircontroller@status')->name('vehicleserviceandrepairstatus');

// Mobile Bill Payment controller routes
Route::get('/mobilebillpayment' ,'Mobilebillpaymentcontroller@index')->name('mobilebillpayment');
Route::get('/mobilebillpaymentlist' ,'Mobilebillpaymentcontroller@requestlist')->name('mobilebillpaymentlist');
Route::post('/mobilebillpaymentinsert' ,'Mobilebillpaymentcontroller@insert')->name('mobilebillpaymentinsert');
Route::post('/mobilebillpaymentedit' ,'Mobilebillpaymentcontroller@edit')->name('mobilebillpaymentedit');
Route::post('/mobilebillpaymentupdate' ,'Mobilebillpaymentcontroller@update')->name('mobilebillpaymentupdate');
Route::post('/mobilebillpaymentdelete' ,'Mobilebillpaymentcontroller@delete')->name('mobilebillpaymentdelete');
Route::post('/mobilebillpaymentapprove' ,'Mobilebillpaymentcontroller@approve')->name('mobilebillpaymentapprove');
Route::get('/mobilebillpaymentstatus/{id}/{stasus}','Mobilebillpaymentcontroller@status')->name('mobilebillpaymentstatus');

// New Business Proposal controller routes
Route::get('/newbusinessproposal' ,'Newbusinessproposalcontroller@index')->name('newbusinessproposal');
Route::get('/newbusinessproposallist' ,'Newbusinessproposalcontroller@requestlist')->name('newbusinessproposallist');
Route::post('/newbusinessproposalinsert' ,'Newbusinessproposalcontroller@insert')->name('newbusinessproposalinsert');
Route::post('/newbusinessproposaledit' ,'Newbusinessproposalcontroller@edit')->name('newbusinessproposaledit');
Route::post('/newbusinessproposalupdate' ,'Newbusinessproposalcontroller@update')->name('newbusinessproposalupdate');
Route::post('/newbusinessproposaldelete' ,'Newbusinessproposalcontroller@delete')->name('newbusinessproposaldelete');
Route::post('/newbusinessproposalapprove' ,'Newbusinessproposalcontroller@approve')->name('newbusinessproposalapprove');
Route::get('/newbusinessproposalstatus/{id}/{stasus}','Newbusinessproposalcontroller@status')->name('newbusinessproposalstatus');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('route:cache');
    Artisan::call('clear-compiled');
    Artisan::call('optimize');

});
