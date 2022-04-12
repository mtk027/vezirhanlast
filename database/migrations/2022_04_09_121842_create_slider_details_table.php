<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slider_id')->on('sliders')->onDelete('cascade');
            $table->foreignId('language_id')->on('languages')->onDelete('cascade');
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->string('short_description');
            $table->string('button_title')->nullable();
            $table->string('short_desc')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slider_details');
    }
};
