<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Attendance;
use App\Commen;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();

        $today = Carbon::now()->format('Y-m-d');
        $empcount = DB::table('employees')->count();
        $todaycount = Attendance::where('date',$today)->count();
//        $late_attendance = DB::table('attendances')
//                ->join('employees', 'attendances.employee_id', '=', 'employees.id')
//                ->select('attendances.*', 'employees.name')
//                ->where('attendances.status', '=', 'Late')
//                ->get();

        return view('home',compact('empcount','todaycount','userPermissions'));
    }
}
