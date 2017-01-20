<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class CreateAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = new App\User;
        $user->first_name = "Matthew";
        $user->last_name = "Witt";
        $user->type = "admin";
        $user->email = "mwitt8178@gmail.com";
        $user->password = bcrypt("password");
        $user->forgot_password_token = "";

        $user->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
