<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuppliersController extends Controller
{
    function  __construct() {
        $this->middleware('auth');
    }

    function index() {
        return view('page.suppliers.index');
    }

    function data(Request $request) {
        $suppliers = Suppliers::all();

        $table = DataTables::of($suppliers)
        ->addIndexColumn()
        ->make(true);

        return $table;
    }

    function store (Request $request) {
        $validator  = Validator::make($request->all(),[
            'name'      => 'required|max:255',
            'contact'   => 'required|max:255',
            'email'     => 'required|unique:users,email',
            'address'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(),'title' => "Error"],422);
        }

        DB::beginTransaction();
        try {
            $suppliers          = new Suppliers();
            $suppliers->name    = $request->name;
            $suppliers->contact = $request->contact;
            $suppliers->email   = $request->email;
            $suppliers->address = $request->address;
            $suppliers->save();

            $user               = new User();
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->password     = Hash::make('password');
            $user->role         = 'supplier';
            $user->save();

            DB::commit();
            return response()->json(['message' => "Supplier add successfully",'title' => "Success"]);

        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => "Something wrong ...",'title' => "Error",'log' => $th->getMessage],422);
        }
    }

    function show($id) {
        DB::beginTransaction();

        try {
            $suppliers = Suppliers::find($id);
            return response()->json(['data' => $suppliers]);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Something wrong ...",'title' => "Error",'log' => $th->getMessage],422);
        }
    }

    function update(Request $request, $id) {

        DB::beginTransaction();
        try {
            $suppliers          = Suppliers::find($id);
            $suppliers->name    = $request->name;
            $suppliers->contact = $request->contact;
            $suppliers->email   = $request->email;
            $suppliers->address = $request->address;
            $suppliers->save();

            DB::commit();
            return response()->json(['message' => "Supplier update successfully",'title' => "Success"]);

        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => "Something wrong ...",'title' => "Error",'log' => $th->getMessage],422);
        }
    }

    function delete($id) {
        DB::beginTransaction();
        try {
            $suppliers = Suppliers::findOrFail($id);
            $suppliers->delete();

            DB::commit();

            return response()->json(['message' => "Suppliers deleted!",'title' => "Success"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => "Something wrong...",'title' => "Error",'log' => $th->getMessage],422);
        }
    }


}
