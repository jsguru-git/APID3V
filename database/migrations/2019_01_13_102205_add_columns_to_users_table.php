<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('allow_location_tracking')->default(0);
            $table->tinyInteger('post_publicly')->default(0);
            $table->tinyInteger('t_c_agreed')->default(0);
            $table->tinyInteger('profile_visible')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'allow_location_tracking',
                'post_publicly',
                't_c_agreed',
                'profile_visible'
            ]);
        });
    }
}
