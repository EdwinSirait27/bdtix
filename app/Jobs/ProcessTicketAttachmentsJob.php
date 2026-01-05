<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Tickets;
use App\Models\User;
use App\Services\NextcloudService;
use Illuminate\Support\Str;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Ticketattachments;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessTicketAttachmentsJob implements ShouldQueue

{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 120;

    public function __construct(
        public string $ticketId,
        public array  $files,
        public string $userId
    ) {}

    public function handle(): void
    {
        Log::info('ATTACHMENT_JOB_START', [
            'ticket_id'   => $this->ticketId,
            'file_count'  => count($this->files),
        ]);

        if (empty($this->files)) {
            return;
        }

        $ticket = Tickets::find($this->ticketId);
        $user   = User::find($this->userId);

        if (!$ticket || !$user) {
            Log::error('ATTACHMENT_JOB_INVALID_DATA', [
                'ticket_id' => $this->ticketId,
                'user_id'   => $this->userId,
            ]);
            return;
        }

        $basePath = $this->buildBasePath($ticket, $user);

        // 1️⃣ ensure folder
        try {
            NextcloudService::makeDir($basePath);
        } catch (\Throwable $e) {
            Log::error('ATTACHMENT_MKDIR_FAILED', [
                'ticket_id' => $ticket->id,
                'error'     => $e->getMessage(),
            ]);
            return;
        }

        // 2️⃣ upload files
        foreach ($this->files as $file) {
            try {
                $this->processSingleFile($ticket, $basePath, $file);
            } catch (\Throwable $e) {
                continue; // lanjut file lain
            }
        }

        // 3️⃣ share folder (opsional)
        try {
            $shareUrl = NextcloudService::shareFolder($basePath);

            $ticket->update([
                'attachment_folder' => $basePath,
                'attachment_url'    => $shareUrl,
            ]);
        } catch (\Throwable $e) {
            Log::warning('ATTACHMENT_SHARE_FAILED', [
                'ticket_id' => $ticket->id,
                'error'     => $e->getMessage(),
            ]);
        }

        Log::info('ATTACHMENT_JOB_DONE', [
            'ticket_id' => $ticket->id,
        ]);
    }



    protected function buildBasePath(Tickets $ticket, User $user): string
    {
        return sprintf(
            'ticket/%s/%s/%s',
            Str::slug($ticket->category),
            Str::slug($user->username),
            $ticket->id
        );
    }

    protected function processSingleFile(
    Tickets $ticket,
    string $basePath,
    array $file
): void {
    if (empty($file['path']) || !Storage::exists($file['path'])) {
        Log::warning('ATTACHMENT_TMP_NOT_FOUND', [
            'ticket_id' => $ticket->id,
            'path'      => $file['path'] ?? null,
        ]);
        return;
    }

    $content = Storage::get($file['path']);

    if (empty($content)) {
        Log::error('ATTACHMENT_EMPTY_CONTENT', [
            'ticket_id' => $ticket->id,
            'path'      => $file['path'],
        ]);
        return;
    }

    // ✅ FIX FILENAME (KEEP EXTENSION)
//     $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
//   $extension    = pathinfo($file['name'], PATHINFO_EXTENSION);
// if (!$extension && !empty($file['mime'])) {
//     $extension = match ($file['mime']) {
//         'image/jpeg' => 'jpg',
//         'image/png'  => 'png',
//         'image/webp' => 'webp',
//         'application/pdf' => 'pdf',
//         default => 'bin',
//     };
// }
//  $filename = time() . '_' . Str::slug($originalName) . '.' . strtolower($extension);
$originalName = pathinfo($file['name'], PATHINFO_FILENAME);
$extension    = pathinfo($file['name'], PATHINFO_EXTENSION);

$filename = time() . '_' . Str::slug($originalName) . '.' . strtolower($extension);


    // ✅ SAFE MIME
    $mime = $file['mime'] ?? 'application/octet-stream';

    NextcloudService::upload(
        $basePath,
        $filename,
        $content,
        $mime
    );
    Ticketattachments::create([
        'id'        => (string) Str::uuid(),
        'ticket_id' => $ticket->id,
        'file_name' => $filename,
        'file_path' => "{$basePath}/{$filename}",
    ]);

    Storage::delete($file['path']);

    Log::info('ATTACHMENT_UPLOADED', [
        'ticket_id' => $ticket->id,
        'file'      => $filename,
    ]);
}

}
// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     public int $tries   = 3;
//     public int $timeout = 120;

