@extends('layouts.app')
@section('title', 'Tickets Queue' . ($ticket->queue_number ?? ''))
@section('header', 'Detail Ticket ')
@section('subtitle', 'Detail and ticket status')
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
                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Created By</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->user?->employee?->employee_name ?? $ticket->user?->email ?? '-' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Category</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->category ?? '-' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Description User</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->description ?? '-' }}
                </p>
            </div>
        </div>

        {{-- Executor --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Executor</p>
            <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                {{ $ticket->executor?->employee?->employee_name ?? '-' }}
            </p>
        </div>

        {{-- Notes IT --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Notes IT</p>
            <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                {{ $ticket->notes_executor ?? '-' }}
            </p>
        </div>

        {{-- Progressed At --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Progressed At</p>
            <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                {{ $ticket->progressed_at?->format('d F Y H:i') ?? '-' }}
            </p>
        </div>

        {{-- Estimation --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Estimation</p>
            <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                {{ $estimationDate ?? '-' }}
            </p>
        </div>

        {{-- Estimation To --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Estimation To</p>
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
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 2a4 4 0 00-4 4v8a6 6 0 0012 0V6a2 2 0 10-4 0v7a1 1 0 102 0V6a4 4 0 00-8 0v8a4 4 0 008 0V6" />
                            </svg>
                            @if ($file->drive_file_id && $file->status === 'uploaded')
                                <button type="button"
                                    onclick="openPreviewModal('https://drive.google.com/file/d/{{ $file->drive_file_id }}/preview', '{{ addslashes($file->original_name ?? $file->file_name) }}')"
                                    class="text-sm text-blue-400 hover:underline text-left">
                                    {{ $file->original_name ?? $file->file_name }}
                                </button>
                            @else
                                <span class="text-blue-500 hover:underline text-sm">
                                    {{ $file->original_name ?? $file->file_name ?? '-' }}
                                    <span class="text-xs text-yellow-500">(processing...)</span>
                                </span>
                            @endif
                            @if (!empty($file->human_size))
                                <span class="text-xs text-slate-500">({{ $file->human_size }})</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Executor Attachments (hanya tampil jika Closed) --}}
        @if ($ticket->status === 'Closed' && $ticket->executorAttachments && $ticket->executorAttachments->count())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold mb-4 text-slate-900 dark:text-white">Executor Attachments</h3>
                <ul class="space-y-2">
                    @foreach ($ticket->executorAttachments as $file)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 2a4 4 0 00-4 4v8a6 6 0 0012 0V6a2 2 0 10-4 0v7a1 1 0 102 0V6a4 4 0 00-8 0v8a4 4 0 008 0V6" />
                            </svg>
                            @if ($file->drive_file_id && $file->status === 'uploaded')
                                <button type="button"
                                    onclick="openPreviewModal('https://drive.google.com/file/d/{{ $file->drive_file_id }}/preview', '{{ addslashes($file->original_name ?? $file->file_name) }}')"
                                    class="text-sm text-blue-400 hover:underline text-left">
                                    {{ $file->original_name ?? $file->file_name }}
                                </button>
                            @else
                                <span class="text-sm text-slate-400">
                                    {{ $file->original_name ?? $file->file_name }}
                                    <span class="text-xs text-yellow-500">(processing...)</span>
                                </span>
                            @endif
                            @if (!empty($file->human_size))
                                <span class="text-xs text-slate-500">({{ $file->human_size }})</span>
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
                                    {{ $reply->user?->employee?->employee_name ?? $reply->user?->email ?? '-' }}
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
    <div id="previewModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4">
        <div class="bg-slate-900 rounded-2xl w-full max-w-3xl border border-slate-700 flex flex-col" style="max-height: 90vh">
            <div class="flex items-center justify-between p-4 border-b border-slate-700 flex-shrink-0">
                <h3 id="previewModalTitle" class="text-sm font-semibold text-slate-200 truncate pr-4"></h3>
                <button type="button" onclick="closePreviewModal()" class="text-slate-400 hover:text-white flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-hidden">
                <iframe id="previewModalIframe" src="" class="w-full" style="height: 75vh" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openPreviewModal(url, name) {
                document.getElementById('previewModalTitle').textContent = name;
                document.getElementById('previewModalIframe').src = url;
                const modal = document.getElementById('previewModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closePreviewModal() {
                const modal = document.getElementById('previewModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.getElementById('previewModalIframe').src = '';
            }

            document.getElementById('previewModal')?.addEventListener('click', function(e) {
                if (e.target === this) closePreviewModal();
            });
        </script>
    @endpush
@endsection