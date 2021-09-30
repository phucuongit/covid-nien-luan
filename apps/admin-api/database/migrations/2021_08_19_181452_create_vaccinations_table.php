<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            
            $table->tinyInteger('time');
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('create_by')
                ->references('id')
                ->on('users')
                ->constrained()
                ->onUpdate('cascade');
                // ->onDelete('cascade');
            $table->foreignId('vaccine_type_id')
                ->constrained()
                ->onUpdate('cascade');
                // ->onDelete('cascade');
            //Default famework columns
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
        Schema::dropIfExists('vaccinations');
    }
}
