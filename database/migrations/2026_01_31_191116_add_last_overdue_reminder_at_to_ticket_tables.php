s<?php

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
        Schema::table('ticket_tables', function (Blueprint $table) {
             $table->dateTime('last_overdue_reminder_at')
                  ->nullable()
                  ->after('progressed_at');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_tables', function (Blueprint $table) {
            //
        });
    }
};
