@extends('layouts.app')
@section('company', 'IT Departments')
@section('header', 'Edit My Tickets')
@section('subtitle', 'Answer problem or request from users')
@section('content')
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.75rem;
            height: 52px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e5e7eb;
            padding-left: 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 0.75rem;
        }

        .select2-dropdown {
            background-color: #1e293b;
            border: 1px solid #334155;
        }

        .select2-results__option {
            color: #e5e7eb;
        }

        .select2-results__option--highlighted {
            background-color: #2563eb !important;
        }
    </style>
    <div class="px-4 space-y-6 pb-8">
        <div class="bg-gradient-to-r from-blue-500/10 to-cyan-500/10 border border-blue-500/30 rounded-2xl p-4">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-blue-400 mb-1">Tickets from
                        {{ optional($ticket->user->employee)->employee_name }}</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Queue Number : {{ optional($ticket)->queue_number }}
                    <p class="text-xs text-slate-400 leading-relaxed">Date : {{ optional($ticket)->created_at }}
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('updatemytickets', request()->route('hash')) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="updated_at" value="{{ $ticket->updated_at->toISOString() }}">

            @if ($errors->has('conflict'))
                <div class="mb-4 p-4 rounded bg-yellow-50 text-yellow-800 border border-yellow-300">
                    <strong>Caution!</strong><br>
                    {{ $errors->first('conflict') }}
                </div>
            @endif

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <span>Ticket Title</span>
                    <span class="text-red-400">*</span>
                </label>
                <input type="text" id="title" name="title" required
                    placeholder="Example: Laptop cannot connect to WiFi"
                    class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    value="{{ old('title', $ticket->title) }}" required>
                @error('title')
                    <p class="mt-2 text-sm text-red-400 flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span>Categories</span>
                    <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <select id="category" name="category" required
                        class="select2 w-full bg-slate-800 border border-slate-700 rounded-xl text-white">
                        <option value="">Choose Categories...</option>
                        <option value="Hardware & Software"
                            {{ old('category', $ticket->category) == 'Hardware & Software' ? 'selected' : '' }}>
                            Hardware & Software
                        </option>
                        <option value="Network" {{ old('category', $ticket->category) == 'Network' ? 'selected' : '' }}>
                            Network
                        </option>
                        <option value="Account & Access"
                            {{ old('category', $ticket->category) == 'Account & Access' ? 'selected' : '' }}>
                            Account & Access
                        </option>
                        <option value="Others" {{ old('category', $ticket->category) == 'Others' ? 'selected' : '' }}>
                            Others
                        </option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('category')
                    <p class="mt-2 text-sm text-red-400 flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="description"
                    class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span>Problem Description</span>
                    <span class="text-red-400">*</span>
                </label>
                <textarea id="description" name="description" rows="5" required
                    placeholder="Describe your problem in detail:
