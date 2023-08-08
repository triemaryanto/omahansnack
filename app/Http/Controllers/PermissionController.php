<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function checkPermissions(Request $request)
    {
        // Assuming you're using the JWT package, you can retrieve the user from the token
        $user = auth()->guard('api')->user();

        // Fetch user's roles and permissions
        $roles = $user->roles;
        $permissions = $user->permissions;

        return response()->json([
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }
}
