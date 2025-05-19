<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    function __construct(){
        $this->middleware(['role:admin']);
    }

    function index()  {
        return view('page.user.index');
    }

    public function fetchUsers()
    {
        $users = User::all();
        $dataTable = DataTables::of($users)
        ->addIndexColumn()
        ->make(true);


        return $dataTable;
    }

    public function show($id) {
        DB::beginTransaction();

        try {
            $user = User::find($id);
            return response()->json(['data' => $user]);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Something wrong ...",'title' => "Error",'log' => $th->getMessage],422);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'role'      => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User created successfully','title' => "Success", 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required',
            'role'  => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role   = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully','title' => "Success"]);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted successfully','title' => "Success"]);
    }
}
