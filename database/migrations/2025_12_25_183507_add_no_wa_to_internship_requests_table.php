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
    Schema::table('internship_requests', function (Blueprint $table) {
        $table->string('no_wa', 20)->after('email_pengaju');
    });
}

public function down(): void
{
    Schema::table('internship_requests', function (Blueprint $table) {
        $table->dropColumn('no_wa');
    });
}

};
