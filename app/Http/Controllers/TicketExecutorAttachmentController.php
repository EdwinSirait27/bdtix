<?php

namespace App\Http\Controllers;

use App\Jobs\UploadAttachmentToGoogleDrive;
use App\Models\TicketExecutorAttachment;
use App\Models\Tickets;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\DriveHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Jobs\DeleteAttachmentFromS3ForExecutor;
use App\Jobs\UploadAttachmentToS3ForExecutor;
use App\Helpers\StorageHelper;


class TicketExecutorAttachmentController extends Controller
{
  public function store(Request $request, $ticketId): JsonResponse
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Hanya admin dan executor yang boleh upload
    if (!$user->hasAnyRole(['admin', 'executor'])) {
        abort(403, 'You are not allowed to upload executor attachments.');
    }

    $request->validate([
        'files'   => ['nullable', 'array', 'max:10'],
        'files.*' => [
            'file',
            'max:5048',
            'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip,txt',
        ],
    ]);

    $ticket = Tickets::findOrFail($ticketId);

    // Executor hanya boleh upload ke tiket yang dia handle
    // Admin boleh upload ke tiket manapun
    if ($user->hasRole('executor') && $ticket->executor_id !== $user->id) {
        abort(403, 'You are not allowed to upload executor attachments for this ticket.');
    }

    $username    = Str::slug($user->username ?? $user->id);
    $s3Folder    = "bdtix-executor-attachments/{$username}";
    $attachments = [];

    foreach ($request->file('files', []) as $index => $file) {
        $date     = now()->format('Y-m-d');
        $title    = Str::slug($ticket->title);
        $ext      = $file->getClientOriginalExtension();
        $suffix   = $index + 1;
        $fileName = "{$username}-{$date}-{$title}-{$suffix}.{$ext}";

        $tempPath = Storage::disk('local')->putFileAs(
            "temp-attachments/{$username}",
            $file,
            $fileName
        );

        if (!$tempPath) {
            Log::error('EXECUTOR_ATTACHMENT_TEMP_FAILED', [
                'ticket_id'     => $ticketId,
                'original_name' => $file->getClientOriginalName(),
            ]);
            continue;
        }

        try {
            $attachment = TicketExecutorAttachment::create([
                'id'            => (string) Str::uuid(),
                'ticket_id'     => $ticketId,
                'executor_id'   => $user->id,
                'file_name'     => $fileName,
                'file_path'     => "{$s3Folder}/{$fileName}",
                'original_name' => $file->getClientOriginalName(),
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
                'status'        => 'pending',
            ]);
        } catch (\Throwable $e) {
            Log::error('EXECUTOR_ATTACHMENT_CREATE_FAILED', [
                'ticket_id' => $ticketId,
                'error'     => $e->getMessage(),
            ]);
            continue;
        }
        UploadAttachmentToS3ForExecutor::dispatch(
            $attachment->id,
            $tempPath,
            $s3Folder,
            $fileName
        )->onQueue('ticket-heavy');

        Log::info('EXECUTOR_ATTACHMENT_QUEUED', [
            'ticket_id'     => $ticketId,
            'attachment_id' => $attachment->id,
            'file_name'     => $fileName,
            'user_id'       => $user->id,
        ]);

        $attachments[] = [
            'id'            => $attachment->id,
            'original_name' => $attachment->original_name,
            'mime_type'     => $attachment->mime_type,
            'size'          => $attachment->size,
            'status'        => 'pending',
            'uploaded_at'   => $attachment->created_at->toDateTimeString(),
        ];
    }

    return response()->json([
        'message'     => 'Bukti pengerjaan berhasil diupload.',
        'attachments' => $attachments,
    ], 201);
}

public function destroy($attachmentId): JsonResponse
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (!$user->hasAnyRole(['admin', 'executor'])) {
        abort(403, 'You are not allowed to delete executor attachments.');
    }

    $attachment = TicketExecutorAttachment::findOrFail($attachmentId);
    $ticket     = $attachment->ticket;

    if (!$ticket) {
        abort(404, 'Ticket not found.');
    }

    if ($user->hasRole('executor') && $attachment->executor_id !== $user->id) {
        abort(403, 'You are not allowed to delete this attachment.');
    }

    $filePath = $attachment->file_path;
    $attachment->delete();

    DeleteAttachmentFromS3ForExecutor::dispatch($filePath)->onQueue('ticket-heavy');

    Log::info('EXECUTOR_ATTACHMENT_DELETED', [
        'attachment_id' => $attachmentId,
        'ticket_id'     => $ticket->id, // ambil dari relasi
        'file_path'     => $filePath,
        'user_id'       => $user->id,
    ]);

    return response()->json(['success' => true, 'message' => 'Attachment berhasil dihapus.']);
}

public function signedUrlForExecutor(string $attachmentId)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $attachment = TicketExecutorAttachment::where('id', $attachmentId)
        ->where('status', 'uploaded')
        ->firstOrFail();

    $isTicketOwner = $attachment->ticket?->user_id === $user->id;

    if (!$isTicketOwner && !$user->hasAnyRole(['admin', 'executor', 'human'])) {
        abort(403);
    }

    // $url = StorageHelper::temporaryPublicUrl($attachment->file_path, 5);
$url = StorageHelper::publicUrl($attachment->file_path);

    return response()->json([
        'url'       => $url,
        'file_name' => $attachment->original_name ?? $attachment->file_name,
        'mime_type' => $attachment->mime_type,
    ]);
}
}

