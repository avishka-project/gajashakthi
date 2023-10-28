<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\AttendanceClear;
use App\AttendanceEdited;
use App\Department;
use App\FingerprintUser;
use App\FingerprintDevice;
use App\Employee;
use App\employeeWorkRate;
use App\Holiday;
use App\LateAttendance;
use App\Leave;
use App\LeaveDetail;
use App\OtApproved;
use App\Shift;
use App\ShiftType;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Datatables;
use ZKLib;
use Validator;
use Excel;
use Carbon\Carbon;


class AttendanceController extends Controller
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
        $user = Auth::user();
        $permission = $user->can('attendance-sync');
        if (!$permission) {
            abort(403);
        }
        $device = DB::table('fingerprint_devices')
            ->leftjoin('branches', 'fingerprint_devices.location', '=', 'branches.id')
            ->select('fingerprint_devices.*', 'branches.location')
            ->get();
        return view('Attendent.attendance', compact('device'));
    }

    public function attendance_list_ajax(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-sync');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $department = $request->get('department');
        $employee = $request->get('employee');
        $location = $request->get('location');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        // Total records
        $totalRecords_array = DB::select('
            SELECT COUNT(*) as acount
                FROM
                (
                    SELECT COUNT(*)
                    from `attendances` as `at1` 
                    inner join `employees` on `at1`.`uid` = `employees`.`emp_id` 
                    left join `shift_types` on `employees`.`emp_shift` = `shift_types`.`id` 
                    left join `branches` on `at1`.`location` = `branches`.`id` 
                    WHERE at1.deleted_at IS NULL
                    group by `at1`.`uid`, `at1`.`date` 
                    having count(timestamp) < 2
                )t
            ');

        $totalRecords = $totalRecords_array[0]->acount;

        $query1 = 'SELECT COUNT(*) as acount ';
        $query1 .= 'FROM ( ';
        $query1 .= 'SELECT COUNT(*) ';
        $query2 = 'FROM `attendances` as `at1` ';
        $query2 .= 'inner join `employees` on `at1`.`uid` = `employees`.`emp_id` ';
        $query2 .= 'left join `shift_types` on `employees`.`emp_shift` = `shift_types`.`id` ';
        $query2 .= 'left join `branches` on `at1`.`location` = `branches`.`id` ';
        $query2 .= 'left join `departments` on `departments`.`id` = `employees`.`emp_department` ';
        $query2 .= 'WHERE 1 = 1 AND at1.deleted_at IS NULL ';
        //$searchValue = 'Breeder Farm';
        if ($searchValue != '') {
            $query2 .= 'AND ';
            $query2 .= '( ';
            $query2 .= 'employees.emp_id like "' . $searchValue . '%" ';
            $query2 .= 'OR employees.emp_name_with_initial like "' . $searchValue . '%" ';
            $query2 .= 'OR at1.timestamp like "' . $searchValue . '%" ';
            $query2 .= 'OR branches.location like "' . $searchValue . '%" ';
            $query2 .= ') ';
        }

        if ($department != '') {
            $query2 .= 'AND departments.id = "' . $department . '" ';
        }

        if ($employee != '') {
            $query2 .= 'AND employees.emp_id = "' . $employee . '" ';
        }

        if ($location != '') {
            $query2 .= 'AND at1.location = "' . $location . '" ';
        }

        if ($from_date != '' && $to_date != '') {
            $query2 .= 'AND at1.date BETWEEN "' . $from_date . '" AND "' . $to_date . '" ';
        }

        $query6 = 'group by `at1`.`uid`, `at1`.`date` ';
        $query6 .= 'having count(timestamp) < 2 ';
        $query4 = ') t ';
        $query5 = 'LIMIT ' . (string)$start . ' , ' . $rowperpage . ' ';
        $query7 = 'ORDER BY ' . $columnName . ' ' . $columnSortOrder . ' ';

        $totalRecordswithFilter_arr = DB::select($query1 . $query2 . $query6 . $query4);
        $totalRecordswithFilter = $totalRecordswithFilter_arr[0]->acount;

        $query3 = 'select `shift_types`.*, `at1`.*, at1.id as at_id, Max(at1.timestamp) as lasttimestamp, Min(at1.timestamp) as firsttimestamp,
                `employees`.`emp_name_with_initial`, `branches`.`location` as b_location, departments.name as dep_name ';

        $records = DB::select($query3 . $query2 . $query6 . $query7 . $query5);

        $data_arr = array();
        foreach ($records as $record) {

            //get only the date from date
            $date = date('Y-m-d', strtotime($record->date));

            $first_timestamp = date('H:i:s A', strtotime($record->firsttimestamp));
            $last_timestamp = date('H:i:s A', strtotime($record->lasttimestamp));

            $data_arr_i = array(
                "at_id" => $record->at_id,
                "uid" => $record->uid,
                "emp_name_with_initial" => $record->emp_name_with_initial,
                "firsttimestamp" => $first_timestamp,
                "begining_checkout" => $record->begining_checkout,
                "date_row" => $record->date,
                "date" => $date,
                "lasttimestamp" => $last_timestamp,
                "ending_checkout" => $record->ending_checkout,
                "location" => $record->b_location,
                "dep_name" => $record->dep_name
            );

            if ((date('G:i', strtotime($record->firsttimestamp))) < (Carbon::parse($record->begining_checkout))) {
                $data_arr_i["btn_in"] = true;
                $data_arr_i["btn_out"] = false;
            } else {
                $data_arr_i["btn_in"] = false;
                $data_arr_i["btn_out"] = true;
            }

            if(Auth::user()->can('attendance-delete')){
                $data_arr_i["btn_delete"] = true;
            }else{
                $data_arr_i["btn_delete"] = false;
            }

            $data_arr[] = $data_arr_i;
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function getdevicedata(Request $request)
    {
        //dd($request->device);
        $device = FingerprintDevice::where('ip', '=', $request->device)->get();
        $device = DB::table('fingerprint_devices')->where('ip', '=', $request->device)->first();
        $ip = $device->ip;
        $sync_date = $request->sync_date;

        $name = $device->name;

        $name = new ZKLib(
            $ip // '112.135.69.27' //your device IP
        );
        $ret = $name->connect();
        if ($ret) {
            $name->disableDevice();

            $attendance = $name->getAttendance();

            //keep timestamp like sync_date% and remove the rest (applied only for jaya farm)
//            $attendance = array_filter($attendance, function ($item) use ($sync_date) {
//                return strpos($item['timestamp'], $sync_date) !== false;
//            });

            $location = $device->location;
            $serial = $name->serialNumber();
            $deviceserial = substr($serial, strpos($serial, "=") + 1, -1);

            $is_ok = true; 

            foreach ($attendance as $link) {

                $newtimestamp = $link['timestamp'];

                $Attendance = Attendance::firstOrNew(['timestamp' => $link['timestamp'], 'devicesno' => $deviceserial]);
                //$Attendance->id=$id= $link['id'];
                $Attendance->uid = $link['id'];
                $Attendance->emp_id = $link['id'];
                $Attendance->state = $link['state'];
                $Attendance->timestamp = $link['timestamp'];
                $timestamp = $link['timestamp'];
                $Attendance->date = substr($timestamp, 0, -9);
                $Attendance->type = $link['type'];
                $Attendance->devicesno = $deviceserial;
                $Attendance->location = $location;
                $is_ok = $Attendance->save(); 
                $errors = "Something went wrong";

            }

            $res = array(
                "status" => $is_ok,
                "message" => "Attendance data has been imported successfully"
            );

            return response()->json($res);
        }


        $name->enableDevice();
        $name->disconnect();

    }

    //cleardevicedata
    public function cleardevicedata(Request $request)
    {
        //dd($request->device);
        $device = FingerprintDevice::where('ip', '=', $request->device)->get();
        $device = DB::table('fingerprint_devices')->where('ip', '=', $request->device)->first();
        $ip = $device->ip;
        $sync_date = $request->sync_date;

        $name = $device->name;

//        $name = new ZKLib(
//            $ip // '112.135.69.27' //your device IP
//        );

        $location = $device->location;
        $serial = $device->conection_no;
        $deviceserial = substr($serial, strpos($serial, "=") + 1, -1);

        $attendance_clear_data = array(
            'user_id' => Auth::user()->id,
            'device_id' => $device->id,
            'location_id' => $location
        );

        $attendance_clear_data = AttendanceClear::create($attendance_clear_data);

        $res = array(
            "status" => true,
            "message" => "Attendance data has been cleared successfully"
        );
        return response()->json($res);

        die();

//        $ret = $name->connect();
//        if ($ret) {
//            $name->disableDevice();
//            $attendance = $name->clearattendance();
//
//            $location = $device->location;
//            $serial = $name->serialNumber();
//            $deviceserial = substr($serial, strpos($serial, "=") + 1, -1);
//
//            $attendance_clear_data = array(
//                'user_id' => Auth::user()->id,
//                'devicesno' => $deviceserial,
//                'location_id' => $location
//            );
//
//            $attendance_clear_data = AttendanceClear::create($attendance_clear_data);
//
//            $res = array(
//                "status" => true,
//                "message" => "Attendance data has been cleared successfully"
//            );
//            return response()->json($res);
//        }
//
//
//        $name->enableDevice();
//        $name->disconnect();

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'employee' => 'required',
            'attdate' => 'required',
            'atttime' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $employees = DB::table('employees')
            ->join('branches', 'employees.emp_location', '=', 'branches.id')
            ->join('fingerprint_devices', 'branches.id', '=', 'fingerprint_devices.location')
            ->select('fingerprint_devices.sno', 'fingerprint_devices.location')
            ->groupBy('fingerprint_devices.location')
            ->where('employees.emp_id', $request->employee)
            ->get();

        //in time
        $timestamp = $request->attdate . ' ' . $request->atttime;
        $data = array(
            'uid' => $request->employee,
            'state' => 1,
            'timestamp' => $timestamp,
            'date' => $timestampdate = substr($timestamp, 0, -6),
            'approved' => 0,
            'type' => 255,
            'devicesno' => $employees[0]->sno,
            'location' => $employees[0]->location
        );
        $id = DB::table('attendances')->insert($data);

        //off time
        $offTimeStamp = $request->attdate . ' ' . $request->offTime;
        $data = array(
            'uid' => $request->employee,
            'state' => 1,
            'timestamp' => $offTimeStamp,
            'date' => substr($offTimeStamp, 0, -6),
            'approved' => 0,
            'type' => 255,
            'devicesno' => $employees[0]->sno,
            'location' => $employees[0]->location
        );
        $id = DB::table('attendances')->insert($data);

        return response()->json(['success' => 'Attendent Inserted successfully.']);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $data = Attendance::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function attendanceedit()
    {
        $user = Auth::user();
        $permission = $user->can('attendance-create');
        if (!$permission) {
            abort(403);
        }

        return view('Attendent.attendanceedit');
    }

    /**
     * @throws \Exception
     */
    public function attendance_list_for_edit(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-create');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        try {
            $department = $request->get('department');
            $employee = $request->get('employee');
            $location = $request->get('location');
            $from_date = $request->get('from_date');
            $to_date = $request->get('to_date');

            $query = DB::query()
                ->select('at1.*',
                    DB::raw('Min(at1.timestamp) as firsttimestamp'),
                    DB::raw('(CASE 
                        WHEN Min(at1.timestamp) = Max(at1.timestamp) THEN ""  
                        ELSE Max(at1.timestamp)
                        END) AS lasttimestamp'),
                    'employees.emp_name_with_initial',
                    'branches.location',
                    'departments.name as dep_name'
                )
                ->from('attendances as at1')
                ->Join('employees', 'at1.uid', '=', 'employees.emp_id')
                ->leftJoin('branches', 'at1.location', '=', 'branches.id')
                ->leftJoin('departments', 'departments.id', '=', 'employees.emp_department');

            if ($department != '') {
                $query->where(['departments.id' => $department]);
            }

            if ($employee != '') {
                $query->where(['employees.emp_id' => $employee]);
            }

            if ($location != '') {
                $query->where(['at1.location' => $location]);
            }

            if ($from_date != '' && $to_date != '') {
                $query->whereBetween('at1.date', [$from_date, $to_date]);
            }

            $query->where(['at1.deleted_at' => null]);

            $query->where(['approved' => '0'])
                ->groupBy('at1.uid', 'at1.date');

            $data = $query->get();

            return Datatables::of($data)
                ->addIndexColumn()
                //formatted date
                ->editColumn('formatted_date', function ($row) {
                    return date('Y-m-d', strtotime($row->date));
                })
                //first_time_stamp
                ->addColumn('first_time_stamp', function ($row) {
                    $ful_time = $row->firsttimestamp;
                    $time = date('h:i:s A', strtotime($ful_time));
                    return $time;
                })
                //last_time_stamp
                ->addColumn('last_time_stamp', function ($row) {
                    $ful_time = $row->lasttimestamp;
                    $time = date('h:i:s A', strtotime($ful_time));
                    return $time;
                })
                ->addColumn('action', function ($row) {

                    $btn = ' <button type="submit" name="view_button" 
                        style="margin:1px;"
                        uid="' . $row->uid . '" 
                        data-date="' . $row->date . '" 
                        data-name="' . $row->emp_name_with_initial . '"  
                        class="view_button btn btn-outline-dark btn-sm ml-1 "><i class="fas fa-eye"></i></button> ';

                    $user = Auth::user();
                    $permission = $user->can('attendance-edit');
                    if ($permission) {
                        $btn .= ' <button type="submit" name="edit_button" 
                        style="margin:1px;"
                        uid="' . $row->uid . '" 
                        data-date="' . $row->date . '" 
                        data-name="' . $row->emp_name_with_initial . '"  
                        class="edit_button btn btn-outline-primary btn-sm ml-1"><i class="fas fa-pencil-alt"></i></button> ';
                    }

                    $permission = $user->can('attendance-delete');
                    if ($permission) {
                        $btn .= ' <button type="submit" name="delete_button"
                            style="margin:1px;"
                            data-uid="' . $row->uid . '" 
                            data-date="' . $row->date . '" 
                            data-name="' . $row->emp_name_with_initial . '"  
                            class="delete_button btn btn-outline-danger btn-sm ml-1"><i class="fas fa-trash"></i></button> ';
                    }

                    return $btn;
                })
                ->rawColumns(['action',
                    'formatted_date',
                    'first_time_stamp',
                    'last_time_stamp'
                ])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


    }

    public function AttendanceEditBulk()
    {
        $user = Auth::user();
        $permission = $user->can('attendance-edit');
        if (!$permission) {
            abort(403);
        }
        return view('Attendent.attendanceEditBulk');
    }

    public function attendance_list_for_month_edit(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-edit');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $month = $request->month;
        $emp_id = $request->emp;

        $query = DB::query()
            ->select('at1.*',
                DB::raw('Min(at1.timestamp) as firsttimestamp'),
                DB::raw('(CASE 
                        WHEN Min(at1.timestamp) = Max(at1.timestamp) THEN ""  
                        ELSE Max(at1.timestamp)
                        END) AS lasttimestamp'),
                'employees.emp_id')
            ->where(['employees.emp_id' => $emp_id])
            ->where('date', 'like', $month . '%')
            ->where(['at1.deleted_at' => null])
            ->from('attendances as at1')
            ->Join('employees', 'at1.uid', '=', 'employees.emp_id')
            ->groupBy('at1.uid', 'at1.date');;

        $attendances = $query->get();

        //add timestamp->format('Y-m-d\TH:i:s') to each row
        foreach ($attendances as $attendance) {
            $timestamp = Carbon::parse($attendance->firsttimestamp);
            $lasttimestamp = Carbon::parse($attendance->lasttimestamp);
            //var_dump($lasttimestamp);
            //die();
            $attendance->firsttime_rfc = $timestamp->format('Y-m-d\TH:i');
            $attendance->lasttime_rfc = $lasttimestamp->format('Y-m-d\TH:i');
        }


        return response()->json(['attendances' => $attendances, 'status' => true], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-edit');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $rules = array(
            'uid' => 'required',
            'timestamp' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'uid' => $request->uid,
            'timestamp' => $request->timestamp,
            'date' => $attendance->date = $timestampdate = substr($request->timestamp, 0, -9)

        );

        $attendanceedited = new AttendanceEdited;
        $attendanceedited->id = $request->input('uid');
        $attendanceedited->date = $request->input('timestamp');
        $attendanceedited->edited_user_id = $request->input('userid');
        $attendanceedited->save();


        Attendance::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    public function exportattendances()
    {
        $emp_data = DB::table('attendances')
            ->join('employees', 'attendances.uid', '=', 'employees.id')
            ->select('attendances.*', 'employees.*')
            ->get();

        $emp_array[] = array('Employee Id', 'Name', 'TimeStamp', 'Date');
        foreach ($emp_data as $employee) {
            $emp_array[] = array(
                'Employee Id' => $employee->uid,
                'Name' => $employee->emp_first_name,
                'TimeStamp' => $employee->timestamp,
                'Date' => $employee->date

            );
        }
        Excel::create('Employee Data', function ($excel) use ($emp_array) {
            $excel->setTitle('Employee Data');
            $excel->sheet('Employee Data', function ($sheet) use ($emp_array) {
                $sheet->fromArray($emp_array, null, 'A1', false, false);
            });
        })->download('xlsx');
    }


    public function getAttendance(Request $request)
    {


        $data = DB::table('attendances')
            ->select('attendances.*', 'employees.emp_name_with_initial', 'employees.emp_etfno', 'shift_types.begining_checkout', 'shift_types.ending_checkout')
            ->Join('employees', 'attendances.uid', '=', 'employees.emp_id')
            ->leftJoin('shift_types', 'employees.emp_shift', '=', 'shift_types.id')
            ->where([
                ['date', '=', $request->date],
                ['uid', '=', $request->id],

            ])->get();


        return response()->json($data);

        //echo json_encode($data);
    }

    public function attendentUpdateLive(Request $request)
    {
        if ($request->ajax()) {
            $data = array(
                'timestamp' => $request->timestamp,
                'date' => $timestampdate = substr($request->timestamp, 0, -6)
            );

            $employees = DB::table('employees')
                ->join('branches', 'employees.emp_location', '=', 'branches.id')
                ->join('fingerprint_devices', 'branches.id', '=', 'fingerprint_devices.location')
                ->select('fingerprint_devices.sno', 'fingerprint_devices.location')
                ->where('employees.emp_id', $request->userid)
                ->groupBy('fingerprint_devices.location')
                ->get();

            $data = array(
                'uid' => $request->userid,
                'state' => 1,
                'timestamp' => $request->timestamp,
                'date' => $timestampdate = substr($request->timestamp, 0, -8),
                'approved' => 0,
                'type' => 255,
                'devicesno' => $employees[0]->sno,
                'location' => $employees[0]->location
            );
            DB::table('attendances')
                ->where('id', $request->id)
                ->update($data);

            //   return response()->json(['success' => 'Data is successfully updated']);
            echo '<div class="alert alert-success">Attendent Updated</div>';
        }
    }

    public function attendentinsertlive(Request $request)
    {

        if ($request->ajax()) // dd($employees);


        {

            $employees = DB::table('employees')
                ->join('branches', 'employees.emp_location', '=', 'branches.id')
                ->join('fingerprint_devices', 'branches.id', '=', 'fingerprint_devices.location')
                ->select('fingerprint_devices.sno', 'fingerprint_devices.location')
                ->groupBy('fingerprint_devices.location')
                ->get();


            $data = array(
                'uid' => $request->userid,
                'state' => 1,
                'timestamp' => $request->timestamp,
                'date' => $timestampdate = substr($request->timestamp, 0, -6),
                'approved' => 0,
                'type' => 255,
                'devicesno' => $employees[0]->sno,
                'location' => $employees[0]->location
            );
            $id = DB::table('attendances')->insert($data);
            if ($id > 0) {
                echo '<div class="alert alert-success">Attendent Inserted</div>';
            }
        }
    }

    public function attendentdeletelive(Request $request)
    {
        if ($request->ajax()) {
            DB::table('attendances')
                ->where('id', $request->id)
                ->delete();
            echo 'Attendant Details Deleted';
        }
    }

    public function getAttendentChart(Request $request)
    {


        $data = DB::table('attendances')
            ->select('attendances.date', DB::raw('COUNT(attendances.uid) as count'))
            ->groupBy('attendances.date')
            ->limit(30)
            ->orderBy('attendances.date', 'desc')
            ->get();
        return response()->json($data);

    }


    public function getBranchAttendentChart(Request $request)
    {

        $today = Carbon::today();
        // dd($today);
        $data = DB::table('attendances')
            ->join('fingerprint_devices', 'attendances.devicesno', '=', 'fingerprint_devices.sno')
            ->join('branches', 'fingerprint_devices.location', '=', 'branches.id')
            ->select('branches.location', DB::raw('COUNT(attendances.uid) as count'))
            ->groupBy('attendances.devicesno')
            ->where('attendances.date', $today)
            ->limit(20)
            ->get();
        return response()->json($data);

    }

    public function attendanceapprovel()
    {
        $user = Auth::user();
        $permission = $user->can('attendance-approve');
        if(!$permission){
            abort(403);
        }

        return view('Attendent.attendanceapprovel');
    }

    public function attendance_list_for_approve(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-approve');

        if(!$permission){
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        ## Read value
        $location = $request->get('company');
        $department = $request->get('department');
        $month = $request->get('month');

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords_array = DB::select('
            SELECT COUNT(*) as acount
                FROM
                (
                    SELECT COUNT(*)
                    from `attendances` as `at1` 
                    join `employees` on `at1`.`uid` = `employees`.`emp_id`  
                    left join `branches` on `at1`.`location` = `branches`.`id`   
                    left join shift_types ON employees.emp_shift = shift_types.id 
                    WHERE at1.deleted_at IS NULL
                    group by at1.uid 
                )t
            ');

        $totalRecords = $totalRecords_array[0]->acount;

        $query1 = 'SELECT COUNT(*) as acount ';
        $query1 .= 'FROM ( ';
        $query1 .= 'SELECT COUNT(*) ';
        $query2 = 'FROM `attendances` as `at1` ';
        $query2 .= ' join `employees` on `employees`.`emp_id` = `at1`.`uid` ';
        $query2 .= 'left join `branches` on `at1`.`location` = `branches`.`id` ';
        $query2 .= 'left join `departments` on `departments`.`id` = `employees`.`emp_department` ';
        $query2 .= 'left join shift_types ON employees.emp_shift = shift_types.id ';
        $query2 .= 'WHERE 1 = 1 AND at1.deleted_at IS NULL ';
        //$searchValue = 'Breeder Farm';
        if ($searchValue != '') {
            $query2 .= 'AND ';
            $query2 .= '( ';
            $query2 .= 'employees.emp_id like "' . $searchValue . '%" ';
            $query2 .= 'OR employees.emp_name_with_initial like "' . $searchValue . '%" ';
            $query2 .= 'OR at1.timestamp like "' . $searchValue . '%" ';
            $query2 .= 'OR branches.location like "' . $searchValue . '%" ';
            $query2 .= 'OR departments.name like "' . $searchValue . '%" ';
            $query2 .= ') ';
        }

        if ($department != '') {
            $query2 .= 'AND departments.id = "' . $department . '" ';
        }

        if ($location != '') {
            $query2 .= 'AND at1.location = "' . $location . '" ';
        }

        if ($month != '') {
            $query2 .= 'AND at1.date LIKE "' . $month . '%" ';
        }

        $query6 = 'group by at1.uid ';
        $query6 .= ' ';
        $query4 = ') t ';
        $query5 = 'LIMIT ' . (string)$start . ' , ' . $rowperpage . ' ';
        $query7 = 'ORDER BY ' . $columnName . ' ' . $columnSortOrder . ' ';

        $totalRecordswithFilter_arr = DB::select($query1 . $query2 . $query6 . $query4);
        $totalRecordswithFilter = $totalRecordswithFilter_arr[0]->acount;

        // Fetch records
        $query3 = 'select 
            at1.*,
            Max(at1.timestamp) as lasttimestamp,
            Min(at1.timestamp) as firsttimestamp,
            employees.emp_id ,
            employees.emp_name_with_initial ,
            shift_types.onduty_time ,
            shift_types.offduty_time ,
            branches.location as b_location,
            departments.name as dept_name  
              ';

        $records = DB::select($query3 . $query2 . $query6 . $query7 . $query5);
        //error_log($query3.$query2.$query6.$query7.$query5);
        //var_dump(sizeof($records));
        //die();
        $data_arr = array();

        foreach ($records as $record) {

            $work_days = (new \App\Attendance)->get_work_days($record->emp_id, $month);
            $leave_days = (new \App\Attendance)->get_leave_days($record->emp_id, $month);
            $no_pay_days = (new \App\Attendance)->get_no_pay_days($record->emp_id, $month);

            $rec_date = Carbon::parse($record->date)->toDateString();
            $date_c = Carbon::createFromFormat('Y-m-d', $rec_date);
            $monthName = $date_c->format('Y-m');

            $data_arr[] = array(
                "id" => $record->id,
                "uid" => $record->uid,
                "emp_name_with_initial" => $record->emp_name_with_initial,
                "date" => $monthName,
                "firsttimestamp" => $record->firsttimestamp,
                "lasttimestamp" => $record->lasttimestamp,
                "onduty_time" => $record->onduty_time,
                "offduty_time" => $record->offduty_time,
                "work_days" => $work_days,
                "leave_days" => $leave_days,
                "no_pay_days" => $no_pay_days,
                "dept_name" => $record->dept_name,
                "location" => $record->b_location
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function attendance_list_for_bulk_edit(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-edit');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
        ## Read value
        $location = $request->get('company');
        $department = $request->get('department');
        $date = $request->get('date');
        $employee = $request->get('employee');

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords_array = DB::select('
            SELECT COUNT(*) as acount
                FROM
                (
                    SELECT COUNT(*)
                    from `attendances` as `at1` 
                    join `employees` on `at1`.`uid` = `employees`.`emp_id`  
                    left join `branches` on `at1`.`location` = `branches`.`id`   
                    left join shift_types ON employees.emp_shift = shift_types.id 
                    WHERE at1.deleted_at IS NULL
                    group by at1.uid, at1.date
                )t
            ');

        $totalRecords = $totalRecords_array[0]->acount;

        $query1 = 'SELECT COUNT(*) as acount ';
        $query1 .= 'FROM ( ';
        $query1 .= 'SELECT COUNT(*) ';
        $query2 = 'FROM `attendances` as `at1` ';
        $query2 .= ' join `employees` on `employees`.`emp_id` = `at1`.`uid` ';
        $query2 .= 'left join `branches` on `at1`.`location` = `branches`.`id` ';
        $query2 .= 'left join `departments` on `departments`.`id` = `employees`.`emp_department` ';
        $query2 .= 'left join shift_types ON employees.emp_shift = shift_types.id ';
        $query2 .= 'WHERE employees.deleted = 0 AND at1.deleted_at IS NULL ';
        //$searchValue = 'Breeder Farm';
        if ($searchValue != '') {
            $query2 .= 'AND ';
            $query2 .= '( ';
            $query2 .= 'employees.emp_id like "' . $searchValue . '%" ';
            $query2 .= 'OR employees.emp_name_with_initial like "' . $searchValue . '%" ';
            $query2 .= 'OR at1.timestamp like "' . $searchValue . '%" ';
            $query2 .= 'OR branches.location like "' . $searchValue . '%" ';
            $query2 .= 'OR departments.name like "' . $searchValue . '%" ';
            $query2 .= ') ';
        }

        if ($department != '') {
            $query2 .= 'AND departments.id = "' . $department . '" ';
        }

        if ($location != '') {
            $query2 .= 'AND at1.location = "' . $location . '" ';
        }

        if ($date != '') {
            $query2 .= 'AND at1.date = "' . $date . '" ';
        }

        if ($employee != '') {
            $query2 .= 'AND employees.emp_id = "' . $employee . '" ';
        }

        $query6 = 'group by at1.uid, at1.date ';
        $query6 .= ' ';
        $query4 = ') t ';
        $query5 = 'LIMIT ' . (string)$start . ' , ' . $rowperpage . ' ';
        $query7 = 'ORDER BY ' . $columnName . ' ' . $columnSortOrder . ' ';

        $totalRecordswithFilter_arr = DB::select($query1 . $query2 . $query6 . $query4);
        $totalRecordswithFilter = $totalRecordswithFilter_arr[0]->acount;

        // Fetch records
        $query3 = 'select 
            at1.*,
            Max(at1.timestamp) as lasttimestamp,
            Min(at1.timestamp) as firsttimestamp,
            employees.emp_id ,
            employees.emp_name_with_initial ,
            shift_types.onduty_time ,
            shift_types.offduty_time ,
            branches.location as b_location,
            departments.name as dept_name,  
            departments.id as dept_id
              ';

        $records = DB::select($query3 . $query2 . $query6 . $query7 . $query5);
        //error_log($query3.$query2.$query6.$query7.$query5);
        //var_dump(sizeof($records));
        //die();
        $data_arr = array();

        foreach ($records as $record) {

            $rec_date = Carbon::parse($record->date)->toDateString();
            try {
                $date_c = Carbon::createFromFormat('Y-m-d', $rec_date);
            } catch (\Exception $e) {
                return $e->getMessage() . ' ' . $rec_date . ' emp_id : ' . $record->emp_id . ' ' . $record->emp_name_with_initial;
            }

            $monthName = $date_c->format('Y-m');

            $parsed_first_time = Carbon::parse($record->firsttimestamp)->format('Y-m-d H:i');
            $first_time_arr = explode(' ', $parsed_first_time);
            $formatted_first_timestamp = $first_time_arr[0] . "T" . $first_time_arr[1];

            $parsed_last_time = Carbon::parse($record->lasttimestamp)->format('Y-m-d H:i');
            $last_time_arr = explode(' ', $parsed_last_time);
            $formatted_last_timestamp = $last_time_arr[0] . "T" . $last_time_arr[1];

            $data_arr[] = array(
                "id" => $record->id,
                "uid" => $record->uid,
                "emp_name_with_initial" => $record->emp_name_with_initial,
                "month" => $monthName,
                "date" => $record->date,
                "firsttimestamp" => $record->firsttimestamp,
                "formatted_first_timestamp" => $formatted_first_timestamp,
                "lasttimestamp" => $record->lasttimestamp,
                "formatted_last_timestamp" => $formatted_last_timestamp,
                "dept_name" => $record->dept_name,
                "dept_id" => $record->dept_id,
                "location" => $record->b_location
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function AttendanceEditBulkSubmit(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-edit');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $changed_records = $request->changed_records;

        foreach ($changed_records as $cr) {

            if ($cr['existing_time_stamp'] == '' && $cr['time_stamp'] != '') {

                $employee = DB::table('employees')
                    ->join('branches', 'employees.emp_location', '=', 'branches.id')
                    ->join('fingerprint_devices', 'branches.id', '=', 'fingerprint_devices.location')
                    ->select('fingerprint_devices.sno', 'fingerprint_devices.location', 'employees.emp_id', 'employees.emp_name_with_initial')
                    ->groupBy('fingerprint_devices.location')
                    ->where('employees.emp_id', $cr['uid'])
                    ->first();

                $data = array(
                    'emp_id' => $employee->emp_id,
                    'uid' => $employee->emp_id,
                    'state' => 1,
                    'timestamp' => $cr['time_stamp'],
                    'date' => $cr['date'],
                    'approved' => 0,
                    'type' => 255,
                    'devicesno' => $employee->sno,
                    'location' => $employee->location
                );
                $id = DB::table('attendances')->insert($data);

            } else {

                DB::table('attendances')
                    ->where('uid', $cr['uid'])
                    ->where('date', $cr['date'])
                    ->where('timestamp', $cr['existing_time_stamp'])
                    ->update(['timestamp' => $cr['time_stamp']]);

                if ($cr['time_stamp'] == '') {
                    DB::table('attendances')
                        ->where('uid', $cr['uid'])
                        ->where('date', $cr['date'])
                        ->where('timestamp', $cr['time_stamp'])
                        ->delete();
                }
            }
        }

        return response()->json(['status' => true, 'msg' => 'Updated successfully.']);

    }

    public function attendance_update_bulk_submit(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-edit');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $in_time = $request->in_time;
        $out_time = $request->out_time;
        $existing_time_stamp_in = $request->existing_time_stamp_in;
        $existing_time_stamp_out = $request->existing_time_stamp_out;
        $existing_time_stamp_in_rfc = $request->existing_time_stamp_in_rfc;
        $existing_time_stamp_out_rfc = $request->existing_time_stamp_out_rfc;
        $uid = $request->uid;
        $emp_id = $request->employee;
        $date = $request->date;
        $month = $request->month;
        $date_e = $request->date_e;

        for ($i = 0; $i < sizeof($in_time); $i++) {

            $full_date = $month.'-'.$date[$i];

            if (($in_time[$i] != '') && ($in_time[$i] != $existing_time_stamp_in_rfc[$i])) {

                $full_time_in = $in_time[$i];

                if ($existing_time_stamp_in[$i] != '' ) {

                    DB::table('attendances')
                        ->where('uid', $uid[$i])
                        ->where('date', $full_date)
                        ->where('timestamp', $existing_time_stamp_in[$i])
                        ->update(['timestamp' => $full_time_in]);
                } else {
                    $employee = DB::table('employees')
                        ->join('branches', 'employees.emp_location', '=', 'branches.id')
                        ->join('fingerprint_devices', 'branches.id', '=', 'fingerprint_devices.location')
                        ->select('fingerprint_devices.sno', 'fingerprint_devices.location')
                        ->groupBy('fingerprint_devices.location')
                        ->where('employees.emp_id', $emp_id)
                        ->first();

                    $data = array(
                        'emp_id' => $emp_id,
                        'uid' => $emp_id,
                        'state' => 1,
                        'timestamp' => $full_time_in,
                        'date' => $full_date,
                        'approved' => 0,
                        'type' => 255,
                        'devicesno' => $employee->sno,
                        'location' => $employee->location
                    );
                    $id = DB::table('attendances')->insert($data);

                }

            }
        }

        for ($i = 0; $i < sizeof($out_time); $i++) {

            $full_date = $month.'-'.$date[$i];

            if (($out_time[$i] != '') && ($out_time[$i] != $existing_time_stamp_out_rfc[$i])) {

                $full_time_out = $out_time[$i];

                if ($existing_time_stamp_out[$i] != '') {
                    DB::table('attendances')
                        ->where('uid', $uid[$i])
                        ->where('date', $full_date)
                        ->where('timestamp', $existing_time_stamp_out[$i])
                        ->update(['timestamp' => $full_time_out]);
                } else {
                    $employee = DB::table('employees')
                        ->join('branches', 'employees.emp_location', '=', 'branches.id')
                        ->join('fingerprint_devices', 'branches.id', '=', 'fingerprint_devices.location')
                        ->select('fingerprint_devices.sno', 'fingerprint_devices.location')
                        ->groupBy('fingerprint_devices.location')
                        ->where('employees.emp_id', $emp_id)
                        ->first();

                    $data = array(
                        'emp_id' => $emp_id,
                        'uid' => $emp_id,
                        'state' => 1,
                        'timestamp' => $full_time_out,
                        'date' => $full_date,
                        'approved' => 0,
                        'type' => 255,
                        'devicesno' => $employee->sno,
                        'location' => $employee->location
                    );
                    $id = DB::table('attendances')->insert($data);

                }

            }
        }

        return response()->json(['status' => true, 'msg' => 'Updated successfully.']);

    }


    public function getAttendanceApprovel(Request $request)
    {

        // dd($request->id);
        $attendance = DB::query()
            ->select('at1.*', DB::raw('Min(at1.timestamp) as firsttimestamp'), DB::raw('Max(at1.timestamp) as lasttimestamp'), 'employees.emp_name_with_initial', 'fingerprint_devices.location')
            ->from('attendances as at1')
            ->Join('employees', 'at1.uid', '=', 'employees.id')
            ->Join('fingerprint_devices', 'at1.devicesno', '=', 'fingerprint_devices.sno')
            ->groupBy('at1.uid', 'at1.date')
            ->where([
                ['uid', '=', $request->id],
                ['approved', '=', '0'],
            ])
            ->get();


        return response()->json($attendance);


    }

    public function AttendentAprovel(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-approve');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        if ($request->ajax())

            $appval = 1;
        {
            $data = array(
                'approved' => $appval
            );
            DB::table('attendances')
                ->where('uid', $request->emp_id)
                ->update($data);

            $full_month = $request->month;
            //get 1st 4 characters
            $year = substr($full_month, 0, 4);
            //get last 2 characters
            $month = substr($full_month, -2);

            //delete existing records for the month and emp_id from employee_work_rates
            DB::table('employee_work_rates')
                ->where('emp_id', $request->emp_id)
                ->where('work_month', $month)
                ->where('work_year', $year)
                ->delete();

            $form_data = array(
                'emp_id' => $request->emp_id,
                'emp_etfno' => $request->emp_etfno,
                'work_year' => $year,
                'work_month' => $month,
                'work_days' => $request->workdays,
                'leave_days' => $request->leavedate,
                'nopay_days' => $request->nopay,
                'normal_rate_otwork_hrs' => $request->ot,
                'double_rate_otwork_hrs' => $request->dot,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            DB::table('employee_work_rates')->insert($form_data);


            //echo '<div class="alert alert-success">Attendent Details Approved</div>';

            return response()->json(['status' => true, 'msg' => 'Attendance Details Approved']);


        }
    }


    public function getlateAttendance(Request $request)
    {


        $data = DB::table('attendances')
            ->select('attendances.*', 'employees.emp_name_with_initial', 'employees.emp_etfno', 'shift_types.onduty_time', 'shift_types.offduty_time')
            ->Join('employees', 'attendances.uid', '=', 'employees.emp_id')
            ->Join('shift_types', 'employees.emp_shift', '=', 'shift_types.id')
            ->where([
                ['attendances.date', '=', $request->date],
                ['attendances.uid', '=', $request->id],

            ])->get();


        return response()->json($data);

        //echo json_encode($data);
    }

    public function attendentdetails($id)
    {
        $date = Carbon::now();


        $employee = DB::table('employees')
            ->where('emp_id', $id)
            ->select('emp_id', 'emp_name_with_initial', 'emp_etfno')->get();

        $workdays = DB::table('attendances')
            ->where('uid', $id)
            ->whereMonth('attendances.date', $date->month)
            ->groupBy('attendances.uid', 'attendances.date')
            ->count();

        $leave = DB::table('leaves')
            ->where('emp_id', $id)
            ->whereMonth('leaves.leave_from', $date->month)
            ->count();

        $nopay = DB::table('leaves')
            ->where('emp_id', $id)
            ->where('leave_type', 4)
            ->whereMonth('leaves.leave_from', $date->month)
            ->count();

        $ot = DB::table('attendances')
            ->where('uid', $id)
            ->whereDate('date', Carbon::TUESDAY)
            ->count();

        $attendance = DB::query()
            ->select('at1.*', DB::raw('count(*) as work_days'), DB::raw('Max(at1.timestamp) as lasttimestamp'), DB::raw('Min(at1.timestamp) as firsttimestamp'), 'employees.emp_name_with_initial', 'shift_types.onduty_time', 'shift_types.offduty_time')
            ->from('attendances as at1')
            ->Join('employees', 'at1.uid', '=', 'employees.emp_id')
            ->leftJoin('shift_types', 'employees.emp_shift', '=', 'shift_types.id')
            ->groupBy('at1.uid', 'at1.date')
            ->whereMonth('at1.date', $date->month)
            ->where('uid', $id)
            ->get()->toarray();


        //dd($attendance);
        return view('Attendent.attendentdetails', compact('attendance', 'employee', 'date', 'workdays', 'leave', 'nopay', 'ot'));
    }

    public function AttendentAprovelBatch(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-approve');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $location = $request->get('company');
        $department = $request->get('department');
        $month = $request->get('month');

        $query2 = 'FROM `attendances` as `at1` ';
        $query2 .= ' join `employees` on `employees`.`emp_id` = `at1`.`uid` ';
        $query2 .= 'left join `branches` on `at1`.`location` = `branches`.`id` ';
        $query2 .= 'left join `departments` on `departments`.`id` = `employees`.`emp_department` ';
        $query2 .= 'left join shift_types ON employees.emp_shift = shift_types.id ';
        $query2 .= 'WHERE 1 = 1 ';

        if ($department != '') {
            $query2 .= 'AND departments.id = "' . $department . '" ';
        }

        if ($location != '') {
            $query2 .= 'AND at1.location = "' . $location . '" ';
        }

        if ($month != '') {
            $query2 .= 'AND at1.date LIKE "' . $month . '%" ';
        }

        $query6 = 'group by at1.uid ';
        $query6 .= ' ';

        $query3 = 'select 
            at1.*,
            Max(at1.timestamp) as lasttimestamp,
            Min(at1.timestamp) as firsttimestamp,
            employees.emp_id ,
            employees.emp_name_with_initial ,
            shift_types.onduty_time ,
            shift_types.offduty_time ,
            branches.location as b_location,
            departments.name as dept_name,
            emp_department,
            employees.id as emp_auto_id
              ';

        $records = DB::select($query3 . $query2 . $query6);

        foreach ($records as $record) {

            $work_days = (new \App\Attendance)->get_work_days($record->emp_id, $month);
            $leave_days = (new \App\Attendance)->get_leave_days($record->emp_id, $month);
            $no_pay_days = (new \App\Attendance)->get_no_pay_days($record->emp_id, $month);

            $normal_ot_hours = (new \App\OtApproved)->get_ot_hours_monthly($record->emp_id, $month);

            $double_ot_hours = (new \App\OtApproved)->get_double_ot_hours_monthly($record->emp_id, $month);

            $year_rec = Carbon::createFromFormat('Y-m-d H:i:s', $record->date)->year;
            $month_rec = Carbon::createFromFormat('Y-m-d H:i:s', $record->date)->month;

            DB::table('employee_work_rates')
                ->where('emp_id', $record->emp_auto_id)
                ->where('work_year', $year_rec)
                ->where('work_month', $month_rec)
                ->delete();

            $data = array(
                'emp_id' => $record->emp_auto_id,
                'emp_etfno' => $record->emp_id,
                'work_year' => $year_rec,
                'work_month' => $month_rec,
                'work_days' => $work_days,
                'leave_days' => $leave_days,
                'nopay_days' => $no_pay_days, //here
                'normal_rate_otwork_hrs' => $normal_ot_hours,
                'double_rate_otwork_hrs' => $double_ot_hours,
            );
            employeeWorkRate::create($data);

        }

        //return success msg json
        return response()->json(['success' => true, 'msg' => 'Successfully updated']);

    }

    public function AttendentAprovelBatch1(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-approve');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $location = $request->get('company');
        $department = $request->get('department');
        $month = $request->get('month');

        $query2 = 'FROM `attendances` as `at1` ';
        $query2 .= ' join `employees` on `employees`.`emp_id` = `at1`.`uid` ';
        $query2 .= 'left join `branches` on `at1`.`location` = `branches`.`id` ';
        $query2 .= 'left join `departments` on `departments`.`id` = `employees`.`emp_department` ';
        $query2 .= 'left join shift_types ON employees.emp_shift = shift_types.id ';
        $query2 .= 'WHERE 1 = 1 ';

        if ($department != '') {
            $query2 .= 'AND departments.id = "' . $department . '" ';
        }

        if ($location != '') {
            $query2 .= 'AND at1.location = "' . $location . '" ';
        }

        if ($month != '') {
            $query2 .= 'AND at1.date LIKE "' . $month . '%" ';
        }

        $query6 = 'group by at1.uid ';
        $query6 .= ' ';

        $query3 = 'select 
            at1.*,
            Max(at1.timestamp) as lasttimestamp,
            Min(at1.timestamp) as firsttimestamp,
            employees.emp_id ,
            employees.id as emp_auto_id ,
            employees.emp_name_with_initial ,
            employees.emp_shift ,
            employees.emp_department,
            shift_types.onduty_time ,
            shift_types.offduty_time ,
            branches.location as b_location,
            departments.name as dept_name  
              ';

        $records = DB::select($query3 . $query2 . $query6);

        $data = array();

        foreach ($records as $record) {

            $normal_rate_otwork_hrs = 0;
            $double_rate_otwork_hrs = 0;

            $year = Carbon::createFromFormat('Y-m-d H:i:s', $record->date)->year;
            $month = Carbon::createFromFormat('Y-m-d H:i:s', $record->date)->month;
            $year_wildcard = $year . '-' . $month . '-%';

            //get attendances
            $att_query = 'SELECT at1.*, 
                Max(at1.timestamp) as lasttimestamp,
                Min(at1.timestamp) as firsttimestamp,
                employees.emp_shift,  
                employees.id as emp_auto_id,
                employees.emp_name_with_initial,
                shift_types.onduty_time, 
                shift_types.offduty_time
                FROM `attendances`  as `at1`
                join `employees` on `employees`.`emp_id` = `at1`.`uid` 
                left join shift_types ON employees.emp_shift = shift_types.id 
                WHERE at1.emp_id = ' . $record->emp_id . ' AND date LIKE  "' . $year_wildcard . '"
                 group by at1.uid, at1.date
                ';
            $att_records = DB::select($att_query);

            foreach ($att_records as $att_record) {
                //calculate ot for last time stamp
                $double_ot_hours = 0;

                $off_time = Carbon::parse($att_record->lasttimestamp);
                $on_time = Carbon::parse($att_record->firsttimestamp);
                $record_date = Carbon::parse($att_record->date);

                $shift_start = $att_record->onduty_time;
                $shift_end = $att_record->offduty_time;

                $ot_ends_morning = Carbon::parse($record_date->year . '-' . $record_date->month . '-' . $record_date->day . ' ' . $shift_start);
                $ot_starts_evening = Carbon::parse($record_date->year . '-' . $record_date->month . '-' . $record_date->day . ' ' . $shift_end);

                $ot_in_minutes_morning = $on_time->diffInMinutes($ot_ends_morning);
                $ot_hours_morning = 0;
                if ($on_time < $ot_ends_morning) {
                    $ot_hours_morning = $ot_in_minutes_morning / 60;
                }

                $ot_in_minutes_evening = $off_time->diffInMinutes($ot_starts_evening);
                $ot_hours_evening = 0;
                if ($off_time > $ot_starts_evening) {
                    $ot_hours_evening = $ot_in_minutes_evening / 60;
                }

                $ot_hours = $ot_hours_morning + $ot_hours_evening;

                $holiday_check = Holiday::where('date', $record_date->year . '-' . $record_date->month . '-' . $record_date->day)
                    ->first();

                if (!empty($holiday_check)) {
                    if ($holiday_check->work_type == 2) {
                        $double_ot_hours = $ot_hours;
                        $ot_hours = 0;
                    }
                }

                //if driver or after sale department
                $department = Department::where('id', $record->emp_department)->first();
                if ($department->name == 'AFTER SALES SERVICE' || $department->name == 'DRIVERS') {

                    //actual ot start time = shift_end + 1 hour
                    $actual_ot_start_time = Carbon::parse($record_date->year . '-' . $record_date->month . '-' . $record_date->day . ' ' . $shift_end)->addHour();
                    if ($actual_ot_start_time <= $off_time) {
                        $normal_rate_otwork_hrs += $ot_hours;
                        $double_rate_otwork_hrs += $double_ot_hours;
                    }
                }
            }

            $year_rec = Carbon::createFromFormat('Y-m-d H:i:s', $record->date)->year;
            $month_rec = Carbon::createFromFormat('Y-m-d H:i:s', $record->date)->month;
            $rec_wildcard = $year_rec . '-' . $month_rec . '-%';

            $holidays = Holiday::where('date', 'like', $rec_wildcard . '%')->get();

            $filtered_holidays = array();
            foreach ($holidays as $hol) {
                $date = Carbon::parse($hol->date);
                if ($date->dayOfWeek != Carbon::SUNDAY) {
                    array_push($filtered_holidays, $date);
                }
            }

            //get work days
            $query = DB::table('attendances')
                ->select('uid', DB::raw('count(*) as total'));
            if ($month != '') {
                $query->where('date', 'like', $month . '%');
            }
            $query->where('uid', $record->uid)
                ->groupBy(DB::raw('Date(timestamp)'), DB::raw('uid'));
            $work_days = $query->get();


            $month_work_days = 26 - COUNT($filtered_holidays);

            //$leave_days = $month_work_days - COUNT($work_days);

            $query = DB::table('leaves')
                ->select(DB::raw('SUM(no_of_days) as total'))
                ->where('emp_id', $record->emp_id)
                ->where('leave_from', 'like', $month . '%');
            $leave_days_data = $query->get();

            $leave_days = (!empty($leave_days_data[0]->total)) ? $leave_days_data[0]->total : 0;

            $no_pay_days = Leave::where('leave_type', 3)->where('emp_id', $record->emp_id)->sum('no_of_days');

            DB::table('employee_work_rates')
                ->where('emp_id', $record->emp_auto_id)
                ->where('work_year', $year_rec)
                ->where('work_month', $month_rec)
                ->delete();

            $data = array(
                'emp_id' => $record->emp_auto_id,
                'emp_etfno' => $record->emp_id,
                'work_year' => $year_rec,
                'work_month' => $month_rec,
                'work_days' => COUNT($work_days),
                'leave_days' => $leave_days,
                'nopay_days' => $no_pay_days, //here
                'normal_rate_otwork_hrs' => $normal_rate_otwork_hrs,
                'double_rate_otwork_hrs' => $double_rate_otwork_hrs,
            );
            employeeWorkRate::create($data);
        }

        return response()->json(['status' => '1', 'msg' => 'Approved Successfully!']);
    }


    public function copy_att_to_employee_work_rates()
    {
        $attendances = DB::query()
            ->select('e.emp_id', 'e.emp_etfno', 'e.id', 'at1.timestamp')
            ->from('attendances as at1')
            ->join('employees as e', 'e.emp_id', 'at1.uid')
            ->whereBetween('at1.timestamp', ['2021-11-01', '2021-11-31'])
            ->groupBy('e.emp_id')
            ->get();

        $data = array();

        foreach ($attendances as $att) {
            $work_year = Carbon::createFromFormat('Y-m-d H:i:s', $att->timestamp)->year;
            $work_month = Carbon::createFromFormat('Y-m-d H:i:s', $att->timestamp)->month;

            $work_days = DB::table('attendances')
                ->select('uid', DB::raw('count(*) as total'))
                ->whereBetween('timestamp', ['2021-11-01', '2021-11-31'])
                ->where('uid', $att->emp_id)
                ->groupBy(DB::raw('Date(timestamp)'))
                ->get();

            $leave_days = 26 - COUNT($work_days);

            $data[] = array(
                'emp_id' => $att->id,
                'emp_etfno' => $att->emp_id,
                'work_year' => $work_year,
                'work_month' => $work_month,
                'work_days' => COUNT($work_days),
                'leave_days' => $leave_days,
                'nopay_days' => '',
                'normal_rate_otwork_hrs' => '',
                'double_rate_otwork_hrs' => '',
            );

        }

        DB::table('employee_work_rates')->insert($data);
    }

    public function late_attendance_by_time()
    {
        $user = Auth::user();
        $permission = $user->can('late-attendance-create');
        if (!$permission) {
            abort(403);
        }
        return view('Attendent.late_attendance_by_time');
    }

    public function attendance_by_time_report_list(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('late-attendance-create');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        ## Read value
        $department = $request->get('department');
        $company = $request->get('company');
        $location = $request->get('location');
        $date = $request->get('date');
        $late_type = $request->get('late_type');


        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        //late_type
        $late_times = DB::table('late_types')->where('id', $late_type)->first();
        // Total records
        $totalRecords_array = DB::select('
            SELECT COUNT(*) as acount
                FROM
                (
                    SELECT COUNT(*)
                    FROM attendances AS at1 
                    left join `employees` on `at1`.`uid` = `employees`.`emp_id`  
                    left join `branches` on `at1`.`location` = `branches`.`id`  
                    WHERE NOT EXISTS (
                        SELECT * FROM employee_late_attendances AS ela
                        WHERE ela.emp_id = at1.uid
                    )
                    AND at1.deleted_at IS NULL
                    group by `at1`.`uid`, `at1`.`date`  
                )t
            ');


        $totalRecords = $totalRecords_array[0]->acount;

        $query1 = 'SELECT COUNT(*) as acount ';
        $query1 .= 'FROM ( ';
        $query1 .= 'SELECT COUNT(*) ';
        $query2 = 'FROM `attendances` as `at1` ';
        $query2 .= 'join `employees` on `employees`.`emp_id` = `at1`.`uid` ';
        $query2 .= 'left join `branches` on `at1`.`location` = `branches`.`id` ';
        $query2 .= 'left join `departments` on `departments`.`id` = `employees`.`emp_department` ';


        $query2 .= 'WHERE 1 = 1 AND `at1`.`deleted_at` IS NULL ';
        //$searchValue = 'Breeder Farm';
        if ($searchValue != '') {
            $query2 .= 'AND ';
            $query2 .= '( ';
            $query2 .= 'employees.emp_id like "' . $searchValue . '%" ';
            $query2 .= 'OR employees.emp_name_with_initial like "' . $searchValue . '%" ';
            $query2 .= 'OR at1.timestamp like "' . $searchValue . '%" ';
            $query2 .= 'OR companies.name like "' . $searchValue . '%" ';
            $query2 .= 'OR branches.location like "' . $searchValue . '%" ';
            $query2 .= 'OR departments.name like "' . $searchValue . '%" ';
            $query2 .= ') ';
        }

        if ($department != '') {
            $query2 .= 'AND departments.id = "' . $department . '" ';
        }

        if ($company != '') {
            $query2 .= 'AND employees.emp_company = "' . $company . '" ';
        }

        if ($location != '') {
            $query2 .= 'AND at1.location = "' . $location . '" ';
        }

        if ($date != '') {
            $query2 .= 'AND at1.date LIKE "' . $date . ' %" ';
        }

        $query_t = '';

        if ($late_type != '') {
            if ($late_type == 1) {
                $query_t .= 'AND at1.timestamp BETWEEN "' . ($date . ' ' . $late_times->time_from) . '" AND "' . ($date . ' ' . $late_times->time_to) . '" ';
            } else {
                $query_t .= 'AND at1.timestamp > "' . ($date . ' ' . $late_times->time_from) . '" ';
            }
        }

        $query6 = 'group by `at1`.`uid`, `at1`.`date` ';
        $query6 .= ' ';
        $query4 = ') t ';
        $query5 = 'LIMIT ' . (string)$start . ' , ' . $rowperpage . ' ';
        $query7 = 'ORDER BY ' . $columnName . ' ' . $columnSortOrder . ' ';

        $totalRecordswithFilter_arr = DB::select($query1 . $query2 . $query_t . $query6 . $query4);

        $totalRecordswithFilter = $totalRecordswithFilter_arr[0]->acount;

        // Fetch records
        $query3 = 'select  
            at1.*,
            Min(at1.timestamp) as first_checkin,
            Max(at1.timestamp) as lasttimestamp,
            employees.emp_id ,
            employees.emp_name_with_initial ,
            branches.location as b_location,
            branches.id as b_location_id,
            departments.name as dept_name,  
            departments.id as dept_id  
              ';

        $records = DB::select($query3 . $query2 . $query6 . $query7 . $query5);
        //error_log($query3.$query2.$query6.$query7.$query5);
        //var_dump(sizeof($records));
        //die();
        $data_arr = array();

        foreach ($records as $record) {

            $to = \Carbon\Carbon::parse($record->lasttimestamp);
            $from = \Carbon\Carbon::parse($record->timestamp);

            $workhours = gmdate("H:i:s", $to->diffInSeconds($from));
            $rec_date = Carbon::parse($record->date)->toDateString();

            $first_time_stamp = date('h:i:s A', strtotime($record->first_checkin));
            $last_time_stamp = date('h:i:s A', strtotime($record->lasttimestamp));

            if ($first_time_stamp == $last_time_stamp) {
                $last_time_stamp = '';
            }

            if ($late_type != '') {
                if ($late_type == 1) {
                    if ($record->timestamp > $date . ' ' . $late_times->time_from && $record->timestamp < $date . ' ' . $late_times->time_to) {
                        $data_arr[] = array(
                            "id" => $record->id,
                            "uid" => $record->uid,
                            "emp_name_with_initial" => $record->emp_name_with_initial,
                            "date" => $rec_date,
                            "timestamp" => $first_time_stamp,
                            "lasttimestamp" => $last_time_stamp,
                            "workhours" => $workhours,
                            "dept_name" => $record->dept_name,
                            "dept_id" => $record->dept_id,
                            "location" => $record->b_location,
                            "location_id" => $record->b_location_id
                        );
                    }

                } else {
                    if ($record->timestamp > $date . ' ' . $late_times->time_from) {
                        $data_arr[] = array(
                            "id" => $record->id,
                            "uid" => $record->uid,
                            "emp_name_with_initial" => $record->emp_name_with_initial,
                            "date" => $rec_date,
                            "timestamp" => $first_time_stamp,
                            "lasttimestamp" => $last_time_stamp,
                            "workhours" => $workhours,
                            "dept_name" => $record->dept_name,
                            "dept_id" => $record->dept_id,
                            "location" => $record->b_location,
                            "location_id" => $record->b_location_id
                        );
                    }

                }
            } else {
                $data_arr[] = array(
                    "id" => $record->id,
                    "uid" => $record->uid,
                    "emp_name_with_initial" => $record->emp_name_with_initial,
                    "date" => $rec_date,
                    "timestamp" => $first_time_stamp,
                    "lasttimestamp" => $last_time_stamp,
                    "workhours" => $workhours,
                    "dept_name" => $record->dept_name,
                    "dept_id" => $record->dept_id,
                    "location" => $record->b_location,
                    "location_id" => $record->b_location_id
                );
            }
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }


    public function late_types_sel2(Request $request)
    {
        if ($request->ajax()) {
            $page = Input::get('page');
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            $breeds = DB::query()
                ->where('name', 'LIKE', '%' . Input::get("term") . '%')
                ->from('late_types')
                ->orderBy('name')
                ->skip($offset)
                ->take($resultCount)
                ->get([DB::raw('DISTINCT id as id'), DB::raw('name as text')]);

            $count = DB::query()
                ->where('name', 'LIKE', '%' . Input::get("term") . '%')
                ->from('late_types')
                ->orderBy('name')
                ->skip($offset)
                ->take($resultCount)
                ->select([DB::raw('DISTINCT id as id'), DB::raw('name as text')])
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

    public function lateAttendance_mark_as_late(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('late-attendance-create');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $selected_cb = $request->selected_cb;

        if (empty($selected_cb)) {
            return response()->json(['status' => false, 'msg' => 'Select one or more employees']);
        }

        $data_arr = array();
        foreach ($selected_cb as $cr) {

            if ($cr['lasttimestamp'] != '') {
                $data_arr[] = array(
                    'attendance_id' => $cr['id'],
                    'emp_id' => $cr['uid'],
                    'date' => $cr['date'],
                    'check_in_time' => $cr['timestamp'],
                    'check_out_time' => $cr['lasttimestamp'],
                    'working_hours' => $cr['workhours'],
                    'created_by' => Auth::id(),
                );

                DB::table('employee_late_attendances')
                    ->where('attendance_id', $cr['id'])
                    ->where('emp_id', $cr['uid'])
                    ->where('date', $cr['date'])
                    ->where('check_in_time', $cr['timestamp'])
                    ->where('check_out_time', $cr['lasttimestamp'])
                    ->delete();
            }
        }

        DB::table('employee_late_attendances')->insert($data_arr);

        return response()->json(['status' => true, 'msg' => 'Updated successfully.']);
    }

    public function late_attendance_by_time_approve()
    {
        $user = Auth::user();
        $permission = $user->can('late-attendance-approve');
        if (!$permission) {
            abort(403);
        }

        $leave_types = DB::table('leave_types')->get();
        return view('Attendent.late_attendance_by_time_approve', compact('leave_types'));
    }

    public function attendance_by_time_approve_report_list(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('late-attendance-approve');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        ## Read value
        $department = $request->get('department');
        $company = $request->get('company');
        $location = $request->get('location');
        $date = $request->get('date');


        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords_array = DB::select('
            SELECT COUNT(*) as acount
                FROM
                (
                    SELECT COUNT(*)
                    from `employee_late_attendances` as `ela` 
                    left join attendances as at1 on `at1`.`id` = `ela`.`attendance_id`  
                    left join `employees` on `at1`.`uid` = `employees`.`emp_id`  
                    left join `branches` on `at1`.`location` = `branches`.`id`
                    WHERE ela.is_approved = 0
                    group by `at1`.`uid`, `at1`.`date`  
                )t
            ');

        $totalRecords = $totalRecords_array[0]->acount;

        $query1 = 'SELECT COUNT(*) as acount ';
        $query2 = 'FROM `employee_late_attendances` as `ela` ';
        $query2 .= 'left join attendances as at1 on at1.`id` = `ela`.`id` ';
        $query2 .= 'join `employees` on `employees`.`emp_id` = `ela`.`emp_id` ';
        $query2 .= 'left join `branches` on `at1`.`location` = `branches`.`id` ';
        $query2 .= 'left join `departments` on `departments`.`id` = `employees`.`emp_department` ';
        $query2 .= 'WHERE 1 = 1 and ela.is_approved = 0 ';
        //$searchValue = 'Breeder Farm';
        if ($searchValue != '') {
            $query2 .= 'AND ';
            $query2 .= '( ';
            $query2 .= 'employees.emp_id like "' . $searchValue . '%" ';
            $query2 .= 'OR employees.emp_name_with_initial like "' . $searchValue . '%" ';
            $query2 .= 'OR ela.timestamp like "' . $searchValue . '%" ';
            $query2 .= 'OR companies.name like "' . $searchValue . '%" ';
            $query2 .= 'OR branches.location like "' . $searchValue . '%" ';
            $query2 .= 'OR departments.name like "' . $searchValue . '%" ';
            $query2 .= ') ';
        }

        if ($department != '') {
            $query2 .= 'AND departments.id = "' . $department . '" ';
        }

        if ($company != '') {
            $query2 .= 'AND employees.emp_company = "' . $company . '" ';
        }

        if ($location != '') {
            $query2 .= 'AND at1.location = "' . $location . '" ';
        }

        if ($date != '') {
            $query2 .= 'AND ela.date = "' . $date . '" ';
        }

        $query6 = ' ';
        $query6 .= ' ';

        $query5 = 'LIMIT ' . (string)$start . ' , ' . $rowperpage . ' ';
        $query7 = 'ORDER BY ' . $columnName . ' ' . $columnSortOrder . ' ';

        //error_log($query1.$query2.$query6);

        $totalRecordswithFilter_arr = DB::select($query1 . $query2 . $query6);
        $totalRecordswithFilter = $totalRecordswithFilter_arr[0]->acount;

        // Fetch records
        $query3 = 'select ela.*,   
            employees.emp_id ,
            employees.emp_name_with_initial ,
            branches.location as b_location,
            branches.id as b_location_id,
            departments.name as dept_name,  
            departments.id as dept_id  
              ';

        $records = DB::select($query3 . $query2 . $query6 . $query7 . $query5);
        //error_log($query3.$query2.$query6.$query7.$query5);
        //var_dump(sizeof($records));
        //die();
        $data_arr = array();

        foreach ($records as $record) {

            $data_arr[] = array(
                "id" => $record->id,
                "emp_name_with_initial" => $record->emp_name_with_initial,
                "date" => $record->date,
                "check_in_time" => $record->check_in_time,
                "check_out_time" => $record->check_out_time,
                "working_hours" => $record->working_hours,
                "dept_name" => $record->dept_name,
                "dept_id" => $record->dept_id,
                "location" => $record->b_location,
                "location_id" => $record->b_location_id,
                "is_approved_int" => $record->is_approved,
                "is_approved" => ($record->is_approved == 0) ? 'No' : 'Yes',
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function lateAttendance_mark_as_late_approve(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('late-attendance-approve');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $selected_cb = $request->selected_cb;
        $leave_type = $request->leave_type;

        if (empty($selected_cb)) {
            return response()->json(['status' => false, 'msg' => 'Select one or more employees']);
        }

        $id_arr = array();
        $date = '';
        foreach ($selected_cb as $cr) {
            $date = $cr['date'];
            array_push($id_arr, $cr['id']);

            DB::table('employee_late_attendances')
                ->where('id', $cr['id'])
                ->update(['is_approved' => 1]);

            $emp_data = DB::table('employee_late_attendances')
                ->find($cr['id']);

            //count this month leaves and to leaves table
            $d_count = DB::table('employee_late_attendances')
                ->where('date', $emp_data->date)
                ->where('emp_id', $emp_data->emp_id)
                ->count();

            switch (true) {
                case ($d_count == 1 || $d_count == 2):
                    //add short leave
                    $half_short = 0.25;
                    break;
                default:
                    //add half day
                    $half_short = 0.5;
            }

            $leave = new Leave;
            $leave->emp_id = $emp_data->emp_id;
            $leave->leave_type = $leave_type;
            $leave->leave_from = $emp_data->date;
            $leave->leave_to = $emp_data->date;
            $leave->no_of_days = $half_short;
            $leave->half_short = $half_short;
            $leave->reson = 'Late';
            $leave->comment = '';
            $leave->emp_covering = '';
            $leave->leave_approv_person = Auth::id();
            $leave->status = 'Approved';
            $leave->save();

//            $users = DB::table('leave_details')
//                ->where('emp_id', $emp_data->emp_id)
//                ->count();
//
//            if ($users == 0) {
//                $leavedetails = new LeaveDetail;
//                $leavedetails->emp_id = $emp_data->emp_id;
//                $leavedetails->leave_type = 2;
//                $assign_leave = $request->input('assign_leave');
//                $total_leave = $assign_leave - $diff_days;
//                $leavedetails->total_leave = $total_leave;
//                $leavedetails->save();
//
//            } else {
//                DB::table('leave_details')
//                    ->where('emp_id', $request->employee)
//                    ->where('leave_type', $request->leavetype)
//                    ->decrement('total_leave', $diff_days);
//            }

        }

//        DB::table('employee_late_attendances')
//            ->whereNotIn('id', $id_arr)
//            ->where('date', $date)
//            ->where('is_approved', 0)
//            ->delete();

        return response()->json(['status' => true, 'msg' => 'Updated successfully.']);

    }

    public function attendance_add_bulk_submit(Request $request)
    {

        $rules = array(
            'employee' => 'required',
            'month' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $emp_id = $request->employee;
        $month = $request->month;

        $in_time = $request->in_time;
        $out_time = $request->out_time;
        $date = $request->date;

        $employees = DB::table('employees')
            ->join('branches', 'employees.emp_location', '=', 'branches.id')
            ->join('fingerprint_devices', 'branches.id', '=', 'fingerprint_devices.location')
            ->select('fingerprint_devices.sno', 'fingerprint_devices.location')
            ->groupBy('fingerprint_devices.location')
            ->where('employees.emp_id', $request->employee)
            ->get();

        for ($i = 0; $i < count($date); $i++) {

            $in_timestamp = $month . '-' . $date[$i] . ' ' . $in_time[$i];
            $out_timestamp = $month . '-' . $date[$i] . ' ' . $out_time[$i];

            if ($in_time[$i] != '') {
                $data = array(
                    'emp_id' => $emp_id,
                    'uid' => $request->employee,
                    'state' => 1,
                    'timestamp' => $in_timestamp,
                    'date' => substr($in_timestamp, 0, -6),
                    'approved' => 0,
                    'type' => 255,
                    'devicesno' => $employees[0]->sno,
                    'location' => $employees[0]->location
                );
                $id = DB::table('attendances')->insert($data);
            }

            if ($out_time[$i] != '') {
                $data = array(
                    'emp_id' => $emp_id,
                    'uid' => $request->employee,
                    'state' => 1,
                    'timestamp' => $out_timestamp,
                    'date' => substr($out_timestamp, 0, -6),
                    'approved' => 0,
                    'type' => 255,
                    'devicesno' => $employees[0]->sno,
                    'location' => $employees[0]->location
                );
                $id = DB::table('attendances')->insert($data);
            }

        }

        return response()->json(['msg' => 'Attendances Inserted successfully.', 'status' => true]);

    }

    public function attendance_add_dept_wise_submit(Request $request)
    {

        $rules = array(
            'department' => 'required',
            'date' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $date = $request->date;

        $emp_id = $request->emp_id;
        $in_time = $request->in_time;
        $out_time = $request->out_time;

        for ($i = 0; $i < count($emp_id); $i++) {

            $employees = DB::table('employees')
                ->join('branches', 'employees.emp_location', '=', 'branches.id')
                ->join('fingerprint_devices', 'branches.id', '=', 'fingerprint_devices.location')
                ->select('fingerprint_devices.sno', 'fingerprint_devices.location')
                ->groupBy('fingerprint_devices.location')
                ->where('employees.emp_id', $emp_id[$i])
                ->get();

            if ($in_time[$i] != '') {
                $in_timestamp = $in_time[$i];

                $data = array(
                    'emp_id' => $emp_id[$i],
                    'uid' => $emp_id[$i],
                    'state' => 1,
                    'timestamp' => $in_timestamp,
                    'date' => $date,
                    'approved' => 0,
                    'type' => 255,
                    'devicesno' => $employees[0]->sno,
                    'location' => $employees[0]->location
                );
                $id = DB::table('attendances')->insert($data);
            }

            if ($out_time[$i] != '') {

                $out_timestamp = $out_time[$i];

                $data = array(
                    'emp_id' => $emp_id[$i],
                    'uid' => $emp_id[$i],
                    'state' => 1,
                    'timestamp' => $out_timestamp,
                    'date' => $date,
                    'approved' => 0,
                    'type' => 255,
                    'devicesno' => $employees[0]->sno,
                    'location' => $employees[0]->location
                );
                $id = DB::table('attendances')->insert($data);
            }

        }

        return response()->json(['msg' => 'Attendances Inserted successfully.', 'status' => true]);

    }

    public function late_attendances_all(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('late-attendance-list');
        if (!$permission) {
            abort(403);
        }
        return view('Attendent.late_attendance_all');
    }

    public function late_attendance_list_approved(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('late-attendance-list');
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission.']);
        }

        $department = $request->get('department');
        $employee = $request->get('employee');
        $location = $request->get('location');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        $query = DB::query()
            ->select('ela.*',
                'employees.emp_name_with_initial',
                'branches.location',
                'departments.name as dep_name')
            ->from('employee_late_attendances as ela')
            ->Join('employees', 'ela.emp_id', '=', 'employees.emp_id')
            ->leftJoin('attendances as at1', 'at1.id', '=', 'ela.attendance_id')
            ->leftJoin('branches', 'at1.location', '=', 'branches.id')
            ->leftJoin('departments', 'departments.id', '=', 'employees.emp_department');

        if ($department != '') {
            $query->where(['departments.id' => $department]);
        }

        if ($employee != '') {
            $query->where(['employees.emp_id' => $employee]);
        }

        if ($location != '') {
            $query->where(['at1.location' => $location]);
        }

        if ($from_date != '' && $to_date != '') {
            $query->whereBetween('ela.date', [$from_date, $to_date]);
        }

        $data = $query->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $btn = '';

                $permission = Auth::user()->can('late-attendance-delete');
                if ($permission) {
                    $btn = ' <button type="button" 
                        name="delete_button"
                        title="Delete"
                        data-id="' . $row->id . '"  
                        class="view_button btn btn-danger btn-sm delete_button"><i class="fas fa-trash"></i></button> ';
                }

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * @throws \Exception
     */
    public function destroy_late_attendacne($id)
    {
        $permission = Auth::user()->can('late-attendance-delete');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
        $data = LateAttendance::findOrFail($id);
        $data->delete();
    }

    public function get_attendance_monthly_summery_by_emp_id()
    {

        $emp_id = request('emp_id');
        $month = request('month');

        $employee = DB::table('employees')
            ->where('emp_id', $emp_id)
            ->select('emp_id', 'emp_name_with_initial', 'emp_etfno')->get();

        $work_days = (new \App\Attendance)->get_work_days($emp_id, $month);

        $leave_days = (new \App\Attendance)->get_leave_days($emp_id, $month);

        $no_pay_days = (new \App\Attendance)->get_no_pay_days($emp_id, $month);

        $ot_hours = (new \App\Attendance)->get_ot_hours($emp_id, $month);

        $normal_ot_hours = $ot_hours['normal_rate_otwork_hrs'];
        $double_ot_hours = $ot_hours['double_rate_otwork_hrs'];

        $attendances = (new \App\Attendance)->get_attendance_details($emp_id, $month);

        $data = array(
            'employee' => $employee,
            'work_days' => $work_days,
            'leave_days' => $leave_days,
            'no_pay_days' => $no_pay_days,
            'normal_ot_hours' => $normal_ot_hours,
            'double_ot_hours' => $double_ot_hours,
            'attendances' => $attendances
        );

        return response()->json($data);


    }

    public function incomplete_attendances()
    {
        $user = Auth::user();
        $permission = $user->can('incomplete-attendance-list');
        if (!$permission) {
            abort(403);
        }
        return view('Attendent.incomplete_attendances');
    }

    //get_incomplete_attendance_by_employee_data
    public function get_incomplete_attendance_by_employee_data(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('incomplete-attendance-list');
        if (!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $department = Request('department');
        $employee = Request('employee');
        $location = Request('location');
        $from_date = Request('from_date');
        $to_date = Request('to_date');

        $dept_sql = "SELECT * FROM departments WHERE 1 = 1 ";

        if ($department != '') {
            $dept_sql .= ' AND id = "' . $department . '" ';
        }

        if ($location != '') {
            $dept_sql .= 'AND company_id = "' . $location . '" ';
        }

        $departments = DB::select($dept_sql);

        $data_arr = array();
        $not_att_count = 0;

        foreach ($departments as $department_) {

            $query3 = 'select   
            employees.emp_id ,
            employees.emp_name_with_initial ,
            employees.emp_etfno,
            branches.location as b_location,
            departments.name as dept_name,
            departments.id as dept_id    
              ';

            $query3 .= 'from employees ';
            $query3 .= 'left join `branches` on `employees`.`emp_location` = `branches`.`id` ';
            $query3 .= 'left join `departments` on `employees`.`emp_department` = `departments`.`id` ';
            $query3 .= 'where 1 = 1 AND employees.deleted = 0 ';

            $query3 .= 'AND departments.id = "' . $department_->id . '" ';

            if ($employee != '') {
                $query3 .= 'AND employees.emp_id = "' . $employee . '" ';
            }

            $query3 .= 'order by employees.emp_id asc ';

            $employees = DB::select($query3);

            foreach ($employees as $record) {

                //dates of the month between from and to date
                $period = CarbonPeriod::create($from_date, $to_date);

                foreach ($period as $date) {
                    $f_date = $date->format('Y-m-d');

                    //check this is not a holiday
                    $holiday_check = Holiday::where('date', $f_date)->first();

                    if (empty($holiday_check)) {

                        //check leaves from_date to date and emp_id is not a leave
                        $leave_check = Leave::where('emp_id', $record->emp_id)
                            ->where('leave_from', '<=', $f_date)
                            ->where('leave_to', '>=', $f_date)->first();

                        if (empty($leave_check)) {

                            $sql = " SELECT *,
                                Min(attendances.timestamp) as first_checkin,
                                Max(attendances.timestamp) as lasttimestamp
                                FROM attendances WHERE uid = '" . $record->emp_id . "' AND deleted_at IS NULL ";

                            $sql .= 'AND date LIKE "' . $f_date . '%" ';

                            $sql .= 'GROUP BY uid, date ';
                            $sql .= 'ORDER BY date DESC ';

                            $attendances = DB::select($sql);

                            if (empty($attendances)) {
                                $data_arr[$department_->id][$record->emp_id][$not_att_count]['emp_id'] = $record->emp_id;
                                $data_arr[$department_->id][$record->emp_id][$not_att_count]['emp_name_with_initial'] = $record->emp_name_with_initial;
                                $data_arr[$department_->id][$record->emp_id][$not_att_count]['etf_no'] = $record->emp_etfno;
                                $data_arr[$department_->id][$record->emp_id][$not_att_count]['b_location'] = $record->b_location;
                                $data_arr[$department_->id][$record->emp_id][$not_att_count]['dept_name'] = $record->dept_name;
                                $data_arr[$department_->id][$record->emp_id][$not_att_count]['dept_id'] = $record->dept_id;
                                $data_arr[$department_->id][$record->emp_id][$not_att_count]['date'] = $f_date;
                                $data_arr[$department_->id][$record->emp_id][$not_att_count]['timestamp'] = '-';
                                $data_arr[$department_->id][$record->emp_id][$not_att_count]['lasttimestamp'] = '-';
                                $data_arr[$department_->id][$record->emp_id][$not_att_count]['workhours'] = '-';
                                $data_arr[$department_->id][$record->emp_id][$not_att_count]['location'] = $record->b_location;

                                $not_att_count++;
                            }

                        }// leave check if

                    }//holiday if end

                }// period loop

            }//employees loop


        }//departments loop

        $department_id = 0;

        $html = '<div class="row mb-1"> 
                    <div class="col-md-4"> 
                    </div>
                    
                    <div class="col-md-4"> 
                    </div>
                     
                </div>';
        $html .= '<table class="table table-sm table-hover" id="attendance_report_table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th> </th>';
        $html .= '<th>ETF No</th>';
        $html .= '<th>Name</th>';
        $html .= '<th>Department</th>';
        $html .= '<th>Date</th>';
        $html .= '<th>Check In Time</th>';
        $html .= '<th>Check Out Time</th>';
        $html .= '<th>Work Hours</th>';
        $html .= '<th>Location</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($data_arr as $dept_key => $department_data) {

            //if department_id is not equal to the previous department_id
            if ($department_id != $dept_key) {
                $department_id = $dept_key;
                $department_name = Department::query()->where('id', $department_id)->first()->name;
                $html .= '<tr>';
                $html .= '<td colspan="8" style="background-color: #f5f5f5;"> <strong> ' . $department_name . '</strong> </td>';
                $html .= '</tr>';
            }

            foreach ($department_data as $emp_data) {

                foreach ($emp_data as $attendance) {

                    $tr = '<tr>';

                    $html .= $tr;
                    $html .= '<td> 
                                <input type="checkbox" class="checkbox_attendance" name="checkbox[]" value="' . $attendance['etf_no'] . '"
                                    data-etf_no="' . $attendance['etf_no'] . '" 
                                    data-date = "' . $attendance['date'] . '" 
                                 />
                                </td>';
                    $html .= '<td>' . $attendance['etf_no'] . '</td>';
                    $html .= '<td>' . $attendance['emp_name_with_initial'] . '</td>';
                    $html .= '<td>' . $attendance['dept_name'] . '</td>';
                    $html .= '<td>' . $attendance['date'] . '</td>';
                    $html .= '<td>' . $attendance['first_checkin'] . '</td>';
                    $html .= '<td>' . $attendance['lasttimestamp'] . '</td>';
                    $html .= '<td>' . $attendance['workhours'] . '</td>';
                    $html .= '<td>' . $attendance['location'] . '</td>';
                    $html .= '</tr>';
                    $department_id = $attendance['dept_id'];

                }

            }

        }

        $html .= '</tbody>';
        $html .= '</table>

                <div class="row mt-3"> 
                    <div class="col-md-12"> 
                        <button type="button" class="btn btn-primary btn-sm float-right" id="btn_mark_as_no_pay">Mark as NO Pay Leave</button>
                    </div>  
                </div>';


        //return json response
        echo $html;

    }

    public function mark_as_no_pay(Request $request)
    {

        $checked = $request->checked;

        foreach ($checked as $ch) {

            $data = array(
                'emp_id' => $ch['etf_no'],
                'leave_type' => '3',
                'leave_from' => $ch['date'],
                'leave_to' => $ch['date'],
                'no_of_days' => '1',
                'half_short' => '0',
                'reson' => 'No Pay Leave',
                'comment' => 'No Pay Leave',
                'emp_covering' => '0',
                'leave_approv_person' => Auth::user()->id,
                'status' => 'Approved',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            Leave::query()->insert($data);

        }

        return response()->json(['success' => 'Leaves marked as No Pay Leave']);

    }

    public function ot_approve(Request $request)
    {
        $permission = Auth::user()->can('ot-approve');
        if(!$permission){
            abort(403);
        }

        return view('Attendent.ot_approve');
    }

    public function get_ot_details(Request $request)
    {
        $permission = Auth::user()->can('ot-approve');
        if(!$permission){
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $department = Request('department');
        $employee = Request('employee');
        $location = Request('location');
        $from_date = Request('from_date');
        $to_date = Request('to_date');

        //sub query to get this month date and group by
        //left join where above date = min and max

        $sql = "at1.*,
                Min(at1.timestamp) as first_checkin,
                Max(at1.timestamp) as lasttimestamp, 
                employees.id as emp_auto_id
                FROM `attendances`  as `at1`
                join `employees` on `employees`.`emp_id` = `at1`.`uid`
                GROUP BY at1.uid, at1.date 
        ";


        $sql = "SELECT *,
                (SELECT MIN(date) FROM `attendance` WHERE `attendance`.`emp_id` = `attendance`.`emp_id` AND `attendance`.`date` BETWEEN '$from_date' AND '$to_date') as min_date,
                (SELECT MAX(date) FROM `attendance` WHERE `attendance`.`emp_id` = `attendance`.`emp_id` AND `attendance`.`date` BETWEEN '$from_date' AND '$to_date') as max_date
                FROM `attendance`
                WHERE `attendance`.`emp_id` = '$employee' AND `attendance`.`date` BETWEEN '$from_date' AND '$to_date'
                GROUP BY `attendance`.`emp_id`";


        $sql = "SELECT at1.*,  
                Min(at1.timestamp) as first_checkin,
                Max(at1.timestamp) as lasttimestamp,
                employees.emp_shift,
                employees.id as emp_auto_id,
                employees.emp_name_with_initial,
                employees.emp_department,
                shift_types.onduty_time,
                shift_types.offduty_time,
                shift_types.saturday_onduty_time,
                shift_types.saturday_offduty_time,
                shift_types.shift_name,
                branches.location as b_location,
                departments.name as dept_name
                FROM `attendances`  as `at1`
                join `employees` on `employees`.`emp_id` = `at1`.`uid`
                left join shift_types ON employees.emp_shift = shift_types.id
                left join branches ON at1.location = branches.id
                left join departments ON employees.emp_department = departments.id
                WHERE 1 = 1";

        if ($department != '') {
            $sql .= " AND employees.emp_department = '$department'";
        }

        if ($employee != '') {
            $sql .= " AND employees.emp_id = '$employee'";
        }

        if ($location != '') {
            $sql .= " AND at1.location = '$location'";
        }

        if ($from_date != '') {
            $sql .= " AND at1.date >= '$from_date'";
        }

        if ($to_date != '') {
            $sql .= " AND at1.date <= '$to_date'";
        }

        $sql .= " GROUP BY at1.uid, at1.date";

        $attendance_data = DB::select($sql);

        //dd($sql);

        $ot_data = array();

        foreach ($attendance_data as $att) {

            $emp_id = $att->uid;
            $date = $att->date;

            $ot_hours = (new \App\Attendance)->get_ot_hours_by_date($emp_id, $att->lasttimestamp, $att->first_checkin, $date, $att->onduty_time, $att->offduty_time, $att->emp_department);

            //if ot_breakdown is a key in the array
            if (array_key_exists('ot_breakdown', $ot_hours)) {

                $ot_breakdown = $ot_hours['ot_breakdown'];
                $normal_rate_otwork_hrs = $ot_hours['normal_rate_otwork_hrs'];
                $double_rate_otwork_hrs = $ot_hours['double_rate_otwork_hrs'];

                //push ot_breakdown to ot_data
                if (!empty($ot_breakdown)) {
                    array_push($ot_data, $ot_breakdown);
                }

            }

        }

        return response()->json(['ot_data' => $ot_data]);

    }

    public function ot_approve_post(Request $request)
    {
        $permission = Auth::user()->can('ot-approve');
        if(!$permission){
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $checked = $request->ot_data;

        foreach ($checked as $ch) {

            $data = array(
                'emp_id' => $ch['emp_id'],
                'date' => $ch['date'],
                'from' => $ch['from'],
                'to' => $ch['to'],
                'hours' => $ch['hours'],
                'double_hours' => $ch['double_hours'],
                'is_holiday' => $ch['is_holiday'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            OtApproved::query()->insert($data);

        }

        return response()->json(['success' => 'OT Approved']);

    }

    public function ot_approved()
    {
        $permission = Auth::user()->can('ot-list');
        if(!$permission){
            abort(403);
        }
        return view('Attendent.ot_approved');
    }

    public function ot_approved_list(Request $request)
    {
        $permission = Auth::user()->can('ot-list');
        if(!$permission){
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
        $department = $request->get('department');
        $employee = $request->get('employee');
        $location = $request->get('location');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $type = $request->get('type');

        $att_query = 'SELECT ot_approved.*,  
                employees.emp_shift,  
                employees.id as emp_auto_id,
                employees.emp_name_with_initial,
                employees.emp_department, 
                branches.location as b_location,
                departments.name as dept_name 
                FROM ot_approved
                join `employees` on `employees`.`emp_id` = ot_approved.emp_id
                left join shift_types ON employees.emp_shift = shift_types.id  
                left join departments ON employees.emp_department = departments.id 
                left join branches ON employees.emp_location = branches.id 
                WHERE 1 = 1
                ';

        if ($department != '') {
            $att_query .= ' AND employees.emp_department = ' . $department;
        }

        if ($employee != '') {
            $att_query .= ' AND employees.emp_id = ' . $employee;
        }

        if ($location != '') {
            $att_query .= ' AND employees.emp_location = ' . $location;
        }

        if ($from_date != '' && $to_date != '') {
            $att_query .= ' AND ot_approved.date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
        }

        $data = DB::select($att_query);

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $permission = Auth::user()->can('ot-delete');
                if($permission){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete_btn btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>';
                }
                return $btn;
            })
            //is_holiday
            ->addColumn('is_holiday', function ($row) {
                if ($row->is_holiday == 1) {
                    return 'Yes';
                } else {
                    return 'No';
                }
            })
            //form
            ->addColumn('from', function ($row) {
                return date('Y-m-d h:i A', strtotime($row->from));
            })
            //to
            ->addColumn('to', function ($row) {
                return date('Y-m-d h:i A', strtotime($row->to));
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function ot_approved_list_monthly(Request $request)
    {
        $permission = Auth::user()->can('ot-list');
        if(!$permission){
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
        $department = $request->get('department');
        $employee = $request->get('employee');
        $location = $request->get('location');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $type = $request->get('type');

        $att_query = 'SELECT ot_approved.*,  
                employees.emp_shift,  
                employees.id as emp_auto_id,
                employees.emp_name_with_initial,
                employees.emp_department, 
                branches.location as b_location,
                departments.name as dept_name 
                FROM ot_approved
                join `employees` on `employees`.`emp_id` = ot_approved.emp_id
                left join shift_types ON employees.emp_shift = shift_types.id  
                left join departments ON employees.emp_department = departments.id 
                left join branches ON employees.emp_location = branches.id 
                WHERE 1 = 1
                ';

        if ($department != '') {
            $att_query .= ' AND employees.emp_department = ' . $department;
        }

        if ($employee != '') {
            $att_query .= ' AND employees.emp_id = ' . $employee;
        }

        if ($location != '') {
            $att_query .= ' AND employees.emp_location = ' . $location;
        }

        if ($from_date != '' && $to_date != '') {
            $att_query .= ' AND ot_approved.date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
        }

        $data = DB::select($att_query);

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $permission = Auth::user()->can('ot-delete');
                if($permission){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="delete_btn btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>';
                }
                return $btn;
            })
            //is_holiday
            ->addColumn('is_holiday', function ($row) {
                if ($row->is_holiday == 1) {
                    return 'Yes';
                } else {
                    return 'No';
                }
            })
            //form
            ->addColumn('from', function ($row) {
                return date('Y-m-d h:i A', strtotime($row->from));
            })
            //to
            ->addColumn('to', function ($row) {
                return date('Y-m-d h:i A', strtotime($row->to));
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function ot_approved_delete(Request $request)
    {
        $permission = Auth::user()->can('ot-delete');
        if(!$permission){
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $id = $request->get('id');
        OtApproved::query()->where('id', $id)->delete();
        return response()->json([
            'success' => true,
            'msg' => 'Deleted']);
    }

    //delete
    public function delete(Request $request)
    {
        $permission = Auth::user()->can('attendance-delete');
        if(!$permission){
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $uid = $request->get('uid');
        $date_time = $request->get('date');
        $date = date('Y-m-d', strtotime($date_time));

        //delete attendance
        $status = Attendance::query()->where('uid', $uid)->whereDate('date', $date)->delete();

        return response()->json([
            'success' => true,
            'status' => $status,
            'msg' => 'Deleted']);

    }

    public function attendance_clear_list_dt(Request $request)
    {
        $user = Auth::user();
        $permission = $user->can('attendance-');
        if (!$permission) {
            return response()->json(['error' => 'You do not have permission.']);
        }

        $department = $request->get('department');
        $employee = $request->get('employee');
        $location = $request->get('location');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

        $query = DB::query()
            ->select('ela.*',
                'employees.emp_name_with_initial',
                'branches.location',
                'departments.name as dep_name')
            ->from('employee_late_attendances as ela')
            ->Join('employees', 'ela.emp_id', '=', 'employees.emp_id')
            ->leftJoin('attendances as at1', 'at1.id', '=', 'ela.attendance_id')
            ->leftJoin('branches', 'at1.location', '=', 'branches.id')
            ->leftJoin('departments', 'departments.id', '=', 'employees.emp_department');

        if ($department != '') {
            $query->where(['departments.id' => $department]);
        }

        if ($employee != '') {
            $query->where(['employees.emp_id' => $employee]);
        }

        if ($location != '') {
            $query->where(['at1.location' => $location]);
        }

        if ($from_date != '' && $to_date != '') {
            $query->whereBetween('ela.date', [$from_date, $to_date]);
        }

        $data = $query->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $btn = '';

                $permission = Auth::user()->can('late-attendance-delete');
                if ($permission) {
                    $btn = ' <button type="button" 
                        name="delete_button"
                        title="Delete"
                        data-id="' . $row->id . '"  
                        class="view_button btn btn-danger btn-sm delete_button"><i class="fas fa-trash"></i></button> ';
                }

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


}
