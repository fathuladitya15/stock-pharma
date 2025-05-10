<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index() {
        $user   =   User::find(auth()->user()->id);
        return view('page.profile.index',compact('user'));
    }

    public function update(Request $request) {
        DB::beginTransaction();
        $id     =   $request->id;

        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.$id,
            'password'  => 'nullable|min:6',
        ]);

        try {
            $user = User::findOrFail($id);

            // Simpan email lama sebelum diubah
            $oldEmail = $user->email;

            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Cek dan update data supplier jika role adalah supplier
            if ($user->role === 'supplier') {
                $supplier = Suppliers::where('email', $oldEmail)->first();
                if ($supplier) {
                    $supplier->name = $request->name;
                    $supplier->email = $request->email;
                    $supplier->save();
                }
            }

            $user->save();
            DB::commit();


            return response()->json(['message' => "profile updated successfully",'title' => "Success",'s' => $supplier]);

        } catch (\Throwable $th) {
            return response()->json(['message' => "Something wrong ...",'title' => "Error",'log' => $th->getMessage(),'data' => $request->all()],422);
        }
    }
}
