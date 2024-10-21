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
        Schema::create('toko', function (Blueprint $table) {
            $table->id();
            $table->string('cabang', 25)->nullable();
            $table->string('ip', 100)->nullable();
            $table->boolean('is_induk')->nullable();
            $table->string('kdtk', 100)->nullable();
            $table->string('koneksi', 150)->nullable();
            $table->string('nama', 200)->nullable();
            $table->string('report', 200)->nullable();
            $table->string('station', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toko');
    }
};
