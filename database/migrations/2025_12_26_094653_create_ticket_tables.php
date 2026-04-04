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
        Schema::connection('mysql')->create('ticket_tables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->uuid('request_uuid')->unique()->nullable();
            $table->string('title', 150)->nullable();
            $table->enum('category', [
                'Building',
                'Plumbing',
                'Mechanical Engineering',
                'Others'
                ])->nullable();
                $table->string('remark',150)->default('Project');
            $table->enum('sub_category', [
                'Maintenance',
                'Renovation',
                'Others'
            ])->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['Open', 'Progress', 'Closed', 'Overdue'])->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('ticket_tables');
    }
};
