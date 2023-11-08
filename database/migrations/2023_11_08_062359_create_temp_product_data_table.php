<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temp_product_data', function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('customer_name');
            $table->string('product_name');
            $table->string('rate');
            $table->string('unit');
            $table->string('qty');
            $table->string('disc');
            $table->string('net_amt');
            $table->string('total_amt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_product_data');
    }
};
