<?php

namespace App\Http\Controllers;

use App\Commen;
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
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        $approvel01permission = 0;
        $approvel02permission = 0;
        $approvel03permission = 0;

        $listpermission = 0;
        $editpermission = 0;
        $deletepermission = 0;
        $statuspermission = 0;

        if (in_array('Approve-Level-01', $userPermissions)) {
            $approvel01permission = 1;
        } 
        if (in_array('Approve-Level-02', $userPermissions)) {
            $approvel02permission = 1;
        } 
        if (in_array('Approve-Level-03', $userPermissions)) {
            $approvel03permission = 1;
        } 
        if (in_array('Stock-list', $userPermissions)) {
            $listpermission = 1;
        } 
        if (in_array('Stock-edit', $userPermissions)) {
            $editpermission = 1;
        } 
        if (in_array('Stock-status', $userPermissions)) {
            $statuspermission = 1;
        } 
        if (in_array('Stock-delete', $userPermissions)) {
            $deletepermission = 1;
        } 

        $stores = DB::table('storelists')->select('storelists.*')
        ->whereIn('storelists.status', [1, 2])
        ->where('storelists.approve_status', 1)
        ->get();
       
        return view('Stock.stock',compact('stores','approvel01permission','approvel02permission','approvel03permission','listpermission','editpermission','deletepermission','statuspermission','userPermissions'));
    }
}
