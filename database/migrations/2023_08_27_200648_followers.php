<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Followers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('followers', function (Blueprint $table) {
            $table->bigIncrements('fol_id');
            $table->string('name',225);             
            $table->unsignedBigInteger('follower_id');
            $table->unsignedBigInteger('stream_owner');
            $table->date('follow_start'); 
            $table->char('read_status');
            $table->foreign('follower_id')->references('id')->on('users');
            $table->foreign('stream_owner')->references('id')->on('users');          
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
