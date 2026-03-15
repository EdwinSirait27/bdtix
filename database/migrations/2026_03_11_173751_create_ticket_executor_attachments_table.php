<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_executor_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ticket_id');
            $table->string('executor_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('size')->nullable();
            $table->string('drive_file_id')->nullable();
            $table->string('drive_folder_id')->nullable();
            $table->text('web_view_link')->nullable();
            $table->text('web_content_link')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_executor_attachments');
    }
};