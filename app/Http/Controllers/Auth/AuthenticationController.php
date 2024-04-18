<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Backend\AuthServices\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    //

    protected AuthenticationService $authenticationService;

    /**
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }


    function show_login()
    {
        $target=session('previous_url') ?? '';
        return view('auth.login',compact('target'));
    }

    function do_login(Request $request)
    {
        $rules = [
            'password' => 'required|min:6',
        ];
        if ($request->has('user') && $request->input('user') == 'on') {
            $rules['username'] = ['required', 'exists:users,username'];
            $request->validate($rules);
            if ($this->authenticationService->isUserExists($request)) {
                if ($this->authenticationService->authenticateUser($request)) {
                    if($request['target']!=''){
                        return redirect()->away($request->input('target'));
                    }
                    return redirect()->route('admin.dashboard.index');
                } else {
                    return redirect()->back()->with('response',[
                        'message'=>'Invalid credentials',
                        'status'=>'danger'
                    ]);
                }
            } else {
                return redirect()->back()->with('response',[
                    'message'=>'User not found',
                    'status'=>'danger'
                ]);

            }

        } else {
            $rules['email'] = ['required', 'exists:super_admins,email'];
            $request->validate($rules);
            if ($this->authenticationService->isSuperAdminExists($request)) {
                $request['username']=null;
                if ($this->authenticationService->authenticateSuperAdmin($request)) {
                    if($request['target']!=''){
                        return redirect()->away($request->input('target'));
                    }
                    return redirect()->route('admin.dashboard.index');
                } else {
                    return redirect()->back()->with('response',[
                        'message'=>'Invalid credentials',
                        'status'=>'danger'
                    ]);

                }
            }else{
                return redirect()->back()->with('response',[
                    'message'=>'User not found',
                    'status'=>'danger'
                ]);
            }
        }
    }

    public function do_logout(Request $request)
    {
        if (auth('super_admin')->check()) {
            Auth::guard('super_admin')->logout();
        } else {
            Auth::guard('web')->logout();
        }
        session()->flush();
        session()->regenerate(true);
        return redirect()->route('admin.show-login');
    }
}
