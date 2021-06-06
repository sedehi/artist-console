<?php

namespace App\Http\Controllers\User\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('User.views.admin.password.form');
    }

    public function change(Request $request)
    {
        $request->validate([
            'password'     => 'required|min:8|confirmed',
            'old_password' => [
                'required',
                'min:8',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth('admin')->user()->password)) {
                        $fail(trans('user.validation.wrong_current_password'));
                    }
                },
            ],
        ]);
        $user = auth('admin')->user();
        $user->password = bcrypt($request->get('password'));
        $user->save();

        return redirect()->route('admin.homepage')->with('success', trans('user.password_updated_successfully'));
    }
}
