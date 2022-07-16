<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->on('categories')->references('id')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->on('admins')->references('id')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('size');
            $table->string('color');
            $table->string('description');
            $table->string('price_per_day');
            $table->integer('status')->default('1');
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
        Schema::dropIfExists('dresses');
    }
}
