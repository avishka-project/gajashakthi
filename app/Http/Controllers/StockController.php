<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $approvel01permission = 0;
        $approvel02permission = 0;
        $approvel03permission = 0;

        $listpermission = 0;
        $editpermission = 0;
        $deletepermission = 0;
        $statuspermission = 0;
        
        if (Auth::user()->can('Approve-Level-01')) {
            $approvel01permission = 1;
        } 
        if (Auth::user()->can('Approve-Level-02')) {
            $approvel02permission = 1;
        } 
        if (Auth::user()->can('Approve-Level-03')) {
            $approvel03permission = 1;
        } 
        if (Auth::user()->can('Stock-list')) {
            $listpermission = 1;
        } 
        if (Auth::user()->can('Stock-edit')) {
            $editpermission = 1;
        }
        if (Auth::user()->can('Stock-status')) {
            $statuspermission = 1;
        }
        if (Auth::user()->can('Stock-delete')) {
            $deletepermission = 1;
        }

        $stores = DB::table('storelists')->select('storelists.*')
        ->whereIn('storelists.status', [1, 2])
        ->where('storelists.approve_status', 1)
        ->get();
       
        return view('Stock.stock',compact('stores','approvel01permission','approvel02permission','approvel03permission','listpermission','editpermission','deletepermission','statuspermission'));
    }
}
