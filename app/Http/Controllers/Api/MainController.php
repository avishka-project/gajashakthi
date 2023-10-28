<?php

namespace App\Http\Controllers\Api;

use App\Employee;
use App\Leave;
use App\User; 
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MainController extends Controller
{
    public function __construct()
    {

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-Auth-Token');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day   // cache for 1 day
            header('content-type: application/json; charset=utf-8');
        }

        if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
            $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
        }



        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers:        
               {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }
    }

    public function getCustomerBranches(Request $request)
    {
        $q = "
            SELECT cb.* 
            FROM customerbranches cb 
        ";

        $data = DB::select($q);

        $data = array(
            'customer_branches' => $data
        );

        return (new BaseController)->sendResponse($data, 'customer_branches');
    }

    public function attendanceStore(Request $request)
    {
        //validate request
        $validator = \Validator::make($request->all(), [
            'emp_id' => 'required',
            'uid' => 'required',
            'state' => 'required',
            'timestamp' => 'required',
            'date' => 'required',
            'approved' => 'required',
            'type' => 'required',
            'devicesno' => 'required',
            'location' => 'required'

        ]);

        if($validator->fails()){
            return (new BaseController())->sendError('Validation Error.', $validator->errors(), '400');
        }

        $time_stamp = date('H:i:s', strtotime($request->timestamp));
        $time_stamp =  $request->date.' '.$time_stamp;

        $attendance = new \App\Attendance;
        $attendance->emp_id = $request->emp_id;
        $attendance->uid = $request->uid;
        $attendance->state = $request->state;
        $attendance->timestamp = $time_stamp;
        $attendance->date = $request->date;
        $attendance->approved = $request->approved;
        $attendance->type = $request->type;
        $attendance->devicesno = $request->devicesno;
        $attendance->location = $request->location;

        $attendance->save();
        return (new BaseController)->sendResponse($attendance, 'Attendance Added');
    }

    public function getEmployeeInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'branch_id' => 'required',
            'date' => 'required'
        ]);

        if($validator->fails()){
            return (new BaseController())->sendError('Validation Error.', $validator->errors(), '400');
        }

        $q = "
            SELECT * 
            FROM empallocations ea 
            LEFT JOIN empallocationdetails ead ON ead.allocation_id = ea.id 
            LEFT JOIN employees e ON e.emp_id = ead.emp_id
            LEFT JOIN shifts s ON s.id = ea.shift_id
            WHERE ea.customerbranch_id = '$request->branch_id' 
            AND ea.date = '$request->date'
        ";

        $data = DB::select($q);

        $data = array(
            'employee_info' => $data
        );

        return (new BaseController)->sendResponse($data, 'employee_info');
    }

    public function empLocationStore(Request $request)
    {
        //validate request
        $validator = \Validator::make($request->all(), [
            'emp_id' => 'required',
            'location_id' => 'required',
            'longitude' => 'required',
            'latitude' => 'required'
        ]);

        if($validator->fails()){
            return (new BaseController())->sendError('Validation Error.', $validator->errors(), '400');
        }

        $empLocation = new \App\EmpLocation;
        $empLocation->emp_id = $request->emp_id;
        $empLocation->location_id = $request->location_id;
        $empLocation->longitude = $request->longitude;
        $empLocation->latitude = $request->latitude;;

        $empLocation->save();
        return (new BaseController)->sendResponse($empLocation, 'Emp Location Added');
    }


}