//     public function __construct(
//         public string $ticketId,
//         public array  $files,
//         public string $userId
//     ) {}

//     public function handle(): void
//     {
//         Log::info('ATTACHMENT_JOB_START', [
//             'ticket_id'   => $this->ticketId,
//             'file_count'  => count($this->files),
//         ]);

//         if (empty($this->files)) {
//             return;
//         }

//         $ticket = Tickets::find($this->ticketId);
//         $user   = User::find($this->userId);

//         if (!$ticket || !$user) {
//             Log::error('ATTACHMENT_JOB_INVALID_DATA', [
//                 'ticket_id' => $this->ticketId,
//                 'user_id'   => $this->userId,
//             ]);
//             return;
//         }

//         $basePath = $this->buildBasePath($ticket, $user);

//         // 1️⃣ ensure folder
//         try {
//             NextcloudService::makeDir($basePath);
//         } catch (\Throwable $e) {
//             Log::error('ATTACHMENT_MKDIR_FAILED', [
//                 'ticket_id' => $ticket->id,
//                 'error'     => $e->getMessage(),
//             ]);
//             return;
//         }

//         // 2️⃣ upload files
//         foreach ($this->files as $file) {
//             try {
//                 $this->processSingleFile($ticket, $basePath, $file);
//             } catch (\Throwable $e) {
//                 continue; // lanjut file lain
//             }
//         }

//         // 3️⃣ share folder (opsional)
//         try {
//             $shareUrl = NextcloudService::shareFolder($basePath);

//             $ticket->update([
//                 'attachment_folder' => $basePath,
//                 'attachment_url'    => $shareUrl,
//             ]);
//         } catch (\Throwable $e) {
//             Log::warning('ATTACHMENT_SHARE_FAILED', [
//                 'ticket_id' => $ticket->id,
//                 'error'     => $e->getMessage(),
//             ]);
//         }

//         Log::info('ATTACHMENT_JOB_DONE', [
//             'ticket_id' => $ticket->id,
//         ]);
//     }



//     protected function buildBasePath(Tickets $ticket, User $user): string
//     {
//         return sprintf(
//             'ticket/%s/%s/%s',
//             Str::slug($ticket->category),
//             Str::slug($user->username),
//             $ticket->id
//         );
//     }

//     protected function processSingleFile(
//         Tickets $ticket,
//         string $basePath,
//         array $file
//     ): void {
//         if (empty($file['path']) || !Storage::exists($file['path'])) {
//             Log::warning('ATTACHMENT_TMP_NOT_FOUND', [
//                 'ticket_id' => $ticket->id,
//                 'path'      => $file['path'] ?? null,
//             ]);
//             return;
//         }

//         try {
//             $content  = Storage::get($file['path']);
//             $filename = time() . '_' . Str::slug($file['name']);

//             NextcloudService::upload(
//                 $basePath,
//                 $filename,
//                 $content,
//                 $file['mime']
//             );

//             Ticketattachments::create([
//                 'id'        => (string) Str::uuid(),
//                 'ticket_id' => $ticket->id,
//                 'file_name' => $filename,
//                 'file_path' => "{$basePath}/{$filename}",
//             ]);

//             Log::info('ATTACHMENT_UPLOADED', [
//                 'ticket_id' => $ticket->id,
//                 'file'      => $filename,
//             ]);
//         } finally {
//             Storage::delete($file['path']);
//         }
//     }
// }
























// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     public int $tries   = 3;
//     public int $timeout = 120;

//     public function __construct(
//         public string $ticketId,
//         public array  $files,
//         public string $userId
//     ) {}

//     public function handle(): void
//     {
//         Log::info('ATTACHMENT_JOB_START', [
//             'ticket_id' => $this->ticketId,
//             'files'     => count($this->files),
//         ]);

//         if (empty($this->files)) {
//             Log::info('ATTACHMENT_JOB_SKIP_EMPTY', [
//                 'ticket_id' => $this->ticketId,
//             ]);
//             return;
//         }

//         try {
//             $ticket = Tickets::findOrFail($this->ticketId);
//             $user   = User::findOrFail($this->userId);

//             $category = Str::slug($ticket->category);
//             $username = Str::slug($user->username);
//             $basePath = "ticket/{$category}/{$username}/{$ticket->id}";

//             NextcloudService::makeDir($basePath);

//             foreach ($this->files as $file) {

//                 if (
//                     empty($file['path']) ||
//                     !Storage::exists($file['path'])
//                 ) {
//                     Log::warning('ATTACHMENT_TMP_NOT_FOUND', [
//                         'ticket_id' => $ticket->id,
//                         'path'      => $file['path'] ?? null,
//                     ]);
//                     continue;
//                 }

//                 $content  = Storage::get($file['path']);
//                 $filename = time() . '_' . Str::slug($file['name']);

//                 NextcloudService::upload(
//                     $basePath,
//                     $filename,
//                     $content,
//                     $file['mime']
//                 );

//                 Ticketattachments::create([
//                     'id'        => (string) Str::uuid(),
//                     'ticket_id' => $ticket->id,
//                     'file_name' => $filename,
//                     'file_path' => "{$basePath}/{$filename}",
//                 ]);

//                 Storage::delete($file['path']);
//             }

//             $shareUrl = NextcloudService::shareFolder($basePath);

//             $ticket->update([
//                 'attachment_folder' => $basePath,
//                 'attachment_url'    => $shareUrl,
//             ]);

//             Log::info('ATTACHMENT_JOB_DONE', [
//                 'ticket_id' => $ticket->id,
//                 'share_url' => $shareUrl,
//             ]);

//         } catch (\Throwable $e) {

//             Log::error('ATTACHMENT_JOB_FAILED', [
//                 'ticket_id' => $this->ticketId,
//                 'error'     => $e->getMessage(),
//             ]);

//             foreach ($this->files as $file) {
//                 if (
//                     isset($file['path']) &&
//                     Storage::exists($file['path'])
//                 ) {
//                     Storage::delete($file['path']);
//                 }
//             }


//             throw $e;
//         }
//     }
// }
// public function handle(): void
// {
//     Log::info('ATTACHMENT_JOB_START', [
//         'ticket_id' => $this->ticketId,
//         'file_count' => count($this->files),
//     ]);

//     if (empty($this->files)) {
//         return;
//     }

//     $ticket = Tickets::find($this->ticketId);
//     $user   = User::find($this->userId);

//     if (!$ticket || !$user) {
//         Log::error('ATTACHMENT_JOB_INVALID_DATA', [
//             'ticket_id' => $this->ticketId,
//             'user_id'   => $this->userId,
//         ]);
//         return; // ❗ jangan FAIL queue
//     }

//     $basePath = sprintf(
//         'ticket/%s/%s/%s',
//         Str::slug($ticket->category),
//         Str::slug($user->username),
//         $ticket->id
//     );

//     // 1️⃣ makeDir (JANGAN FAIL JOB)
//     try {
//         NextcloudService::makeDir($basePath);
//     } catch (\Throwable $e) {
//         Log::error('ATTACHMENT_MKDIR_FAILED', [
//             'ticket_id' => $ticket->id,
//             'error'     => $e->getMessage(),
//         ]);
//         return; // ❗ stop job, tapi tidak FAIL
//     }

