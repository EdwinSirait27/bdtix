@extends('layouts.app')
@section('title', 'BD Tickets Queue' . ($ticket->queue_number ?? ''))
@section('header', 'BD Ticket Detail')
@section('subtitle', 'Detail and status tickets')
@section('content')
    <div class="max-w-10xl mx-auto space-y-12">

        {{-- Ticket Info --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                        Title : {{ $ticket->title ?? '-' }}
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Ticket Queue Number {{ $ticket->queue_number ?? '-' }} • Created
                        {{-- FIX 1: created_at bisa null --}}
                        {{ $ticket->created_at?->format('d F Y H:i') ?? '-' }}
                    </p>
                </div>
                <div>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold">
                        Status : {{ strtoupper(str_replace('_', ' ', $ticket->status ?? '-')) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Ticket Meta --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 ">Location</p>
                <p class="font-semibold mt-1">
                    {{-- FIX 2: relasi bertingkat null-safe --}}
                    {{ $ticket->store?->name ?? '-' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 ">Created By</p>
                <p class="font-semibold mt-1">
                    {{-- FIX 2: relasi bertingkat null-safe --}}
                    {{ $ticket->user?->employee?->employee_name ?? ($ticket->user?->email ?? '-') }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 ">Priority</p>
                <p class="font-semibold mt-1">
                    {{ ucfirst($ticket->priority ?? '-') }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 ">Category</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->category ?? '-' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 ">Sub Category</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->sub_category ?? '-' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 ">Executor</p>
                <p class="font-semibold mt-1">
                    {{-- FIX 3: executor bisa null --}}
                    {{-- {{ $ticket->executor?->employee?->employee_name ?? '-' }} --}}
                    {{ $executor?->employee?->employee_name ?? '-' }}

                    {{-- {{ dd($ticket->executor_id, $ticket->executor, $ticket->executor?->employee) }} --}}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 ">Finished</p>
                <p class="font-semibold mt-1">
                    {{-- FIX 4: finished bisa null --}}
                    {{ $ticket->finished?->format('d F Y H:i') ?? '-' }}
                </p>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
            <h3 class="text-lg font-bold mb-3">Description</h3>
            <div class="prose dark:prose-invert max-w-none text-sm">
                {!! nl2br(e($ticket->description ?? '-')) !!}
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
            <h3 class="text-lg font-bold mb-3">Notes from BD</h3>
            <div class="prose dark:prose-invert max-w-none text-sm">
                {!! nl2br(e($ticket->notes_executor ?? '-')) !!}
            </div>
        </div>

        {{-- Attachments --}}
        {{-- FIX 5: cek null sebelum count() --}}
        
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
                                    {{-- {{ $file->original_name ?? ($file->file_name ?? '-') }} --}}
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
        @if ($ticket->executorAttachments && $ticket->executorAttachments->count())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold mb-4 text-slate-900 dark:text-white">Executor Attachments : {{ $ticket->executor->employee->employee_name }}</h3>
                <ul class="space-y-2">
                    @foreach ($ticket->executorAttachments as $file)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8 2a4 4 0 00-4 4v8a6 6 0 0012 0V6a2 2 0 10-4 0v7a1 1 0 102 0V6a4 4 0 00-8 0v8a4 4 0 008 0V6" />
                            </svg>

                            @if ($file->status === 'uploaded')
                                {{-- <button type="button" onclick="openSignedUrl('{{ $file->id }}')" --}}
                                    <button type="button" onclick="openSignedUrlForExecutor('{{ $file->id }}')"

                                    class="text-sm text-blue-400 hover:underline text-left truncate">
                                    {{-- {{ $file->original_name ?? $file->file_name }} --}}
                                    {{ $file->file_name }}

                                </button>
                            @else
                                <span class="text-sm text-slate-400 truncate">
                                    {{-- {{ $file->original_name ?? ($file->file_name ?? '-') }} --}}
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

        {{-- Activity --}}
        @if ($ticket->replies && $ticket->replies->count())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
                <h3 class="text-lg font-bold mb-4">Activity</h3>
                <div class="space-y-4">
                    @foreach ($ticket->replies as $reply)
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm font-semibold">
                                    {{-- FIX 6: relasi bertingkat di replies --}}
                                    {{ $reply->user?->employee?->employee_name ?? ($reply->user?->email ?? '-') }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{-- FIX 7: created_at di reply bisa null --}}
                                    {{ $reply->created_at?->diffForHumans() ?? '-' }}
                                </p>
                            </div>
                            <p class="text-sm text-slate-700 dark:text-slate-300">
                                {{ $reply->message ?? '-' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Review --}}
        @if ($ticket->review)
            <div class="bg-gradient-to-br from-green-900/20 to-emerald-900/20 border border-green-700/30 rounded-2xl p-6">
                <div class="flex items-start space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-green-400 mb-1">Review Submitted</h3>
                        <p class="text-sm text-slate-400">Thank you for your feedback!</p>
                    </div>
                </div>

                @if ($ticket->review->rating)
                    <div class="bg-slate-900/50 rounded-xl p-4 space-y-3">
                        <div>
                            <p class="text-xs text-slate-500 mb-2">Reviewed By :
                                {{-- FIX 8: user->employee bisa null --}}
                                {{ $ticket->user?->employee?->employee_name ?? ($ticket->user?->email ?? '-') }}
                            </p>
                            <div class="flex items-center space-x-2">
                                <div class="flex text-yellow-400 text-lg">
                                    @for ($i = 1; $i <= 5; $i++)
                                        {{ $i <= $ticket->review->rating ? '★' : '☆' }}
                                    @endfor
                                </div>
                                <span class="text-sm text-slate-400">({{ $ticket->review->rating }}/5)</span>
                            </div>
                        </div>

                        @if ($ticket->review->comment)
                            <div>
                                @role('admin|executor')
                                    <p class="text-xs text-slate-500 mb-2">Comment By :
                                        {{ $ticket->user?->employee?->employee_name ?? ($ticket->user?->email ?? '-') }}
                                    </p>
                                    <p class="text-sm text-slate-300 italic">"{{ $ticket->review->comment }}"</p>
                                @endrole
                            </div>
                        @else
                            <p class="text-sm text-slate-500 italic">No comment provided</p>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- Back Button --}}
        <div class="flex justify-end">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-5 py-2.5 rounded-xl
                  bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white
                  hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                Back to Dashboard
            </a>
        </div>
    </div>


    {{-- Modal Preview Attachment --}}
    <div id="previewModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4">
        <div class="bg-slate-900 rounded-2xl w-full max-w-3xl border border-slate-700 flex flex-col"
            style="max-height: 90vh">
            <div class="flex items-center justify-between p-4 border-b border-slate-700 flex-shrink-0">
                <h3 id="previewModalTitle" class="text-sm font-semibold text-slate-200 truncate pr-4"></h3>
                <button type="button" onclick="closePreviewModal()" class="text-slate-400 hover:text-white flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-hidden">
                <iframe id="previewModalIframe" src="" class="w-full" style="height: 75vh" frameborder="0"
                    allowfullscreen></iframe>
            </div>
        </div>
    </div>

    {{-- Modal Preview Executor Attachment --}}
<div id="previewModalForExecutor" class="fixed inset-0 bg-black/80 hidden items-center justify-center p-4" style="z-index:9999;">
    <div class="bg-slate-900 rounded-2xl w-full max-w-3xl border border-slate-700 flex flex-col shadow-2xl" style="max-height:90vh;">
        <div class="flex items-center justify-between p-4 border-b border-slate-700 flex-shrink-0">
            <h3 id="previewModalForExecutorTitle" class="text-sm font-semibold text-slate-200 truncate pr-4"></h3>
            <button type="button" onclick="closePreviewModalForExecutor()" class="text-slate-400 hover:text-white flex-shrink-0 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-hidden">
            <iframe id="previewModalForExecutorIframe" src="" class="w-full" style="height:75vh;" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // for human
            async function openSignedUrl(attachmentId) {
                try {
                    const res = await fetch(`/attachments/${attachmentId}/signed-url`, {
                        headers: {
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin',
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Failed to get URL');

                    const previewableMimes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];

                    if (previewableMimes.includes(data.mime_type)) {
                        document.getElementById('previewModalTitle').textContent = data.file_name ?? 'Attachment';
                        document.getElementById('previewModalIframe').src = data.url;
                        const modal = document.getElementById('previewModal');
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    } else {
                        // File tidak bisa di-preview, langsung download
                        const a = document.createElement('a');
                        a.href = data.url;
                        a.download = data.file_name ?? 'attachment';
                        a.click();
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: e.message || 'Failed to open file.',
                        background: '#0f172a',
                        color: '#e2e8f0',
                    });
                }
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



function openSignedUrlForExecutor(fileId) {
    const url = `/attachmentsforexecutor/${fileId}/signed-url-for-executor`;
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        },
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(data => {
        if (!data.url) throw new Error('No URL returned');
        document.getElementById('previewModalForExecutorTitle').textContent = data.file_name ?? 'Preview';
        document.getElementById('previewModalForExecutorIframe').src = data.url;
        const modal = document.getElementById('previewModalForExecutor');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    })
    .catch(err => Swal.fire({
        icon: 'error',
        title: 'Failed',
        text: err.message || 'Failed to open file.',
        background: '#0f172a',
        color: '#e2e8f0',
    }));
}

    function closePreviewModalForExecutor() {
        const modal = document.getElementById('previewModalForExecutor');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('previewModalForExecutorIframe').src = '';
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
</script>
            @endpush
@endsection
