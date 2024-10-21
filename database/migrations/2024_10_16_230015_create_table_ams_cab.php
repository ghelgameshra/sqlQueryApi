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
        Schema::create('ams_cab', function (Blueprint $table) {
            $table->id();
            $table->string('cab_name', 100)->nullable();
            $table->string('host_name', 100)->nullable();
            $table->string('jenis_server', 100)->nullable();
            $table->string('kdcab', 100)->nullable();
            $table->string('pass', 100)->nullable();
            $table->string('port', 100)->nullable();
            $table->string('uname', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ams_cab');
    }
};
