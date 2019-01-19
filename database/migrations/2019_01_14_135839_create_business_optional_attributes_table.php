<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessOptionalAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('optional_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name')->unique();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('business_optional_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->integer('optional_attribute_id')->unsigned();
            $table->string('description')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();

            $table->unique(['business_id', 'optional_attribute_id'], 'business_optional_attribute_unique');

            $table
                ->foreign('business_id')
                ->references('id')
                ->on('businesses')
                ->onDelete('cascade');
            $table
                ->foreign('optional_attribute_id')
                ->references('id')
                ->on('optional_attributes')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_optional_attribute');
        Schema::dropIfExists('optional_attributes');
    }
}
