<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('order_number')->unique(); // INV-YYYYMMDD-XXXX
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->enum('order_type', ['dine_in', 'takeaway', 'pickup']);
            $table->string('table_or_notes')->nullable();
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('tax_amount');
            $table->unsignedInteger('service_fee')->default(2000);
            $table->unsignedInteger('total_amount');
            $table->string('payment_status')->default('pending'); // pending, paid, expired, failed
            $table->string('payment_method')->nullable();
            $table->string('snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
