<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requestorders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('dress_id')->nullable();
            $table->foreign('dress_id')->on('dresses')->references('id')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->on('users')->references('id')
                ->cascadeOnDelete()->cascadeOnUpdate();
                $table->date('startdate');
                $table->date('enddate');
                $table->integer('price');

            $table->string('state')->default('0');
            $table->string('dayes');

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
        Schema::dropIfExists('requestorders');
    }
}
