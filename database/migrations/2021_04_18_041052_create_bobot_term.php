<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBobotTerm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bobot_term', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bobot_id');
            $table->unsignedBigInteger('term_id');

            $table->foreign('bobot_id')->references('id')->on('bobots');
            $table->foreign('term_id')->references('id')->on('terms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bobot_term');
    }
}
