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
        Schema::create('system_errors', function (Blueprint $table) {
            $table->id();
            $table->string('module',100);
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->longText('error_message');
            $table->longText('url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_errors');
    }
};
