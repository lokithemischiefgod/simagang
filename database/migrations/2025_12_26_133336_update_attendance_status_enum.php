<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE attendances MODIFY status ENUM('standby_kantor','izin','turun_lapangan','checkout') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE attendances MODIFY status ENUM('hadir','izin','turun_lapangan') NOT NULL");
    }
};
