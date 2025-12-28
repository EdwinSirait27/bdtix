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
            $table->uuid('executor')->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High'])->nullable();
            $table->text('notes_executor')->nullable();
            $table->dateTime('finished')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_tables', function (Blueprint $table) {
        });
    }
};
