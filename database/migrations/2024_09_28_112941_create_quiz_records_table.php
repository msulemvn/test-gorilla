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
        Schema::create('quiz_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_instance_id');
            $table->foreign('quiz_instance_id')->references('id')->on('quiz_instances');
            $table->string('score');
            $table->binary('recording')->nullable();
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
        Schema::dropIfExists('quiz_records');
    }
};
