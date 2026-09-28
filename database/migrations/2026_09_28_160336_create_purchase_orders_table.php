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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->index('supplier_id');
            $table->bigInteger('total_amount')->nullable();
            $table->bigInteger('gst')->nullable();
            $table->bigInteger('amount_payable')->nullable();
            $table->foreignId('purchase_order_status_id')->constrained('purchase_order_statuses');
            $table->index('purchase_order_status_id');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->index('approved_by');
            $table->dateTime('received_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->boolean('is_locked')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
