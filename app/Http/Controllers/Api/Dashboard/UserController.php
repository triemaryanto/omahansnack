<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        //get users
        $users = User::when(request()->q, function ($users) {
            $users = $users->where('name', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);

        //return with Api Resource
        return ResponseFormatter::success($users, 'Data Users berhasil di dapatkan');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'roles'     => 'required',
            'address'     => 'required',
            'houseNumber'     => 'required',
            'phoneNumber'     => 'required',
            'city'     => 'required',
            'email'    => 'required|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/users', $image->hashName());

        $user = User::create([
            'image'       => $image->hashName(),
            'name'       => $request->name,
            'roles' => $request->roles,
            'address'     => $request->address,
            'houseNumber'     => $request->houseNumber,
            'phoneNumber'     => $request->phoneNumber,
            'city'     => $request->city,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        if ($user) {
            //return success with Api Resource
            return ResponseFormatter::success($user, 'Data Users berhasil di simpan');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Data User Gagal Di Simpan', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::whereId($id)->first();

        if ($user) {
            //return success with Api Resource
            return ResponseFormatter::success($user, 'Detail Data User');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Detail Data User Tidak DItemukan!', 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|unique:users,email,' . $user->id,
            'roles'     => 'required',
            'address'     => 'required',
            'houseNumber'     => 'required',
            'phoneNumber'     => 'required',
            'city'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image update
        if ($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('public/users/' . basename($user->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/users', $image->hashName());

            $user->update([
                'image'       => $image->hashName(),
                'name'       => $request->name,
                'roles' => $request->roles,
                'address'     => $request->address,
                'houseNumber'     => $request->houseNumber,
                'phoneNumber'     => $request->phoneNumber,
                'city'     => $request->city,
                'email' => $request->email,
            ]);
        } else
            $user->update([
                'name'       => $request->name,
                'roles' => $request->roles,
                'address'     => $request->address,
                'houseNumber'     => $request->houseNumber,
                'phoneNumber'     => $request->phoneNumber,
                'city'     => $request->city,
                'email' => $request->email,
            ]);
        if ($request->password == "") {

            //update user without password
            $user->update([
                'name'       => $request->name,
                'address'     => $request->address,
                'houseNumber'     => $request->houseNumber,
                'phoneNumber'     => $request->phoneNumber,
                'city'     => $request->city,
                'email' => $request->email,
            ]);
        } else
            $user->update([
                'name'       => $request->name,
                'address'     => $request->address,
                'houseNumber'     => $request->houseNumber,
                'phoneNumber'     => $request->phoneNumber,
                'city'     => $request->city,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
        if ($user) {
            //return success with Api Resource
            return ResponseFormatter::success($user, 'Data User Berhasil Diupdate');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Data User Gagal Diupdate!', 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            //return success with Api Resource
            return ResponseFormatter::success(null, 'Data User Berhasil Dihapus');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Data User Gagal Dihapus!', 500);
    }
}
