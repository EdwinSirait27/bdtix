<?php

namespace App\Http\Controllers;

use App\Models\Ticketattachments;
use App\Models\Tickets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Jobs\UploadAttachmentToS3;
use App\Jobs\DeleteAttachmentFromS3;
use Illuminate\Support\Facades\Storage;
use App\Helpers\StorageHelper;

// class TicketAttachmentController extends Controller
// {
//     public function store(Request $request, $ticketId): JsonResponse
//     {
//         $request->validate([
//             'files'   => ['required', 'array', 'max:10'],
//             'files.*' => [
//                 'file',
//                 'max:20480',
//                 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip,txt',
//             ],
//         ]);

//         $ticket = Tickets::findOrFail($ticketId);
//         if ($ticket->user_id !== Auth::id()) {
//             abort(403, 'You are not allowed to upload attachments for this ticket.');
//         }
//         $owner = $ticket->user;
//         $folderIdentity = DriveHelper::getFolderIdentity($owner);
//         $filePrefix = DriveHelper::getFilePrefix(Auth::user());
//         $category = $ticket->category;
//         $attachments = [];

//         foreach ($request->file('files') as $file) {
//             $tempPath = $file->store('temp-attachments', 'local');

//             $attachment = Ticketattachments::create([
//                 'id'            => (string) Str::uuid(),
//                 'ticket_id'     => $ticketId,
//                 'user_id'       => Auth::id(),
//                 'file_name'     => $file->getClientOriginalName(),
//                 'file_path'     => $tempPath,
//                 'original_name' => $file->getClientOriginalName(),
//                 'mime_type'     => $file->getMimeType(),
//                 'size'          => $file->getSize(),
//                 'status'        => 'pending',
//             ]);

//             UploadAttachmentToGoogleDrive::dispatch(
//                 $attachment->id,
//                 $tempPath,
//                 $folderIdentity,
//                 $category,
//                 'user',
//                 'user',
//                 $filePrefix
//             )->onQueue('ticket-heavy');

//             $attachments[] = [
//                 'id'            => $attachment->id,
//                 'original_name' => $attachment->original_name,
//                 'mime_type'     => $attachment->mime_type,
//                 'size'          => $attachment->size,
//                 'status'        => 'pending',
//                 'uploaded_at'   => $attachment->created_at->toDateTimeString(),
//                 'drive_file_id' => $attachment->drive_file_id,
//             ];
//         }

//         return response()->json([
//             'message'     => 'File berhasil diupload.',
//             'attachments' => $attachments,
//         ], 201);
//     }

//     public function destroy($ticketId, $attachmentId): JsonResponse
//     {
//         $attachment = Ticketattachments::findOrFail($attachmentId);
//         $ticket = $attachment->ticket;
//         if (!$ticket || $ticket->user_id !== Auth::id()) {
//             abort(403, 'You are not allowed to delete this attachment.');
//         }
//         $attachment->delete();

//         return response()->json(['message' => 'Attachment berhasil dihapus.']);
//     }
// }
class TicketAttachmentController extends Controller
{
       public function store(Request $request, string $ticketId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'files'   => 'required|array|max:3',
            'files.*' => 'file|max:5048|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip,txt',
        ]);

        $ticket = Tickets::where('id', $ticketId)
            ->where('user_id', $user->id)
            ->where('status', 'Open')
            ->firstOrFail();

        // Cek total attachment tidak melebihi 10
        $existingCount = $ticket->attachments()->count();
        $uploadCount   = count($request->file('files'));

        if ($existingCount + $uploadCount > 10) {
            return response()->json([
                'message' => "Cannot upload {$uploadCount} files. Current: {$existingCount}, max: 10.",
            ], 422);
        }

        $username    = Str::slug($user->username ?? $user->id);
        $uploaded    = [];

        foreach ($request->file('files') as $index => $file) {
            $date     = now()->format('Y-m-d');
            $title    = Str::slug($ticket->title);
            $ext      = $file->getClientOriginalExtension();
            $suffix   = $existingCount + $index + 1;
            $fileName = "{$username}-{$date}-{$title}-{$suffix}.{$ext}";
            $s3Folder = "bdtix-user-attachments/{$username}";

            $tempPath = Storage::disk('local')->putFileAs(
                "temp-attachments/{$username}",
                $file,
                $fileName
            );

            if (!$tempPath) {
                Log::error('ATTACHMENT_STORE_TEMP_FAILED', [
                    'ticket_id'     => $ticket->id,
                    'original_name' => $file->getClientOriginalName(),
                ]);
                continue;
            }

            try {
                $attachment = Ticketattachments::create([
                    'id'            => (string) Str::uuid(),
                    'ticket_id'     => $ticket->id,
                    'user_id'       => $user->id,
                    'file_name'     => $fileName,
                    'file_path'     => "{$s3Folder}/{$fileName}",
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'status'        => 'pending',
                ]);
            } catch (\Throwable $e) {
                Log::error('ATTACHMENT_STORE_CREATE_FAILED', [
                    'ticket_id' => $ticket->id,
                    'error'     => $e->getMessage(),
                ]);
                continue;
            }

            UploadAttachmentToS3::dispatch(
                $attachment->id,
                $tempPath,
                $s3Folder,
                $fileName
            )->onQueue('ticket-heavy');

            $uploaded[] = [
                'id'            => $attachment->id,
                'original_name' => $attachment->original_name,
                'status'        => $attachment->status,
            ];

            Log::info('ATTACHMENT_STORE_QUEUED', [
                'ticket_id'     => $ticket->id,
                'attachment_id' => $attachment->id,
                'file_name'     => $fileName,
            ]);
        }

        return response()->json([
            'message'   => 'Upload queued successfully.',
            'uploaded'  => $uploaded,
        ]);
    }

    public function destroy(string $attachmentId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $attachment = Ticketattachments::where('id', $attachmentId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Pastikan tiketnya masih Open
        $ticket = Tickets::where('id', $attachment->ticket_id)
            ->where('status', 'Open')
            ->firstOrFail();

        Log::info('ATTACHMENT_DELETE_START', [
            'attachment_id' => $attachment->id,
            'file_path'     => $attachment->file_path,
            'user_id'       => $user->id,
        ]);

        // Hapus record DB dulu
        $filePath = $attachment->file_path;
        $attachment->delete();

        // Dispatch job hapus dari S3
        DeleteAttachmentFromS3::dispatch($filePath)
            ->onQueue('ticket-heavy');

        return response()->json([
            'message' => 'Attachment deleted successfully.',
        ]);
    }
   
   public function signedUrl(string $attachmentId)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $attachment = Ticketattachments::where('id', $attachmentId)
        ->where('status', 'uploaded')
        ->firstOrFail();

    if ($attachment->user_id !== $user->id && !$user->hasAnyRole(['admin', 'executor'])) {
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
