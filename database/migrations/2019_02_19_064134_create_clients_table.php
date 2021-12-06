<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('password');
            $table->string('businessName')->nullable();
            $table->integer('businessTypeId')->unsigned();
            $table->string('businessType')->nullable();
            $table->string('businessOtherTypeName')->nullable();
            $table->integer('cities_id')->unsigned();
            $table->integer('regions_id')->unsigned();;
            $table->text('address')->nullable();
            $table->text('mapAddress');
            $table->integer('type');
            $table->double('latitude');
            $table->double('longitude');
      
            $table->foreign('businessTypeId')->references('id')->on('bussiness_types')->onDelete('cascade');
            $table->foreign('cities_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('regions_id')->references('id')->on('regions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
