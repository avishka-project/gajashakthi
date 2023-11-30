<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Bank_branch;
use App\Commen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;
use Yajra\Datatables\Datatables;

class BankBranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($bankcode)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('bank-list', $userPermissions)) {
            abort(403);
        } 
        

        $bank = Bank::where('code',$bankcode)->first();
        return view('Organization.bank_branch', compact( 'bank','userPermissions'));
    }

    public function bank_branches_list_dt(Request $request)
    {
        $bank_code = $request->bank_code;

        $branches = DB::table('bank_branches')
            ->select('bank_branches.*')
            ->where('bank_branches.bankcode', $bank_code)
            ->get();

        return Datatables::of($branches)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $btn = '';

                $commen= new Commen();
                $userPermissions = $commen->Allpermission();

                if(in_array('bank-edit',$userPermissions)){
                    $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                }

                if(in_array('bank-delete',$userPermissions)){
                    $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                }

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('bank-create', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rules = array(
            'name' => 'required',
            'code' => 'required|unique:bank_branches,code|max:3',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $bank = new Bank_branch();
        $bank->branch = $request->input('name');
        $bank->code = $request->input('code');
        $bank->bankcode = $request->input('bankcode');
        $bank->zipcode = $request->input('zipcode');
        $bank->status = '1';
        $bank->create_by = Auth::id();
        $bank->save();

        return response()->json(['success' => 'Branch Added successfully.']);
    }

    public function edit($id)
    {
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('bank-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        if (request()->ajax()) {
            $data = Bank_branch::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request, Bank_branch $bank_branch)
    {
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('bank-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $rules = array(
            'name' => 'required',
            'code' => 'required | max:3',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $current_date_time = Carbon::now()->toDateTimeString();

        $form_data = array(
            'branch' => $request->name,
            'code' => $request->code,
            'zipcode' => $request->zipcode,
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );

        Bank_branch::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Branch is successfully updated']);
    }

    public function destroy($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('bank-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = Bank_branch::findOrFail($id);
        $data->delete();
    }

    public function branch_list(Request $request){
        if ($request->ajax())
        {
            $page = Input::get('page');
            $bankcode = Input::get('bank');
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            $breeds = Bank_branch::where('branch', 'LIKE',  '%' . Input::get("term"). '%')
                ->where('status','1')
                ->where('bankcode',"$bankcode")
                ->orderBy('code')
                ->skip($offset)
                ->take($resultCount)
                ->get([DB::raw('id as id'),DB::raw('branch as text')]);

            $count = Bank_branch::where('status', '1')
                ->where('bankcode',"$bankcode")
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

}
