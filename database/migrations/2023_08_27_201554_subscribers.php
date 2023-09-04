<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Subscribers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('subscribers', function (Blueprint $table) {
            $table->bigIncrements('subs_id');
            $table->string('name',225); 
            $table->integer('subscription_tier');            
            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedBigInteger('stream_owner');
            $table->date('subscription_start');   
            $table->char('read_status');
            $table->foreign('subscriber_id')->references('id')->on('users');
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
