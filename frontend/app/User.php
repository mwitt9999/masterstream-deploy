<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends \Eloquent implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $fillable = ['first_name', 'last_name', 'email', 'type', 'password', 'forgot_password_token'];

    public function __construct()
    {
    }

    public static function authenticate($email, $password) {
        return(Auth::attempt(['email' => $email, 'password' => $password]));
    }

    public static function updateUserPassword($password, $userId) {
        $data = ['password'=> Hash::make($password), 'forgot_password_token' => ''];
        $updated = self::find($userId)->update($data);

        if($updated) {
            return true;
        }

        return false;
    }
}
