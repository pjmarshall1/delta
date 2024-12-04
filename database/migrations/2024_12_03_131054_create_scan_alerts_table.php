<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scan_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scan_id')->constrained()->cascadeOnDelete();
            $table->timestamp('timestamp');
            $table->string('symbol');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('volume');
            $table->unsignedBigInteger('float');
            $table->unsignedBigInteger('relative_volume_daily');
            $table->unsignedBigInteger('relative_volume_five');
            $table->bigInteger('gap_percent');
            $table->bigInteger('change_percent');
            $table->unsignedBigInteger('short_interest');
            $table->string('strategy_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_alerts');
    }
};
