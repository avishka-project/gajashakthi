<?php

namespace App\Http\Controllers;

use App\Department;
use App\Employee;
use App\EmployeeAttachment;
use App\JobCategory;
use App\User;
use App\EmployeeDependent;
use App\EmployeeImmigration;
use App\EmployeePicture;
use App\EmploymentStatus;
use App\FingerprintDevice;
use App\Branch;
use App\Commen;
use App\JobTitle;
use App\JobStatus;
use App\Shift;
use App\ShiftType;
use App\Company;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Response;
use Validator;

use Illuminate\Http\Request;
use Session;
use Yajra\Datatables\Datatables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-list', $userPermissions)) {
            abort(403);
        } 

        $lastid = DB::table('employees')
            ->latest()
            ->first();
        $employmentstatus = EmploymentStatus::orderBy('id', 'asc')->get();
        $branch = Branch::orderBy('id', 'asc')->get();
        $title = JobTitle::orderBy('id', 'asc')->get();
        $shift_type = ShiftType::where('deleted', 0)->orderBy('id', 'asc')->get();
        $company = Company::orderBy('id', 'asc')->get();
        $departments = Department::orderBy('id', 'asc')->get();
        $empcategory = Department::orderBy('id', 'asc')->get();
        $employeecat = DB::table('employee_category')->select('employee_category.*')->get();
        if (isset($lastid)) {

            $newid = ($lastid->id + 1);
        } else {

            $newid = '0001';
        }
        $device = FingerprintDevice::orderBy('id', 'asc')->where('status', '=', 1)->get();

        $listpermission = 0;
        $fingerprintpermission = 0;
        $editpermission = 0;
        $deletepermission = 0;

        if (in_array('employee-list', $userPermissions)) {
            $listpermission = 1;
        } 
        if (in_array('finger-print-user-create', $userPermissions)) {
            $fingerprintpermission = 1;
        } 
        if (in_array('employee-edit', $userPermissions)) {
            $editpermission = 1;
        } 
        if (in_array('employee-delete', $userPermissions)) {
            $deletepermission = 1;
        }


        return view('Employee.employeeAdd', compact('newid', 'employmentstatus', 'branch', 'device', 'title', 'shift_type', 'company', 'departments','listpermission','fingerprintpermission','editpermission','deletepermission','employeecat','userPermissions'));
    }


    public function securitystafflist()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-list', $userPermissions)) {
            abort(403);
        }

        $lastid = DB::table('employees')
            ->latest()
            ->first();
        $employmentstatus = EmploymentStatus::orderBy('id', 'asc')->get();
        $branch = Branch::orderBy('id', 'asc')->get();
        $title = JobTitle::orderBy('id', 'asc')->get();
        $shift_type = ShiftType::where('deleted', 0)->orderBy('id', 'asc')->get();
        $company = Company::orderBy('id', 'asc')->get();
        $departments = Department::orderBy('id', 'asc')->get();
        $empcategory = Department::orderBy('id', 'asc')->get();
        $employeecat = DB::table('employee_category')->select('employee_category.*')->get();
        if (isset($lastid)) {

            $newid = ($lastid->id + 1);
        } else {

            $newid = '0001';
        }
        $device = FingerprintDevice::orderBy('id', 'asc')->where('status', '=', 1)->get();

        $listpermission = 0;
        $fingerprintpermission = 0;
        $editpermission = 0;
        $deletepermission = 0;
        
        if (in_array('employee-list', $userPermissions)) {
            $listpermission = 1;
        } 
        if (in_array('finger-print-user-create', $userPermissions)) {
            $fingerprintpermission = 1;
        } 
        if (in_array('employee-edit', $userPermissions)) {
            $editpermission = 1;
        } 
        if (in_array('employee-delete', $userPermissions)) {
            $deletepermission = 1;
        }


        return view('Employee.employeeAddSecuritystaff', compact('newid', 'employmentstatus', 'branch', 'device', 'title', 'shift_type', 'company', 'departments','listpermission','fingerprintpermission','editpermission','deletepermission','employeecat','userPermissions'));
    }

    public function employee_list_dt(Request $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-list', $userPermissions)) {
            abort(403);
        }

        $department = $request->get('department');
        $employee = $request->get('employee');
        $location = $request->get('location');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        $query = DB::table('employees')
            ->leftjoin('employment_statuses', 'employees.emp_status', '=', 'employment_statuses.id')
            ->leftjoin('job_titles', 'employees.emp_job_code', '=', 'job_titles.id')
            ->leftjoin('branches', 'employees.emp_location', '=', 'branches.id')
            ->leftjoin('departments', 'employees.emp_department', '=', 'departments.id')
            ->select('employees.*', 'employment_statuses.emp_status', 'branches.location', 'job_titles.title', 'departments.name as dep_name')
            ->where('deleted', 0);


        if ($department != '') {
            $query->where(['departments.id' => $department]);
        }

        if ($employee != '') {
            $query->where(['employees.emp_id' => $employee]);
        }

        if ($location != '') {
            $query->where(['employees.emp_location' => $location]);
        }

        if ($from_date != '' && $to_date != '') {
            $query->whereBetween('employees.emp_join_date', [$from_date, $to_date]);
        }

        $data = $query->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('emp_id_link', function ($row) {
                return '<a href="viewEmployee/' . $row->id . '">' . $row->emp_id . '</a>';
            })
            ->addColumn('emp_name_link', function ($row) {
                return '<a href="viewEmployee/' . $row->id . '">' . $row->emp_name_with_initial . '</a>';
            })
            ->addColumn('emp_status_label', function ($row) {
                return '<span class="text-success"> ' . $row->emp_status . ' </span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                $commen= new Commen();
                $userPermissions = $commen->Allpermission();
                if (in_array('employee-list', $userPermissions)) {
                    $btn = ' <a style="margin:1px;" data-toggle="tooltip" data-placement="bottom" title="View Employee Details" class="btn btn-outline-dark btn-sm" href="viewEmployee/' . $row->id . '"><i class="far fa-clipboard"></i></a> ';
                }

                if (in_array('finger-print-user-create', $userPermissions)) {
                    $btn .= '<button style="margin:1px;" data-toggle="tooltip" data-placement="bottom" title="Add Employee Fingerprint Details" class="btn btn-outline-primary btn-sm addfp" 
                        id="' . $row->emp_id . '" name="' . $row->emp_name_with_initial . '"><i class="fas fa-sign-in-alt"></i></button>';
                }

                if (in_array('employee-edit', $userPermissions)) {
                    $btn .= '<button style="margin:1px;" data-toggle="tooltip" data-placement="bottom" title="Add Employee User Login Details" class="btn btn-outline-secondary btn-sm adduserlog" 
                        id="' . $row->emp_id . '" name="' . $row->emp_name_with_initial . '"><i class="fas fa-user"></i></button>';
                }

                if (in_array('employee-delete', $userPermissions)) {
                    $btn .= '<button style="margin:1px;" data-toggle="tooltip" data-placement="bottom" title="Delete Employee Details" class="btn btn-outline-danger btn-sm delete" id="' . $row->id . '"><i class="far fa-trash-alt"></i></button>';
                }

                return $btn;
            })
            ->rawColumns(['action', 'emp_id_link', 'emp_name_link', 'emp_status_label'])
            ->make(true);
    }

    public function employeelist()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-list', $userPermissions)) {
            abort(403);
        }
        $device = FingerprintDevice::orderBy('id', 'asc')->where('status', '=', 1)->get();
        $employee = DB::table('employees')
            ->join('employment_statuses', 'employees.emp_status', '=', 'employment_statuses.id')
            ->join('branches', 'employees.emp_location', '=', 'branches.id')
            ->select('employees.*', 'employment_statuses.emp_status', 'branches.branch')
            ->get();

        return view('Employee.employeeList', compact('employee', 'device','userPermissions'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }


    public function usercreate(Request $request)
    {
        $rules = array(
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'string|min:6|confirmed'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $user = new User;
        $user->emp_id = $request->input('userid');
        $user->email = $request->input('email');
        $user->password = bcrypt($request['password']);
        $user->save();

        return response()->json(['success' => 'User Login is successfully Created']);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-create', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $rules = array(
            'emp_id' => 'required|max:10|unique:employees,emp_id',
            'etfno' => 'required',
            'emp_name_with_initial' => 'required|string|max:255',
            'calling_name' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'max:255',
            'lastname' => 'max:255',
            'emp_id_card' => 'required|max:12',
            'emp_mobile' => 'required|max:10',
            'emp_work_telephone' => 'max:10',
            'telephone' => 'max:10',
            'status' => 'required',
            'photograph' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'location' => 'required',
            'employeejob' => 'required',
            'shift' => 'required',
            'employeecompany' => 'required',
            'department' => 'required',
            'employeecategory' => 'required',
//            'no_of_casual_leaves'  => 'required_if:status,2',
//            'no_of_annual_leaves'  => 'required_if:status,2'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        if ($request->hasFile('photograph')) {
            $image = $request->file('photograph');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);


            $employeepicture = new EmployeePicture;
            $employeepicture->emp_id = $request->input('emp_id');
            $employeepicture->emp_pic_filename = $name;
            $employeepicture->save();

        }


        $Employee = new Employee;
        $Employee->emp_id = $request->input('emp_id');
        $Employee->emp_etfno = $request->input('etfno');
        $Employee->emp_name_with_initial = $request->input('emp_name_with_initial');
        $Employee->calling_name = $request->input('calling_name');
        $Employee->emp_first_name = $request->input('firstname');
        $Employee->emp_med_name = $request->input('middlename');
        $Employee->emp_last_name = $request->input('lastname');
        $Employee->emp_national_id = $request->input('emp_id_card');
        $Employee->emp_mobile = $request->input('emp_mobile');
        $Employee->emp_status = $request->input('status');
        $Employee->emp_location = $request->input('location');
        $Employee->emp_job_code = $request->input('employeejob');
        $Employee->emp_shift = $request->input('shift');
        $Employee->emp_company = $request->input('employeecompany');
        $Employee->emp_department = $request->input('department');
        $Employee->no_of_casual_leaves = 0; //$request->input('no_of_casual_leaves');
        $Employee->no_of_annual_leaves = 0; //$request->input('no_of_annual_leaves');
        $Employee->emp_work_telephone = $request->input('emp_work_telephone');
        $Employee->tp1 = $request->input('telephone');
        $Employee->emp_fullname = $request->input('emp_fullname');
        $Employee->emp_category = $request->input('employeecategory');
        $Employee->save();

        return response()->json(['success' => 'Data Added successfully.']);
    }

    private function get_emp_available_leaves($join_date_f, $emp_id)
    {
        //$join_date_f = '2021-12-27';
        $join_year = Carbon::parse($join_date_f)->year;
        $join_month = Carbon::parse($join_date_f)->month;
        $join_date = Carbon::parse($join_date_f)->day;
        $full_date = '2022-' . $join_month . '-' . $join_date;

        $q_data = DB::table('quater_leaves')
            ->where('from_date', '<', $full_date)
            ->where('to_date', '>', $full_date)
            ->first();

        $total_taken_annual_leaves = DB::table('leaves')
            ->where('leaves.emp_id', '=', $emp_id)
            ->where('leaves.leave_type', '=', '1')
            ->sum('no_of_days');

        $leaves = 0;
        if ($join_year == date('y')) {
            $leaves = $q_data->leaves;
        } else {
            $leaves = 14;
        }

        // - taken leaves for current year
        // + leaves from previous year

        return $leaves;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-list', $userPermissions)) {
            abort(403);
        }

        $employee = Employee::where('id', $id)->first();
        $branch = Branch::orderBy('id', 'asc')->get();
        $shift_type = ShiftType::where('deleted', 0)->orderBy('id', 'asc')->get();
        $employmentstatus = EmploymentStatus::orderBy('id', 'asc')->get();
        $jobtitles = JobTitle::orderBy('id', 'asc')->get();
        $company = Company::orderBy('id', 'asc')->get();
        $departments = Department::orderBy('id', 'asc')->get();
        $job_categories = JobCategory::orderBy('id', 'asc')->get();

        $attachments = EmployeeAttachment::where('emp_id', $employee->id)->where('emp_ath_type', 'employee')->get();

        $employee_picture = EmployeePicture::where('emp_id', $employee->emp_id)->latest()->first();
        $employeecat = DB::table('employee_category')->select('employee_category.*')->get();

        return view('Employee.viewEmployee', compact('job_categories', 'employee', 'id', 'jobtitles', 'employmentstatus', 'branch', 'shift_type', 'company', 'departments', 'attachments',
            'employee_picture','employeecat','userPermissions'));
    }

    public function download_file($file)
    {
        //$filepath = public_path('images/filename.JPG');
        $filepath = public_path('attachment/'.$file);
        return Response::download($filepath);
    }

    public function destroy_attachment($id)
    {
        $att = EmployeeAttachment::where('emp_ath_id', $id)->delete();
        return response()->json(['success' => 'The Attachment Successfully Deleted']);
    }

    public function showcontact($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-list', $userPermissions)) {
            abort(403);
        }

        $employee = DB::table('employees')
            ->leftjoin('employee_pictures', 'employees.id', '=', 'employee_pictures.emp_id')
            ->select('employees.*', 'employee_pictures.emp_pic_filename')
            ->where('id', $id)->first();

        return view('Employee.contactDetails', compact('employee', 'id','userPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(REQUEST $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-edit', $userPermissions)) {
            abort(403);
        }

        $id = $request->id;
        $emp_id = $request->emp_id;
        $emp_etfno = $request->emp_etfno;
        $service_no = $request->service_no;
        $emp_name_with_initial = $request->emp_name_with_initial;
        $calling_name = $request->calling_name;
        $firstname = $request->firstname;
        $middlename = $request->middlename;
        $lastname = $request->lastname;
        $fullname = $request->fullname;
        $nicnumber = $request->nicnumber;
        $licensenumber = $request->licensenumber;
        $licenseexpiredate = $request->licenseexpiredate;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $gender = $request->gender;
        $marital_status = $request->marital_status;
        $nationality = $request->nationality;
        $birthday = $request->birthday;
        $joindate = $request->joindate;
        $jobtitle = $request->jobtitle;
        $jobstatus = $request->jobstatus;
        $dateassign = $request->dateassign;
        $location = $request->location;
        $shift = $request->shift;
        $employeecompany = $request->employeecompany;
        $department = $request->department;
        $job_category_id = $request->job_category_id;

        $emergency_contact_person = $request->emergency_contact_person;
        $emergency_contact_tp = $request->emergency_contact_tp;

        $employee = Employee::find($id);

        if ($jobstatus != 2) {
            $errors = array();
            if ($request->no_of_casual_leaves > 0) {
                $errors[] = 'No of casual leaves allows only for permanent employees';
                Session::flash('error', $errors);
                return redirect('viewEmployee/' . $id);
            }

            if ($request->no_of_annual_leaves > 0) {
                $errors[] = 'No of annual leaves allows only for permanent employees';
                Session::flash('error', $errors);
                return redirect('viewEmployee/' . $id);
            }


        } else {
            //check join date and available leaves
//            $errors = array();
//            $available_leaves = $this->get_emp_available_leaves($dateassign, $id);
//            if($request->no_of_annual_leaves > $available_leaves){
//                $errors[] = 'Only '. $available_leaves .' Annual leaves available';
//                Session::flash('error', $errors);
//                return redirect('viewEmployee/' . $id);
//            }
        }

        $employee->emp_id = $emp_id;
        $employee->emp_etfno = $emp_etfno;
        $employee->service_no = $service_no;
        $employee->emp_name_with_initial = $emp_name_with_initial;
        $employee->calling_name = $calling_name;
        $employee->emp_first_name = $firstname;
        $employee->emp_med_name = $middlename;
        $employee->emp_last_name = $lastname;
        $employee->emp_fullname = $fullname;
        $employee->emp_national_id = $nicnumber;
        $employee->emp_drive_license = $licensenumber;
        $employee->emp_license_expire_date = $licenseexpiredate;
        $employee->emp_address = $address1;
        $employee->emp_address_2 = $address2;
        $employee->emp_gender = $gender;
        $employee->emp_marital_status = $marital_status;
        $employee->emp_nationality = $nationality;
        $employee->emp_birthday = $birthday;
        $employee->emp_join_date = $joindate;
        $employee->emp_job_code = $jobtitle;
        $employee->emp_status = $jobstatus;
        $employee->emp_location = $location;
        $employee->emp_shift = $shift;
        $employee->emp_company = $employeecompany;
        $employee->emp_department = $department;
        $employee->job_category_id = $job_category_id;

        $employee->region_id = $request->region_id;;
        $employee->subregion_id = $request->subregion_id;;
        $employee->emptype_id = $request->emptype_id;;
        $employee->emptype_id = $request->employeecategory;

        $employee->emp_work_telephone = $request->input('emp_work_telephone');
        $employee->tp1 = $request->input('telephone');
        $employee->emp_mobile = $request->input('emp_mobile');
        $employee->emp_etfno_a = $request->input('emp_etfno_a');
        $employee->emp_email = $request->input('emp_email');
        $employee->emp_other_email = $request->input('emp_other_email');

        if ($request->input('is_resigned') !== null) {
            $employee->is_resigned = $request->input('is_resigned');
        } else {
            $employee->is_resigned = 0;
        }

