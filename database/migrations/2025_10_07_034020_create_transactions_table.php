<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->string('customer_name')->default('none');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('paid', 10, 2);
            $table->decimal('spare_change', 10, 2)->default(0);
            $table->dateTime('transaction_date');
            $table->enum('transaction_type', ['sale', 'return'])->default('sale');
            $table->enum('payment_method', ['cash', 'cashless'])->default('cash');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
