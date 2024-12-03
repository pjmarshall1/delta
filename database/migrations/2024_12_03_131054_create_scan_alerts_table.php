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
            $table->bigInteger('price');
            $table->bigInteger('volume');
            $table->bigInteger('float');
            $table->bigInteger('relative_volume_daily');
            $table->bigInteger('relative_volume_five');
            $table->unsignedBigInteger('gap_percent');
            $table->unsignedBigInteger('change_percent');
            $table->bigInteger('short_interest');
            $table->string('strategy_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_alerts');
    }
};
