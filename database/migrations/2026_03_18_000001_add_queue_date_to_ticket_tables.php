<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ticket_tables', function (Blueprint $table) {
            $table->date('queue_date')->nullable()->after('queue_number');
            $table->unique(['queue_date', 'queue_number'], 'ticket_queue_date_number_unique');
        });

        // Backfill existing rows so the unique index applies cleanly for old data.
        DB::table('ticket_tables')
            ->whereNull('queue_date')
            ->update(['queue_date' => DB::raw('DATE(created_at)')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_tables', function (Blueprint $table) {
            $table->dropUnique('ticket_queue_date_number_unique');
            $table->dropColumn('queue_date');
        });
    }
};