//     // 2️⃣ process file satu-satu
//     foreach ($this->files as $file) {

//         if (empty($file['path']) || !Storage::exists($file['path'])) {
//             Log::warning('ATTACHMENT_TMP_NOT_FOUND', [
//                 'ticket_id' => $ticket->id,
//                 'path'      => $file['path'] ?? null,
//             ]);
//             continue;
//         }

//         try {
//             $content  = Storage::get($file['path']);
//             $filename = time().'_'.Str::slug($file['name']);

//             NextcloudService::upload(
//                 $basePath,
//                 $filename,
//                 $content,
//                 $file['mime']
//             );

//             Ticketattachments::create([
//                 'id'        => (string) Str::uuid(),
//                 'ticket_id' => $ticket->id,
//                 'file_name' => $filename,
//                 'file_path' => "{$basePath}/{$filename}",
//             ]);

//             Log::info('ATTACHMENT_UPLOADED', [
//                 'ticket_id' => $ticket->id,
//                 'file'      => $filename,
//             ]);

//         } catch (\Throwable $e) {
//             Log::error('ATTACHMENT_UPLOAD_FAILED', [
//                 'ticket_id' => $ticket->id,
//                 'file'      => $file['name'] ?? null,
//                 'error'     => $e->getMessage(),
//             ]);
//         } finally {
//             Storage::delete($file['path']);
//         }
//     }

//     // 3️⃣ share folder (OPSIONAL)
//     try {
//         $shareUrl = NextcloudService::shareFolder($basePath);

//         $ticket->update([
//             'attachment_folder' => $basePath,
//             'attachment_url'    => $shareUrl,
//         ]);

//     } catch (\Throwable $e) {
//         Log::warning('ATTACHMENT_SHARE_FAILED', [
//             'ticket_id' => $ticket->id,
//             'error'     => $e->getMessage(),
//         ]);
//     }

//     Log::info('ATTACHMENT_JOB_DONE', [
//         'ticket_id' => $ticket->id,
//     ]);
// }
 // protected function shareFolderSafely(Tickets $ticket, string $basePath): void
    // {
    //     try {
    //         $shareUrl = NextcloudService::shareFolder($basePath);

    //         $ticket->update([
    //             'attachment_folder' => $basePath,
    //             'attachment_url'    => $shareUrl,
    //         ]);

    //         Log::info('ATTACHMENT_FOLDER_SHARED', [
    //             'ticket_id' => $ticket->id,
    //             'url'       => $shareUrl,
    //         ]);

    //     } catch (Throwable $e) {
    //         // ❗ jangan FAIL job
    //         Log::warning('ATTACHMENT_SHARE_FAILED', [
    //             'ticket_id' => $ticket->id,
    //             'error'     => $e->getMessage(),
    //         ]);
    //     }
    // }
    // public function handle(): void
    // {
    //     Log::info('ATTACHMENT_JOB_START', [
    //         'ticket_id' => $this->ticketId,
    //         'file_count'=> count($this->files),
    //     ]);

    //     if (empty($this->files)) {
    //         Log::info('ATTACHMENT_JOB_SKIP_EMPTY', [
    //             'ticket_id' => $this->ticketId,
    //         ]);
    //         return;
    //     }

    //     $ticket = Tickets::findOrFail($this->ticketId);
    //     $user   = User::findOrFail($this->userId);

    //     $basePath = $this->buildBasePath($ticket, $user);

    //     // 1️⃣ Pastikan folder ada (critical)
    //     NextcloudService::makeDir($basePath);

    //     foreach ($this->files as $file) {
    //         $this->processSingleFile($ticket, $basePath, $file);
    //     }

    //     // 2️⃣ Share folder (NON-CRITICAL)
    //     $this->shareFolderSafely($ticket, $basePath);

    //     Log::info('ATTACHMENT_JOB_DONE', [
    //         'ticket_id' => $ticket->id,
    //     ]);
    // }