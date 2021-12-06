<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->double('cost', 8, 2)->default(0);
            $table->string('costText_ar')->nullable();
            $table->string('costText_en')->nullable();
            $table->integer('status');
            $table->string('statusText_ar')->nullable();
            $table->string('statusText_en')->nullable();
            $table->integer('productsCount')->default(0);
            $table->string('productsCountText_ar')->nullable();
            $table->string('productsCountText_en')->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
                         $table->integer('user_id')->unsigned();
              $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
