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
        Schema::create('scans', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('symbol');
            $table->bigInteger('price')->nullable();
            $table->bigInteger('gap_percent')->nullable();
            $table->bigInteger('float');
            $table->bigInteger('short_interest');
            $table->integer('p_count')->default(0);
            $table->integer('m_count')->default(0);
            $table->integer('a_count')->default(0);
            $table->boolean('reviewed')->default(false);
            $table->string('name')->nullable();
            $table->string('exchange')->nullable();
            $table->bigInteger('market_cap')->nullable();
            $table->date('list_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
