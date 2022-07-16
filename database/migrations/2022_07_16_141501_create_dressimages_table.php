<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDressimagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dressimages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dress_id')->nullable();
            $table->foreign('dress_id')->on('dresses')->references('id')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('image');
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
        Schema::dropIfExists('dressimages');
    }
}
