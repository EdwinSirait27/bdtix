<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_attachment', function (Blueprint $table) {
            $table->string('user_id')->nullable()->after('ticket_id');
            $table->string('original_name')->nullable()->after('user_id');
            $table->string('mime_type')->nullable()->after('original_name');
            $table->unsignedBigInteger('size')->nullable()->after('mime_type');
            $table->string('drive_file_id')->nullable()->after('size');
            $table->string('drive_folder_id')->nullable()->after('drive_file_id');
            $table->text('web_view_link')->nullable()->after('drive_folder_id');
            $table->text('web_content_link')->nullable()->after('web_view_link');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('ticket_attachment', function (Blueprint $table) {
            $table->dropColumn([
                'user_id', 'original_name', 'mime_type', 'size',
                'drive_file_id', 'drive_folder_id',
                'web_view_link', 'web_content_link', 'deleted_at'
            ]);
        });
    }
};