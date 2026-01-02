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
        Schema::create('tickets_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ticket_id')->nullable();

            $table->string('user_id')->nullable();      // dari DB hrx.users
            $table->string('executor_id')->nullable();

            $table->tinyInteger('rating')->nullable(); // 1–5
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->unique('ticket_id');

            // FK hanya ke ticket_tables (mysql)
            $table->foreign('ticket_id')
                ->references('id')
                ->on('ticket_tables')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets_reviews');
    }
};
