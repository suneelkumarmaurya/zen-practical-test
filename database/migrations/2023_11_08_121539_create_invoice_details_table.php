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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->increments('InvoiceDetail_id');
            $table->string('Invoice_id');
            $table->string('product_id');
            $table->string('Rate');
            $table->string('Unit');
            $table->string('Qty');
            $table->string('Disc_percentage');
            $table->string('NetAmount');
            $table->string('TotalAmount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
