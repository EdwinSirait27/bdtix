<?php

namespace App\Http\Controllers;

use App\Jobs\UploadAttachmentToGoogleDrive;
use App\Models\TicketExecutorAttachment;
use App\Models\Tickets;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketExecutorAttachmentController extends Controller
{
    public function store(Request $request, $ticketId): JsonResponse
    {
        $request->validate([
            'files'   => ['required', 'array', 'max:10'],
            'files.*' => [
                'file',
                'max:20480',
                'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip,txt',
            ],
        ]);

        $ticket = Tickets::findOrFail($ticketId);
        $isAdmin = Auth::user()?->hasRole('admin');
        if (!$isAdmin && $ticket->executor_id !== Auth::id()) {
            abort(403, 'You are not allowed to upload executor attachments for this ticket.');
        }
        $owner = $ticket->user;
        $folderIdentity = $owner?->employee?->nip ?? $owner?->nip ?? $ticket->user_id ?? (string) $ticket->user_id;
        $filePrefix = Auth::user()->employee->nip ?? Auth::user()->nip ?? (string) Auth::id();
        $category = $ticket->category;
        $attachments = [];

        foreach ($request->file('files') as $file) {
            $tempPath = $file->store('temp-attachments', 'local');

            $attachment = TicketExecutorAttachment::create([
                'id'            => (string) Str::uuid(),
                'ticket_id'     => $ticketId,
                'executor_id'   => Auth::id(),
                'file_name'     => $file->getClientOriginalName(),
                'file_path'     => $tempPath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
                'status'        => 'pending',
            ]);

            UploadAttachmentToGoogleDrive::dispatch(
                $attachment->id,
                $tempPath,
                $folderIdentity,
                $category,
                'executor',
                'executor',
                $filePrefix
            );

            $attachments[] = [
                'id'            => $attachment->id,
                'original_name' => $attachment->original_name,
                'mime_type'     => $attachment->mime_type,
                'size'          => $attachment->size,
                'status'        => 'pending',
                'uploaded_at'   => $attachment->created_at->toDateTimeString(),
                'drive_file_id' => $attachment->drive_file_id,
            ];
        }

        return response()->json([
            'message'     => 'Bukti pengerjaan berhasil diupload.',
            'attachments' => $attachments,
        ], 201);
    }

    public function destroy($ticketId, $attachmentId): JsonResponse
    {
        $attachment = TicketExecutorAttachment::findOrFail($attachmentId);
        $ticket = $attachment->ticket;
        $isAdmin = Auth::user()?->hasRole('admin');
        if (!$ticket || (!$isAdmin && $ticket->executor_id !== Auth::id())) {
            abort(403, 'You are not allowed to delete this attachment.');
        }
        $attachment->delete();

        return response()->json(['message' => 'Attachment berhasil dihapus.']);
    }
}
