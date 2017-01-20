<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginPostRequest;
use App\User as User;
use App\AdminUser as AdminUser;
use App\Http\Requests\SubmitResetPasswordPostRequest;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(){}

    public function showLogin() {
        return view('login');
    }

    public function authenticateUser(LoginPostRequest $request) {
        $authenticated = User::authenticate($request->input('email'), $request->input('password'));

        if(!$authenticated)
            return redirect()->back()->withErrors(['message' => 'Email or Password Incorrect... Try again.']);

        return redirect()->to('/deployment');
    }

    public function forgotPassword(Request $request) {
        $userId = $request->input('userId');

        $user = AdminUser::getUserByUserId($userId);

        $forgotPasswordToken = bin2hex(random_bytes(16));

        $updated = AdminUser::setForgotPasswordTokenByUserId($userId, $forgotPasswordToken);

        if($updated){
            Mail::to($user->email)->send(new ForgotPasswordEmail($forgotPasswordToken));

            return response()->json([
                'success' => 'true',
            ]);
        }

        return response()->json([
            'success' => 'false',
        ]);
    }

    public function resetPassword(Request $request) {
        $token = $request->input('token');

        $userId = AdminUser::validateForgotPasswordToken($token);

        if($userId)
            return view('resetpassword')->with('userId', $userId);

        session()->flash('app_error', 'Failed to validate forgot password token');
        return redirect()->to('/login');
    }

    public function submitResetPassword(SubmitResetPasswordPostRequest $request) {
        $updated = User::updateUserPassword($request->input('password'), $request->input('user_id'));

        if($updated){
            session()->flash('app_success', 'Successfully reset your password');
            return view('login');
        }
        session()->flash('app_error', 'Failed to reset your password.  Please try again.');
        return view('login');
    }

    public function logout() {
        Auth::logout();
        return redirect()->to('/login');
    }
}
