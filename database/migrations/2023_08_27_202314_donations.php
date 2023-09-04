<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Donations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('donations', function (Blueprint $table) {
            $table->bigIncrements('don_id');             
            $table->decimal('amount',$precision = 5, $scale = 2);            
            $table->string('currency',10);
            $table->text('donation_msg')->nullable();
            $table->unsignedBigInteger('donator_id');
            $table->unsignedBigInteger('stream_owner');
            $table->date('donation_dt');   
            $table->char('read_status');
            $table->foreign('donator_id')->references('id')->on('users');
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
