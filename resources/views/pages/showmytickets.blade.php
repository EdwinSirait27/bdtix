@extends('layouts.app')
@section('title', 'BD Tickets Queue' . ($ticket->queue_number ?? ''))
@section('header', 'BD Detail Ticket ')
@section('subtitle', 'BD Detail and ticket status')
@section('content')
    <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
        {{-- Ticket Info --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg p-4 sm:p-6">
            <div class="flex flex-col space-y-3">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                        Title : {{ $ticket->title ?? '-' }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Ticket Queue Number {{ $ticket->queue_number ?? '-' }} • Created
                        {{ $ticket->created_at?->format('d F Y H:i') ?? '-' }}
                    </p>
                </div>
                <div>
                    <span
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold {{ $ticket->badge_class ?? '' }}">
                        <span class="w-2 h-2 rounded-full bg-white"></span>
                        {{ $ticket->status ?? '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Ticket Meta --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 ">Created By</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->user?->employee?->employee_name ?? ($ticket->user?->email ?? '-') }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 ">Category</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->category ?? '-' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 ">Sub Category</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->sub_category ?? '-' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 ">Description User</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->description ?? '-' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 ">Location User</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->store->name ?? '-' }}
                </p>
            </div>
        </div>

        {{-- Executor --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
            <p class="text-xs text-slate-500 dark:text-slate-400 ">Executor</p>
            <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                {{ $executor?->employee?->employee_name ?? '-' }}
            </p>
        </div>

        {{-- Notes IT --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
            <p class="text-xs text-slate-500 dark:text-slate-400 ">BD Notes</p>
            <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                {{ $ticket->notes_executor ?? '-' }}
            </p>
        </div>

        {{-- Progressed At --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
            <p class="text-xs text-slate-500 dark:text-slate-400 ">Progressed At</p>
            <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                {{ $ticket->progressed_at?->format('d F Y H:i') ?? '-' }}
            </p>
        </div>

        {{-- Estimation --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
            <p class="text-xs text-slate-500 dark:text-slate-400 ">Estimation</p>
            <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                {{ $estimationDate ?? '-' }}
            </p>
        </div>

        {{-- Estimation To --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
            <p class="text-xs text-slate-500 dark:text-slate-400 ">Estimation To</p>
            <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                {{ $estimationToDate ?? '-' }}
            </p>
        </div>

        {{-- User Attachments --}}
        @if ($ticket->attachments && $ticket->attachments->count())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold mb-4 text-slate-900 dark:text-white">Attachments</h3>
                <ul class="space-y-2">
                    @foreach ($ticket->attachments as $file)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8 2a4 4 0 00-4 4v8a6 6 0 0012 0V6a2 2 0 10-4 0v7a1 1 0 102 0V6a4 4 0 00-8 0v8a4 4 0 008 0V6" />
                            </svg>

                            @if ($file->status === 'uploaded')
                                <button type="button" onclick="openSignedUrl('{{ $file->id }}')"
                                    class="text-sm text-blue-400 hover:underline text-left truncate">
                                    {{-- {{ $file->original_name ?? $file->file_name }} --}}
                                    {{ $file->file_name }}

                                </button>
                            @else
                                <span class="text-sm text-slate-400 truncate">
                                    {{-- {{ $file->original_name ?? $file->file_name ?? '-' }} --}}
                                    {{ $file->file_name ?? '-' }}
                                    <span class="text-xs text-yellow-500">(processing...)</span>
                                </span>
                            @endif

                            @if (!empty($file->human_size))
                                <span class="text-xs text-slate-500 flex-shrink-0">({{ $file->human_size }})</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Executor Attachments (hanya tampil jika Closed) --}}
        @if ($ticket->executorAttachments && $ticket->executorAttachments->count())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold mb-4 text-slate-900 dark:text-white">Executor Attachments</h3>
                <ul class="space-y-2">
                    @foreach ($ticket->executorAttachments as $file)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8 2a4 4 0 00-4 4v8a6 6 0 0012 0V6a2 2 0 10-4 0v7a1 1 0 102 0V6a4 4 0 00-8 0v8a4 4 0 008 0V6" />
                            </svg>

                            @if ($file->status === 'uploaded')
                                <button type="button" onclick="openSignedUrlForExecutor('{{ $file->id }}')"
                                    class="text-sm text-blue-400 hover:underline text-left truncate">
                                    {{-- {{ $file->original_name ?? $file->file_name }} --}}
                                    {{ $file->file_name }}

                                </button>
                            @else
                                <span class="text-sm text-slate-400 truncate">
                                    {{-- {{ $file->original_name ?? $file->file_name }} --}}
                                    {{ $file->file_name }}
                                    <span class="text-xs text-yellow-500">(processing...)</span>
                                </span>
                            @endif

                            @if (!empty($file->human_size))
                                <span class="text-xs text-slate-500 flex-shrink-0">({{ $file->human_size }})</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Activity --}}
        @if ($ticket->replies && $ticket->replies->count())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold mb-4 text-slate-900 dark:text-white">Activity</h3>
                <div class="space-y-3">
                    @foreach ($ticket->replies as $reply)
                        <div class="p-3 sm:p-4 rounded-xl bg-slate-50 dark:bg-slate-800 shadow">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 mb-2">
                                <p class="text-xs sm:text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ $reply->user?->employee?->employee_name ?? ($reply->user?->email ?? '-') }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $reply->created_at?->diffForHumans() ?? '-' }}
                                </p>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300">
                                {{ $reply->message ?? '-' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Back Button --}}
        <div class="flex justify-end pb-4">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 sm:px-5 py-2.5 rounded-xl text-sm
                      bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white
                      hover:bg-slate-300 dark:hover:bg-slate-600 transition shadow-md">
                Back to Dashboard
            </a>
        </div>
    </div>

    {{-- Modal Preview Attachment --}}
   <div id="previewModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4" onclick="if(event.target===this)closePreviewModal()">
    <div class="relative inline-flex flex-col items-center">
        <p id="previewModalTitle" class="text-xs text-slate-400 mb-2 self-start truncate max-w-sm"></p>
        <div class="relative">
            <button type="button" onclick="closePreviewModal()"
                class="absolute -top-2 -right-2 z-10 bg-slate-700 hover:bg-red-500 text-white rounded-full p-1 transition shadow-lg">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img id="previewModalImg" src="" alt=""
                class="hidden rounded-xl shadow-2xl object-contain"
                style="max-width:70vw; max-height:80vh;">
            <iframe id="previewModalIframe" src="" frameborder="0" allowfullscreen
                class="hidden rounded-xl shadow-2xl"
                style="width:70vw; height:80vh;"></iframe>
        </div>
    </div>
</div>

<div id="previewModalForExecutor" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4" onclick="if(event.target===this)closePreviewModalForExecutor()">
    <div class="relative inline-flex flex-col items-center">
        <p id="previewModalForExecutorTitle" class="text-xs text-slate-400 mb-2 self-start truncate max-w-sm"></p>
        <div class="relative">
            <button type="button" onclick="closePreviewModalForExecutor()"
                class="absolute -top-2 -right-2 z-10 bg-slate-700 hover:bg-red-500 text-white rounded-full p-1 transition shadow-lg">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img id="previewModalForExecutorImg" src="" alt=""
                class="hidden rounded-xl shadow-2xl object-contain"
                style="max-width:70vw; max-height:80vh;">
            <iframe id="previewModalForExecutorIframe" src="" frameborder="0" allowfullscreen
                class="hidden rounded-xl shadow-2xl"
                style="width:70vw; height:80vh;"></iframe>
        </div>
    </div>
</div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
              async function openSignedUrl(attachmentId) {
    try {
        const res = await fetch(`/attachments/${attachmentId}/signed-url`, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin',
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Failed to get URL');

        const img = document.getElementById('previewModalImg');
        const iframe = document.getElementById('previewModalIframe');
        const title = document.getElementById('previewModalTitle');
        const modal = document.getElementById('previewModal');

        title.textContent = data.file_name ?? 'Attachment';

        const imageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (imageMimes.includes(data.mime_type)) {
            img.src = data.url;
            img.classList.remove('hidden');
            iframe.classList.add('hidden');
            iframe.src = '';
        } else if (data.mime_type === 'application/pdf') {
            iframe.src = data.url;
            iframe.classList.remove('hidden');
            img.classList.add('hidden');
            img.src = '';
        } else {
            // Download langsung kalau bukan gambar/pdf
            const a = document.createElement('a');
            a.href = data.url;
            a.download = data.file_name ?? 'attachment';
            a.click();
            return;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Failed', text: e.message || 'Failed to open file.', background: '#0f172a', color: '#e2e8f0' });
    }
}

function closePreviewModal() {
    const modal = document.getElementById('previewModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('previewModalImg').src = '';
    document.getElementById('previewModalImg').classList.add('hidden');
    document.getElementById('previewModalIframe').src = '';
    document.getElementById('previewModalIframe').classList.add('hidden');
}

            document.getElementById('previewModal')?.addEventListener('click', function(e) {
                if (e.target === this) closePreviewModal();
            });






             async function openSignedUrlForExecutor(attachmentId) {
    try {
        const res = await fetch(`/attachmentsforexecutor/${attachmentId}/signed-url-for-executor`, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin',
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Failed to get URL');

        const img = document.getElementById('previewModalForExecutorImg');
        const iframe = document.getElementById('previewModalForExecutorIframe');
        const title = document.getElementById('previewModalForExecutorTitle');
        const modal = document.getElementById('previewModalForExecutor');

        title.textContent = data.file_name ?? 'Attachment';

        const imageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (imageMimes.includes(data.mime_type)) {
            img.src = data.url;
            img.classList.remove('hidden');
            iframe.classList.add('hidden');
            iframe.src = '';
        } else if (data.mime_type === 'application/pdf') {
            iframe.src = data.url;
            iframe.classList.remove('hidden');
            img.classList.add('hidden');
            img.src = '';
        } else {
            // Download langsung kalau bukan gambar/pdf
            const a = document.createElement('a');
            a.href = data.url;
            a.download = data.file_name ?? 'attachment';
            a.click();
            return;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Failed', text: e.message || 'Failed to open file.', background: '#0f172a', color: '#e2e8f0' });
    }
}

function closePreviewModalForExecutor() {
    const modal = document.getElementById('previewModalForExecutor');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('previewModalForExecutorImg').src = '';
    document.getElementById('previewModalForExecutorImg').classList.add('hidden');
    document.getElementById('previewModalForExecutorIframe').src = '';
    document.getElementById('previewModalForExecutorIframe').classList.add('hidden');
}

    function deleteAttachmentForExecutor(fileId) {
        if (!confirm('Delete this attachment?')) return;
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch(`/attachmentsforexecutor/${fileId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            credentials: 'same-origin'
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Delete failed.');
            document.getElementById(`attachment-item-${fileId}`)?.remove();
            toastr.success(data.message || 'Attachment deleted.');

            const list = document.getElementById('attachment-list');
            if (list && list.children.length === 0) {
                document.getElementById('existing-attachments-for-executor')?.classList.add('hidden');
            }
        })
        .catch(err => toastr.error(err.message || 'Delete failed.'));
    }

    document.getElementById('previewModalForExecutor')?.addEventListener('click', function(e) {
        if (e.target === this) closePreviewModalForExecutor();
    });

            function deleteAttachmentForExecutor(fileId) {
                if (!confirm('Delete this attachment?')) return;
                const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;
                fetch(`/attachmentsforexecutor/${fileId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF
                        },
                        credentials: 'same-origin'
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) throw new Error(data.message || 'Delete failed.');
                        document.getElementById(`attachment-item-${fileId}`)?.remove();
                        toastr.success(data.message || 'Attachment deleted.');

                        const list = document.getElementById('attachment-list');
                        if (list && list.children.length === 0) {
                            document.getElementById('existing-attachments-for-executor')?.classList.add('hidden');
                        }
                    })
                    .catch(err => toastr.error(err.message || 'Delete failed.'));
            }

           
        </script>
    @endpush
@endsection
