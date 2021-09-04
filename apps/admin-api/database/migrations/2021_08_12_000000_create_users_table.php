<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('identify_card')->nullable();
            $table->string('social_insurance')->nullable();
            $table->string('fullname');
            $table->timestamp('birthday');
            $table->tinyInteger('gender');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();

            $table->foreignId('village_id')->constrained();
            $table->foreignId('role_id')->constrained();
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
        Schema::dropIfExists('users');
    }
}
