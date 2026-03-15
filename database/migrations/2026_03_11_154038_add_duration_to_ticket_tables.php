<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_tables', function (Blueprint $table) {
            $table->string('duration_type')->nullable()->after('estimation_to'); // hour, day, week
            $table->integer('duration_value')->nullable()->after('duration_type'); // angka durasinya
        });
    }

    public function down(): void
    {
        Schema::table('ticket_tables', function (Blueprint $table) {
            $table->dropColumn(['duration_type', 'duration_value']);
        });
    }
};