- What happened?
- When did the problem start?
- What steps have you tried?"
                    class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none">{{ old('description', $ticket->description) }}</textarea>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-slate-500">minimum 10 character</p>
                    <p class="text-xs text-slate-500"><span id="charCount">0</span> / 500</p>
                </div>
                @error('description')
                    <p class="mt-2 text-sm text-red-400 flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            {{-- User Attachments --}}
            <div id="executor-attachments">
                <label class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <span>My Attachments</span>
                </label>

                {{-- Existing user attachments --}}
                @if ($ticket->attachments->count())
                    <div class="border border-slate-700 rounded-xl p-4 bg-slate-800/40 mb-3">
                        <ul class="space-y-2">
                            @foreach ($ticket->attachments as $file)
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Upload attachment baru --}}
                <div id="user-attachment-container">
                    <p id="user-attachment-empty-text" class="text-sm text-slate-500">No files selected</p>
                </div>
                <div class="mt-3 flex flex-col sm:flex-row gap-3">
                    <button type="button" id="user-add-attachment"
                        class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        + Add Attachment
                    </button>
                    <button type="button" id="user-erase-attachment"
                        class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        - Erase Attachment
                    </button>
                </div>
                <p class="mt-2 text-xs text-slate-500">Max 10 files, 20MB each.</p>
            </div>

            {{-- Executor Attachments (hanya tampil jika status Closed) --}}
            @if ($ticket->status === 'Closed')
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <span>Executor Attachments</span>
                    </label>
                    <div class="border border-slate-700 rounded-xl p-5 bg-slate-800/40 min-h-[80px]">
                        @if ($ticket->executorAttachments->count())
                            <ul class="space-y-2 text-sm text-slate-300">
                                @foreach ($ticket->executorAttachments as $file)
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
                                        <span class="text-xs text-slate-500">({{ $file->human_size }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-slate-500">No executor attachments yet</p>
                        @endif
                    </div>
                </div>
            @endif

            <div class="flex space-x-3 pt-4">
                <a href="{{ route('dashboard') }}"
                    class="flex-1 py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Back to Dashboard</span>
                </a>
                <button type="submit"
                    class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Update my ticket</span>
                </button>
            </div>
        </form>

        <form id="executor-attachments-form"
            action="{{ route('attachments.store', $ticket->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="hidden">
            @csrf
            <input type="file" id="executor-files-input" name="files[]" multiple
                accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip,.txt" style="display:none">
        </form>
    </div>

    {{-- Modal Pilih Sumber (User Attachment) --}}
    <div id="userSourceModal"
        class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
        <div class="bg-slate-900 rounded-xl p-6 w-80 text-center border border-slate-800">
            <h3 class="text-lg font-semibold mb-4 text-white">Pilih Sumber</h3>
            <button type="button" id="userOpenCamera"
                class="w-full mb-3 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                Open Camera
            </button>
            <button type="button" id="userOpenFiles"
                class="w-full mb-3 px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white">
                Upload Files
            </button>
            <button type="button" id="userCloseSource"
                class="w-full px-4 py-2 text-slate-400 hover:text-white">
                Abort
            </button>
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
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#category').select2({
                    placeholder: 'Choose Category...',
                    width: '100%',
                    dropdownParent: $('#category').parent()
                });
            });
        </script>

        <script>
            const description = document.getElementById('description');
            const charCount = document.getElementById('charCount');
            description.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        </script>

        <script>
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: "3000"
            };
            @if (session('success'))
                toastr.success(@json(session('success')));
            @endif
            @if (session('error'))
                toastr.error(@json(session('error')));
            @endif
        </script>

        {{-- Script User Attachment --}}
        <script>
            (function () {
                const addBtn = document.getElementById('user-add-attachment');
                const eraseBtn = document.getElementById('user-erase-attachment');
                const container = document.getElementById('user-attachment-container');
                const sourceModal = document.getElementById('userSourceModal');
                const openCameraBtn = document.getElementById('userOpenCamera');
                const openFilesBtn = document.getElementById('userOpenFiles');
                const closeSourceBtn = document.getElementById('userCloseSource');
                const CSRF = document.querySelector('meta[name="csrf-token"]')?.content
                    ?? document.querySelector('input[name="_token"]')?.value;
                const UPLOAD_URL = "{{ route('attachments.store', $ticket->id) }}";

                let attachmentCount = 0;
                let activeInputId = null;
                let isUploading = false;

                const openSourceModal = () => {
                    if (!sourceModal) return;
                    sourceModal.classList.remove('hidden');
                    sourceModal.classList.add('flex');
                };
                const closeSourceModal = () => {
                    if (!sourceModal) return;
                    sourceModal.classList.add('hidden');
                    sourceModal.classList.remove('flex');
                };

                function addAttachment() {
                    attachmentCount++;
                    const id = `user_attachment_${attachmentCount}`;
                    const emptyText = document.getElementById('user-attachment-empty-text');
                    if (emptyText) emptyText.remove();
                    const html = `
                        <div class="relative mb-3" id="wrap_${id}">
                            <input type="file"
                                id="${id}"
                                name="files[]"
                                accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip,.txt"
                                class="hidden"
                                onchange="handleUserFileChange('${id}')">
                            <label onclick="showUserSourceModal('${id}')"
                                class="flex items-center justify-center w-full px-4 py-8 bg-slate-800 border-2 border-dashed border-slate-700 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-slate-800/50 transition-all duration-200 group">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-600 group-hover:text-blue-500"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p id="fileName_${id}" class="text-sm font-medium text-slate-400 group-hover:text-blue-400">
                                        Click to upload file
                                    </p>
                                    <p class="text-xs text-slate-600 mt-1">
                                        JPG, PNG, GIF, PDF, DOC, XLS, ZIP, TXT (Max. 20MB)
                                    </p>
                                    <div id="userPreview_${id}" class="mt-2 text-xs text-slate-400"></div>
                                </div>
                            </label>
                        </div>
                    `;
                    container?.insertAdjacentHTML('beforeend', html);
                }

                function eraseAttachment() {
                    if (attachmentCount <= 0) return;
                    const lastId = `wrap_user_attachment_${attachmentCount}`;
                    const lastElement = document.getElementById(lastId);
                    if (lastElement) {
                        lastElement.remove();
                        attachmentCount--;
                    }
                    if (attachmentCount === 0 && container && !document.getElementById('user-attachment-empty-text')) {
                        container.insertAdjacentHTML('beforeend',
                            '<p id="user-attachment-empty-text" class="text-sm text-slate-500">No files selected</p>'
                        );
                    }
                }

                window.showUserSourceModal = function (id) {
                    activeInputId = id;
                    openSourceModal();
                };

                window.handleUserFileChange = async function (id) {
                    const input = document.getElementById(id);
                    const label = document.getElementById(`fileName_${id}`);
                    const preview = document.getElementById(`userPreview_${id}`);
                    if (!input || !input.files || !input.files.length) return;
                    const file = input.files[0];
                    if (label) label.textContent = file.name;
                    if (preview) preview.textContent = 'Uploading...';
                    await uploadFile(file, preview);
                };

                async function uploadFile(file, previewEl) {
                    if (isUploading) return;
                    isUploading = true;
                    const formData = new FormData();
                    formData.append('files[]', file);
                    try {
                        const res = await fetch(UPLOAD_URL, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                            body: formData,
                            credentials: 'same-origin',
                        });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data?.message || 'Upload failed.');
                        if (previewEl) previewEl.textContent = 'Uploaded';
                        toastr?.success(data?.message || 'Upload successful.');
                    } catch (error) {
                        if (previewEl) previewEl.textContent = 'Upload failed';
                        toastr?.error(error.message || 'Upload failed.');
                    } finally {
                        isUploading = false;
                    }
                }

                addBtn?.addEventListener('click', addAttachment);
                eraseBtn?.addEventListener('click', eraseAttachment);

                openCameraBtn?.addEventListener('click', () => {
                    if (!activeInputId) return;
                    const input = document.getElementById(activeInputId);
                    if (!input) return;
                    input.setAttribute('capture', 'environment');
                    input.click();
                    closeSourceModal();
                });
                openFilesBtn?.addEventListener('click', () => {
                    if (!activeInputId) return;
                    const input = document.getElementById(activeInputId);
                    if (!input) return;
                    input.removeAttribute('capture');
                    input.click();
                    closeSourceModal();
                });
                closeSourceBtn?.addEventListener('click', closeSourceModal);
                sourceModal?.addEventListener('click', (e) => {
                    if (e.target === sourceModal) closeSourceModal();
                });
            })();
        </script>

        {{-- Script Preview Modal --}}
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