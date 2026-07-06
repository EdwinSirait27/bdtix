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
        Schema::table('ticket_tables', function (Blueprint $table) {
              $table->uuid('store_id')->nullable()->after('user_id');
            $table->index('store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_tables', function (Blueprint $table) {
               $table->dropIndex(['ticket_tables_store_id_index']);
            $table->dropColumn('store_id');
        });
    }
};
