<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->double('cost', 8, 2);
            $table->string('costText_ar')->nullable();
            $table->string('costText_en')->nullable();
            $table->integer('productsCount')->default(0);
            $table->string('productsCount_ar')->nullable();
            $table->string('productsCount_en')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
                         $table->integer('user_id')->unsigned();
              $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('carts');
    }
}
