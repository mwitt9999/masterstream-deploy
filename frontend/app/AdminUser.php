<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;

class AdminUser extends \Eloquent {

    protected $table = 'users';
    protected $fillable = ['first_name', 'last_name', 'email', 'type', 'password', 'forgot_password_token'];

    public function __construct()
    {
    }

    public function deleteUser($userId) {
        AdminUser::destroy($userId);
    }

    public function editUser(Request $request) {
        $data = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'type' => $request->input('type')
        ];

        self::find($request->input('id'))->update($data);
    }

    public function saveUser(Request $request) {
        $currentTime = Carbon::now();

        $user = new User;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->forgot_password_token = '';
        $user->password = Hash::make($request->input('password'));
        $user->type = $request->input('type');
        $user->created_at = $currentTime->toDateTimeString();
        $user->save();

    }

    public function getAllUsers() {
        return self::all();
    }

    public function getUserDataTable() {
        return Datatables::of($this->getAllUsers())->make(true);
    }

    public static function getUserByUserId($userId) {
        return User::find($userId);
    }

    public static function setForgotPasswordTokenByUserId($userId, $token) {
        $data = [
            'forgot_password_token' => $token,
        ];

        return self::find($userId)->update($data);
    }

    public static function validateForgotPasswordToken($token){
        if($user = User::where('forgot_password_token', $token)->first()){
            return $user->id;
        }

        return false;
    }

    public static function checkUserExists($email) {
        if($user = self::where('email', $email)->first()){
            return true;
        }

        return false;
    }
}
