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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['standby_kantor', 'izin', 'turun_lapangan', 'checkout']);
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
     });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
