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
        Schema::create('failed', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('meternumber')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('units')->nullable();
            $table->decimal('charge')->nullable();
            $table->string('token')->nullable();
            $table->string('location')->nullable();
            $table->string('vtid')->nullable();
            $table->string('ppid')->nullable();
            $table->decimal('cost')->nullable();
            $table->decimal('tpl')->nullable();
            $table->decimal('commision')->nullable();
            $table->decimal('discountpercent')->nullable();
            $table->decimal('discountamount')->nullable();
            $table->decimal('tpm')->nullable();
            $table->timestamp('date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed');
    }
};
