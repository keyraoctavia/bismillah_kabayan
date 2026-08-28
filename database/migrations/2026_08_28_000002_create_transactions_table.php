<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\BluePrint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up():void
    {
    Schema::create('transactions',function(Blueprint $table){
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->unsignedBigInteger('total_price');
        $table->unsignedBigInteger('cash');
        $table->unsignedBigInteger('change');
        $table->timestamps();
    });
    }

    public function down():void
    {
        Schema::dropIfExists('transactions');
    }
};