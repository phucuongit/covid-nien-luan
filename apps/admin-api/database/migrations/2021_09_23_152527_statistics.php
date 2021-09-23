<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Statistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('injection_first_time')->nullable();
            $table->unsignedBigInteger('injection_second_time')->nullable();
            $table->unsignedBigInteger('injection_more_than_2times')->nullable();
            $table->unsignedBigInteger('injection_total')->nullable();
            $table->unsignedBigInteger('result_test_positive')->nullable();
            $table->unsignedBigInteger('result_test_total')->nullable();
            // Vaccine type
            $table->unsignedBigInteger('total_num_Pfizer')->nullable();
            $table->unsignedBigInteger('total_num_AstraZeneca')->nullable();
            $table->unsignedBigInteger('total_num_Moderna')->nullable();
            $table->unsignedBigInteger('total_num_Sputnik V')->nullable();
            $table->unsignedBigInteger('total_num_Sinopharm')->nullable();
            $table->unsignedBigInteger('total_num_Janssen')->nullable();
            // Default famework columns
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
        //
    }
}
