<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MerchSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('merch_sales', function (Blueprint $table) {
            $table->bigIncrements('sale_id');  
            $table->string('item_name',225);           
            $table->decimal('amount',$precision = 5, $scale = 2); 
            $table->decimal('price',$precision = 5, $scale = 2);            
            $table->string('currency',10);            
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('stream_owner');
            $table->date('sale_dt');   
            $table->char('read_status'); 
            $table->foreign('buyer_id')->references('id')->on('users');
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
