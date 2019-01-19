<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnsInBusinessHours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_hours', function (Blueprint $table) {
            $table->dropColumn('day_of_week');
            $table->boolean('wd_0')->default(false);
            $table->boolean('wd_1')->default(false);
            $table->boolean('wd_2')->default(false);
            $table->boolean('wd_3')->default(false);
            $table->boolean('wd_4')->default(false);
            $table->boolean('wd_5')->default(false);
            $table->boolean('wd_6')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('business_hours', function (Blueprint $table) {
            if (Schema::hasColumn('business_hours', 'wd_0')) {
                $table->dropColumn(['wd_0', 'wd_1', 'wd_2', 'wd_3', 'wd_4', 'wd_5', 'wd_6']);
            }
            $table->smallInteger('day_of_week')->default(0);
        });
    }
}
