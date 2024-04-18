<?php

namespace App\Services\Backend\AuthServices;

use App\Models\Backend\UserModule\SuperAdmin;
use App\Models\Backend\UserModule\User;

class AuthenticationService
{
    function authenticateSuperAdmin($request): bool
    {
        return auth('super_admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')],true);
    }

    function isSuperAdminExists($request)
    {
        return SuperAdmin::where('email', $request->email)->first();

    }

    function isUserExists($request)
    {
        return User::where('username', $request->input('username'))->where('is_active', true)->first();
    }

    function authenticateUser($request): bool
    {
        return auth('web')->attempt(['username' => $request->input('username'), 'password' => $request->input('password')], true);
    }
}
