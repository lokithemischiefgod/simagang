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
    Schema::create('work_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('attendance_id')->constrained()->onDelete('cascade');
        $table->text('aktivitas');
        $table->time('jam_mulai');
        $table->time('jam_selesai')->nullable();
        $table->timestamps();
    });
}

};
