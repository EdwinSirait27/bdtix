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
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessTicketAttachmentsJob implements ShouldQueue
// {
//    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     public $timeout = 300; // 5 menit
//     public $tries   = 3;

//     public function __construct(
//         public string $ticketId,
//         public array  $files,
//         public User   $user
//     ) {}

//     public function handle()
//     {
//         $ticket = Tickets::findOrFail($this->ticketId);

//         $category = Str::slug($ticket->category);
//         $userName = Str::slug($this->user->username);
//         $basePath = "ticket/{$category}/{$userName}/{$ticket->id}";

//         NextcloudService::makeDir($basePath);

//         foreach ($this->files as $file) {
//             $filename = time().'_'.$file->getClientOriginalName();

//             NextcloudService::upload(
//                 $basePath,
//                 $filename,
//                 file_get_contents($file->getRealPath()),
//                 $file->getMimeType()
//             );

//             Ticketattachments::create([
//                 'id'        => Str::uuid(),
//                 'ticket_id' => $ticket->id,
//                 'file_name' => $filename,
//                 'file_path' => "{$basePath}/{$filename}",
//             ]);
//         }

//         $shareUrl = NextcloudService::shareFolder($basePath);

//         $ticket->update([
//             'attachment_folder' => $basePath,
//             'attachment_url'    => $shareUrl,
//         ]);
//     }
// }
{
    use Dispatchable, Queueable, SerializesModels;

    public function __construct(
        public string $ticketId,
        public array $files,
        public string $userId
    ) {}

    public function handle()
    {
        $ticket = Tickets::findOrFail($this->ticketId);
        $user   = User::findOrFail($this->userId);

        $category = Str::slug($ticket->category);
        $username = Str::slug($user->username);
        $basePath = "ticket/{$category}/{$username}/{$ticket->id}";

        NextcloudService::makeDir($basePath);

        foreach ($this->files as $file) {
            $content = Storage::get($file['path']);
            $filename = time().'_'.$file['name'];

            NextcloudService::upload(
                $basePath,
                $filename,
                $content,
                $file['mime']
            );

            Ticketattachments::create([
                'id'        => Str::uuid(),
                'ticket_id' => $ticket->id,
                'file_name' => $filename,
                'file_path' => "{$basePath}/{$filename}",
            ]);

            // 🔥 hapus file sementara
            Storage::delete($file['path']);
        }

        $shareUrl = NextcloudService::shareFolder($basePath);

        $ticket->update([
            'attachment_folder' => $basePath,
            'attachment_url'    => $shareUrl,
        ]);
    }
}