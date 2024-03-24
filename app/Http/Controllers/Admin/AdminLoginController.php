<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                $admin = auth('admin')->user();

                // Check if the admin has any roles
                if ($admin->roles->isNotEmpty()) {
                    // Redirect to the admin dashboard or any authorized section
                    return redirect()->route('admin.dashboard');
                } else {
                    auth('admin')->logout();
                    return redirect()->route('admin.login')->with('error', 'You do not have any roles assigned. Contact the administrator.');
                }
            }
            else {
                return redirect()->route('admin.login')->with('error', 'Either Email/Password is incorrect');
            }
        }
        else {
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
}


// $user = auth()->user();

// if ($user->roles->isNotEmpty()) {
//     // Redirect to the admin dashboard or any authorized section
//     return redirect()->route('admin.dashboard');
// } else {
//     auth('admin')->logout();
//     return redirect()->route('admin.login')->with('error', 'You do not have any roles assigned. Contact the administrator.');
// }
