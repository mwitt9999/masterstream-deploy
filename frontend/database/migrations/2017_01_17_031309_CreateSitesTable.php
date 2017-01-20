<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('github_account_name');
            $table->string('github_repository_name');
            $table->timestamps();
        });

        $site = new \App\Site;
        $site->name = 'Test Deployment App';
        $site->github_account_name = 'mwitt9999';
        $site->github_repository_name = 'test-deployment-app';
        $site->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}