//        $employee->no_of_casual_leaves = $request->no_of_casual_leaves;
//        $employee->no_of_annual_leaves = $request->no_of_annual_leaves;

//        $employee->emergency_contact_person = $request->emergency_contact_person;
//        $employee->emergency_contact_tp = $request->emergency_contact_tp;

        $employee->emp_addressT1 = $request->addressT1;
        $employee->emp_address_T2 = $request->addressT2;

        if ($jobstatus == 2) {
            $employee->emp_permanent_date = $dateassign;
        }
        $employee->emp_assign_date = $dateassign;

        $employee->save();


        $jobstatus = new JobStatus;
        $jobstatus->emp_id = $request->input('id');
        $jobstatus->emp_job_status = $request->input('jobstatus');
        $jobstatus->emp_assign_date = $request->input('dateassign');
        $jobstatus->save();

        if ($request->hasFile('photograph')) {
            $image = $request->file('photograph');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);

            $pic = new EmployeePicture;
            $pic->emp_id = $request->input('emp_id');
            $pic->emp_pic_filename = $name;
            $pic->save();
        }

        if ($request->hasFile('empattachment')) {

            $image = $request->file('empattachment');
            $name = time() . '_emp.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/attachment');
            $image->move($destinationPath, $name);


            $employeeattachment = new EmployeeAttachment;
            $id = $request->input('id');
            $employeeattachment->emp_id = $request->input('emp_id');
            $employeeattachment->emp_ath_file_name = $name;
            $employeeattachment->emp_ath_type = 'employee';
            $employeeattachment->save();

        }

        Session::flash('success', 'The Employee Details Successfully Updated');
        return redirect('viewEmployee/' . $id);

    }


    public function editcontact(REQUEST $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-edit', $userPermissions)) {
            abort(403);
        }

        $id = $request->id;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $city = $request->city;
        $province = $request->province;
        $postal_code = $request->postal_code;
        $home_no = $request->home_no;
        $mobile = $request->mobile;
        $birthday = $request->birthday;
        $work_telephone = $request->work_telephone;
        $work_email = $request->work_email;
        $other_email = $request->other_email;

        $employee = Employee::find($id);

        $employee->emp_address = $address1;
        $employee->emp_address_2 = $address2;
        $employee->emp_city = $city;
        $employee->emp_province = $province;
        $employee->emp_postal_code = $postal_code;
        $employee->emp_home_no = $home_no;
        $employee->emp_mobile = $mobile;
        $employee->emp_birthday = $birthday;
        $employee->emp_work_phone_no = $work_telephone;
        $employee->emp_email = $work_email;
        $employee->emp_other_email = $other_email;

        $employee->save();
        Session::flash('success', 'The Employee Contact Details Successfuly Updated');
        return redirect('contactDetails/' . $id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        DB::table('employees')
            ->where('id', $id)
            ->update(['deleted' => 1]);

        Session::flash('success', 'The Employee Details Successfuly Updated');
    }

    public function exportempoloyee()
    {

    }

    public function exportempoloyeereport()
    {

    }

    public function employee_list_sel2(Request $request)
    {
        if ($request->ajax()) {
            $page = Input::get('page');
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            $breeds = \Illuminate\Support\Facades\DB::query()
                ->from('employees')
                ->where('employees.emp_name_with_initial', 'LIKE', '%' . Input::get("term") . '%')
                ->where('employees.calling_name', 'LIKE', '%' . Input::get("term") . '%')
                ->orwhere('service_no', '=',Input::get("term"))
                ->where('deleted', 0)
                ->orderBy('employees.emp_name_with_initial')
                ->skip($offset)
                ->take($resultCount)
                ->get([DB::raw('DISTINCT employees.emp_id as id'),
                    DB::raw('CONCAT(employees.emp_name_with_initial,
                     " - " ,employees.calling_name
                     ) as text')]);

            $count = DB::query()
                ->from('employees')
                ->where('employees.emp_name_with_initial', 'LIKE', '%' . Input::get("term") . '%')
                ->where('employees.calling_name', 'LIKE', '%' . Input::get("term") . '%')
                ->where('deleted', 0)
                ->count();
            $endCount = $offset + $resultCount;
            $morePages = $endCount < $count;

            $results = array(
                "results" => $breeds,
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }

    public function location_list_sel2(Request $request)
    {
        if ($request->ajax()) {
            $page = Input::get('page');
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            $breeds = \Illuminate\Support\Facades\DB::query()
                ->where('branches.location', 'LIKE', '%' . Input::get("term") . '%')
                ->from('branches')
                ->orderBy('branches.location')
                ->skip($offset)
                ->take($resultCount)
                ->get([DB::raw('DISTINCT branches.id as id'), DB::raw('branches.location as text')]);

            $count = DB::query()
                ->where('branches.location', 'LIKE', '%' . Input::get("term") . '%')
                ->from('branches')
                ->orderBy('branches.location')
                ->skip($offset)
                ->take($resultCount)
                ->get([DB::raw('DISTINCT branches.id as id'), DB::raw('branches.location as text')])
                ->count();
            $endCount = $offset + $resultCount;
            $morePages = $endCount < $count;

            $results = array(
                "results" => $breeds,
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }

    public function get_dept_emp_list()
    {
        $dept_id = Input::get('dept');
        $emp_list = DB::table('employees')
            ->where('deleted', 0)
            ->where('emp_department', $dept_id)
            ->orderBy('emp_name_with_initial')
            ->get();
        return response()->json($emp_list, 200);
    }

    public function getsearch(Request $request){
        $id = request('id');

        if (request()->ajax()) {
            $data = DB::table('employees')
                ->select('employees.*')
                ->where('employees.service_no', $id)
                ->get();
        
            if ($data->isEmpty()) {
                return response()->json(['error' => 'No data found for the provided service number.']);
            }
        
            return response()->json(['result' => $data[0]]);
        }
}

public function getserviceno(Request $request) {
    $searchTerm = $request->input('search');
    $emp_category = $request->input('emp_category');

    $matchingData = DB::table('employees')
        ->where(function ($query) use ($searchTerm) {
            $query->where('service_no', '=', $searchTerm);
        })
        ->where('emp_category', $emp_category)
        ->limit(10)
        ->get();

    if ($matchingData->count() > 0) {
        return response()->json($matchingData);
    } 
}
public function getempname(Request $request) {
    $searchTerm = $request->input('search');
    $emp_category = $request->input('emp_category');

    $matchingData = DB::table('employees')
        ->where(function ($query) use ($searchTerm) {
            $query->where('emp_fullname', '=', $searchTerm)
                   ->orWhere('emp_fullname', 'like', '%' . $searchTerm . '%')
                   ->orWhere('emp_name_with_initial', 'like', '%' . $searchTerm . '%');
        })
        ->where('emp_category', $emp_category)
        ->limit(10)
        ->get();

    if ($matchingData->count() > 0) {
        return response()->json($matchingData);
    }
}
public function getempnic(Request $request) {
    $searchTerm = $request->input('search');
    $emp_category = $request->input('emp_category');

    $matchingData = DB::table('employees')
        ->where(function ($query) use ($searchTerm) {
            $query->where('emp_national_id', '=', $searchTerm);
        })
        ->where('emp_category', $emp_category)
        ->limit(10)
        ->get();

    if ($matchingData->count() > 0) {
        return response()->json($matchingData);
    }
}

public function employeetypeget(Request $request){
    $id = request('id');

    if (request()->ajax()) {
        $data = DB::table('employees')
            ->select('employees.*')
            ->where('employees.id', $id)
            ->get();
    
        if ($data->isEmpty()) {
            return response()->json(['error' => 'No data found for the provided service number.']);
        }
    
        return response()->json(['result' => $data[0]]);
    }
}

public function employeeprerecord(Request $request){
    $id = request('id');
    $employeetype = request('employeetype');

    $empcategory='';
    if( $employeetype==1){
        $empcategory="Office Staffs";
    }else{
        $empcategory="Security Staffs";
    }

    if (request()->ajax()) {
        $data = DB::table('employees')
            ->select('employees.*')
            ->where('employees.emp_category', $employeetype)
            ->where('employees.id', '<', $id)
            ->orderBy('employees.id', 'desc') 
            ->first();
    
        if (!$data) {
            return response()->json(['error' => 'No previous data found for the ' . $empcategory]);

        }
    
        return response()->json(['result' => $data]);
    }
}
public function employeenextrecord(Request $request){
    $id = request('id');
    $employeetype = request('employeetype');

    $empcategory='';
    if( $employeetype==1){
        $empcategory="Office Staffs";
    }else{
        $empcategory="Security Staffs";
    }

    if (request()->ajax()) {
        $data = DB::table('employees')
            ->select('employees.*')
            ->where('employees.emp_category', $employeetype)
            ->where('employees.id', '>', $id)
            ->orderBy('employees.id', 'asc')
            ->first(); 
    
        if (!$data) {
            return response()->json(['error' => 'No Next data found for the ' . $empcategory]);

        }
    
        return response()->json(['result' => $data]);
    }
}


public function checkserviceno(Request $request){
    $service_no = request('service_no');

    if (request()->ajax()) {
        $data = DB::table('employees')
            ->select('employees.*')
            ->where('employees.service_no', $service_no)
            ->get();
    
        if ($data->isEmpty()) {
            return response()->json(['error' => 'Service No is available.']);
        }
    
        return response()->json(['success' => 'Service No already exists.']);
    }
}

public function checkemp_etfno(Request $request){
    $emp_etfno = request('emp_etfno');

    if (request()->ajax()) {
        $data = DB::table('employees')
            ->select('employees.*')
            ->where('employees.emp_etfno', $emp_etfno)
            ->get();
    
        if ($data->isEmpty()) {
            return response()->json(['error' => 'EPF No is available.']);
        }
    
        return response()->json(['success' => 'EPF No already exists.']);
    }
}

public function checknic(Request $request){
    $nicnumber = request('nicnumber');

    if (request()->ajax()) {
        $data = DB::table('employees')
            ->select('employees.*')
            ->where('employees.emp_national_id', $nicnumber)
            ->get();
    
        if ($data->isEmpty()) {
            return response()->json(['error' => 'NIC No is available.']);
        }
    
        return response()->json(['success' => 'NIC No already exists.']);
    }
}

}
