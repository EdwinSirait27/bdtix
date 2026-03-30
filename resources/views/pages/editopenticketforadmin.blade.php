@extends('layouts.app')
@section('company', 'IT Departments')
@section('header', 'Edit Ticket')
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
            background-color: #1e293b !important;
            color: #e5e7eb !important;
        }

        .select2-results__option--highlighted {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }

        .select2-results__option[aria-selected="true"] {
            background-color: #334155 !important;
            color: #e5e7eb !important;
        }

        .select2-results__option[aria-selected="true"]:hover {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }

        #executorSourceModal,
        #previewModal {
            z-index: 9999;
        }

        /* ✅ Flatpickr dark theme override */
        .flatpickr-calendar {
            background: #1e293b !important;
            border: 1px solid #334155 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4) !important;
            z-index: 99999 !important;
        }
        .flatpickr-time input,
        .flatpickr-time .flatpickr-time-separator,
        .flatpickr-time .flatpickr-am-pm {
            color: #e5e7eb !important;
            background: #1e293b !important;
        }
        .flatpickr-time input:hover,
        .flatpickr-time input:focus {
            background: #334155 !important;
        }
        .numInputWrapper:hover {
            background: #334155 !important;
        }
        .flatpickr-input {
            background-color: #1e293b !important;
            border: 1px solid #334155 !important;
            border-radius: 0.75rem !important;
            color: #e5e7eb !important;
            padding: 0.75rem 1rem !important;
            width: 100% !important;
            font-size: 0.875rem !important;
            cursor: pointer !important;
        }
        .flatpickr-input:focus {
            outline: none !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59,130,246,0.3) !important;
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
                    <p class="text-xs text-slate-400 leading-relaxed">Date : {{ $createdat }}</p>
                    <p class="text-xs text-slate-400 leading-relaxed">Queue Number : {{ optional($ticket)->queue_number }}</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('updateopenticketforadmin', request()->route('hash')) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="updated_at" value="{{ $ticket->updated_at->toISOString() }}">

            @if ($errors->has('conflict'))
                <div class="mb-4 p-4 rounded bg-yellow-50 text-yellow-800 border border-yellow-300">
                    <strong>Caution!</strong><br>
                    {{ $errors->first('conflict') }}
                </div>
            @endif

            {{-- Ticket Title --}}
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
                    value="{{ old('title', $ticket->title) }}" disabled>
            </div>

            {{-- Category --}}
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
                        <option value="Hardware & Software" {{ old('category', $ticket->category) == 'Hardware & Software' ? 'selected' : '' }}>Hardware & Software</option>
                        <option value="Network" {{ old('category', $ticket->category) == 'Network' ? 'selected' : '' }}>Network</option>
                        <option value="Account & Access" {{ old('category', $ticket->category) == 'Account & Access' ? 'selected' : '' }}>Account & Access</option>
                        <option value="Others" {{ old('category', $ticket->category) == 'Others' ? 'selected' : '' }}>Others</option>
                    </select>
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

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span>Problem Description</span>
                    <span class="text-red-400">*</span>
                </label>
                <textarea id="description" name="description" rows="5" disabled
                    placeholder="Describe your problem in detail..."
                    class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none">{{ old('description', $ticket->description) }}</textarea>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-slate-500">minimum 10 character</p>
                    <p class="text-xs text-slate-500"><span id="descCharCount">0</span> / 500</p>
                </div>
            </div>

            {{-- Notes Executor --}}
            <div class="-mt-4">
                <label for="notes_executor" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span>Notes Executor</span>
                    <span class="text-red-400">*</span>
                </label>
                <textarea id="notes_executor" name="notes_executor" rows="5" required
                    placeholder="Describe user's problem in detail..."
                    class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none">{{ old('notes_executor', $ticket->notes_executor) }}</textarea>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-slate-500">minimum 10 character</p>
                    <p class="text-xs text-slate-500"><span id="notesCharCount">0</span> / 500</p>
                </div>
                @error('notes_executor')
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

            {{-- ============================================================
                 DURATION SECTION
                 - Status Open     → form duration (executor mengisi)
                 - Status Progress / Overdue → read-only info panel
            ============================================================ --}}
            @if ($ticket->status === 'Open')
                {{-- Form Duration: hanya saat Take Ticket --}}
                <div class="mt-4">
                    <label for="duration_type" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Duration</span>
                        <span class="text-red-400">*</span>
                    </label>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                        {{-- ✅ Choose Type → Select2 --}}
                        <div>
                            <select id="duration_type" name="duration_type"
                                class="select2-duration w-full bg-slate-800 border border-slate-700 rounded-xl text-white" required>
                                <option value="">Choose Type</option>
                                <option value="hour" {{ old('duration_type') == 'hour' ? 'selected' : '' }}>Hour</option>
                                <option value="day" {{ old('duration_type') == 'day' ? 'selected' : '' }}>Day</option>
                                <option value="week" {{ old('duration_type') == 'week' ? 'selected' : '' }}>Week</option>
                            </select>
                            @error('duration_type')
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
                            {{-- ✅ Day/Week → Select2 --}}
                            <select id="duration_value_select"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3.5 bg-slate-800 border border-slate-700 rounded-lg sm:rounded-xl text-sm sm:text-base text-white">
                                <option value="">Choose type first...</option>
                            </select>
                            {{-- ✅ Hour → Flatpickr time picker --}}
                            <input type="text" id="duration_hour_time"
                                class="hidden w-full bg-slate-800 border border-slate-700 rounded-xl text-white"
                                placeholder="Pick a time...">
                            <p id="duration-hour-help" class="mt-2 text-xs text-slate-500 hidden">
                                Pilih jam pengerjaan. Durasi dihitung otomatis dari waktu sekarang.
                            </p>
                            <input type="hidden" id="duration_value" name="duration_value" value="{{ old('duration_value') }}">
                            @error('duration_value')
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
                    </div>
                    {{-- Hidden fields untuk estimation (dihitung otomatis di JS, dikirim ke controller) --}}
                    <input type="datetime-local" id="estimation" name="estimation"
                        value="{{ old('estimation') }}" class="hidden">
                    <div class="hidden">
                        <input type="datetime-local" id="estimation_to" name="estimation_to"
                            value="{{ old('estimation_to') }}">
                    </div>
                </div>

            @else
                {{-- Read-only Estimation Fields: saat Progress / Overdue --}}
                <input type="hidden" name="duration_type" value="{{ $ticket->duration_type }}">
                <input type="hidden" name="duration_value" value="{{ $ticket->duration_value }}">

                <div class="mt-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">

                        {{-- Estimation (Started At) --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Estimation</span>
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="text" readonly
                                value="{{ $ticket->estimation ? $ticket->estimation->timezone('Asia/Makassar')->format('Y-m-d H:i') : '-' }}"
                                class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white cursor-not-allowed opacity-70 focus:outline-none">
                        </div>

                        {{-- Estimation To (Est. Deadline) --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Estimation To</span>
                                <span class="text-red-400">*</span>
                                @if ($ticket->estimation_to && now()->gt($ticket->estimation_to))
                                    <span class="text-xs text-red-400 font-normal">(Overdue)</span>
                                @endif
                            </label>
                            <input type="text" readonly
                                value="{{ $ticket->estimation_to ? $ticket->estimation_to->timezone('Asia/Makassar')->format('Y-m-d H:i') : '-' }}"
                                class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl cursor-not-allowed opacity-70 focus:outline-none
                                    {{ $ticket->estimation_to && now()->gt($ticket->estimation_to) ? 'text-red-400' : 'text-white' }}">
                        </div>

                    </div>
                </div>
            @endif

            {{-- Status Dropdown: hanya saat Overdue --}}
            @if ($ticket->status === 'Overdue')
            <div>
                <label for="statusSelect" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Status</span>
                    <span class="text-red-400">*</span>
                </label>
                <select id="statusSelect" name="status"
                    class="select2-status w-full bg-slate-800 border border-slate-700 rounded-xl text-white">
                    <option value="" disabled selected>Overdue — Choose action...</option>
                    <option value="Progress">Back to Progress</option>
                    <option value="Closed">Close this Ticket</option>
                </select>
            </div>
            @elseif (in_array($ticket->status, ['Progress']))
                <input type="hidden" name="status" id="ticketStatusInput" value="Closed">
            @endif

            {{-- User Attachments --}}
            <div class="mt-4">
                <label class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <span>User Attachments</span>
                </label>
                <div class="border border-slate-700 rounded-xl p-4 bg-slate-800/40 min-h-[80px]">
                    @if ($ticket->attachments->count())
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
                    @else
                        <p class="text-sm text-slate-500">No user attachments</p>
                    @endif
                </div>
            </div>

            {{-- Executor Attachments --}}
            @if ($ticket->status !== 'Open')
                <div class="mt-4">
                    <label class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <span>Executor Attachments</span>
                        @if (in_array($ticket->status, ['Progress', 'Overdue']))
                            <span class="text-red-400">*</span>
                            <span class="text-xs text-slate-500">(wajib sebelum close ticket)</span>
                        @endif
                    </label>

                    @if ($ticket->executorAttachments->count())
                        <div class="border border-slate-700 rounded-xl p-4 bg-slate-800/40 mb-3">
                            <ul class="space-y-2">
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
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        @if (in_array($ticket->status, ['Progress', 'Overdue']))
                            <div class="border border-slate-700 rounded-xl p-4 bg-slate-800/40 mb-3">
                                <p class="text-sm text-slate-500">Belum ada attachment. Upload bukti pengerjaan sebelum menutup ticket.</p>
                            </div>
                        @endif
                    @endif

                    @if (in_array($ticket->status, ['Progress', 'Overdue']))
                        <div id="admin-executor-attachment-container">
                            <p id="admin-executor-empty-text" class="text-sm text-slate-500">No files selected</p>
                        </div>
                        <div class="mt-3 flex flex-col sm:flex-row gap-3">
                            <button type="button" id="admin-executor-add-btn"
                                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                + Add Attachment
                            </button>
                            <button type="button" id="admin-executor-erase-btn"
                                class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                                - Erase Attachment
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Max 10 files, 20MB each.</p>
                    @endif
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="flex space-x-3 pt-4">
                <a href="{{ route('dashboard') }}"
                    class="flex-1 py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Back to Dashboard</span>
                </a>

                @if ($ticket->status === 'Open')
                    <input type="hidden" name="status" value="Progress">
                    <button type="submit" name="action" value="take"
                        class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Take this Ticket</span>
                    </button>
                @endif

                @if (in_array($ticket->status, ['Progress', 'Overdue']))
                    <button type="button" id="btn-close-ticket"
                        class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span id="btn-close-label">{{ $ticket->status === 'Overdue' ? 'Choose Status First...' : 'Close this Ticket' }}</span>
                    </button>
                @endif
            </div>

            {{-- Hidden form upload executor --}}
            <form id="admin-executor-attachments-form"
                action="{{ route('executor.attachments.store', $ticket->id) }}"
                method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
            </form>
        </form>
    </div>

    {{-- Modal: Pilih Sumber --}}
    <div id="executorSourceModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center" style="z-index:9999;">
        <div class="bg-slate-900 rounded-xl p-6 w-80 text-center border border-slate-800 shadow-2xl">
            <h3 class="text-lg font-semibold mb-4 text-white">Pilih Sumber</h3>
            <button type="button" id="executorOpenCamera" class="w-full mb-3 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">Open Camera</button>
            <button type="button" id="executorOpenFiles" class="w-full mb-3 px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-white transition">Upload Files</button>
            <button type="button" id="executorCloseSource" class="w-full px-4 py-2 text-slate-400 hover:text-white transition">Abort</button>
        </div>
    </div>

    {{-- Modal: Preview Attachment --}}
    <div id="previewModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center p-4" style="z-index:9999;">
        <div class="bg-slate-900 rounded-2xl w-full max-w-3xl border border-slate-700 flex flex-col shadow-2xl" style="max-height:90vh;">
            <div class="flex items-center justify-between p-4 border-b border-slate-700 flex-shrink-0">
                <h3 id="previewModalTitle" class="text-sm font-semibold text-slate-200 truncate pr-4"></h3>
                <button type="button" onclick="closePreviewModal()" class="text-slate-400 hover:text-white flex-shrink-0 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-hidden">
                <iframe id="previewModalIframe" src="" class="w-full" style="height:75vh;" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- ✅ Flatpickr CSS --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <script>
            $(document).ready(function () {
                // Select2: Category
                $('#category').select2({
                    placeholder: 'Choose Category...',
                    width: '100%',
                    dropdownParent: $('#category').parent()
                });

                // ✅ Select2: Duration Type (Hour/Day/Week)
                @if ($ticket->status === 'Open')
                $('#duration_type').select2({
                    placeholder: 'Choose Type...',
                    width: '100%',
                    dropdownParent: $('#duration_type').parent(),
                    minimumResultsForSearch: Infinity
                });

                // ✅ Trigger change setelah Select2 berubah agar JS duration ikut update
                $('#duration_type').on('change', function () {
                    const event = new Event('change', { bubbles: true });
                    document.getElementById('duration_type').dispatchEvent(event);
                });
                @endif

                @if ($ticket->status === 'Overdue')
                $('#statusSelect').select2({
                    placeholder: 'Overdue — Choose action...',
                    width: '100%',
                    dropdownParent: $('#statusSelect').parent(),
                    minimumResultsForSearch: Infinity
                });
                @endif
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const desc = document.getElementById('description');
                const descCount = document.getElementById('descCharCount');
                if (desc && descCount) { descCount.textContent = desc.value.length; desc.addEventListener('input', () => descCount.textContent = desc.value.length); }

                const notes = document.getElementById('notes_executor');
                const notesCount = document.getElementById('notesCharCount');
                if (notes && notesCount) { notesCount.textContent = notes.value.length; notes.addEventListener('input', () => notesCount.textContent = notes.value.length); }
            });
        </script>

        <script>
            toastr.options = { closeButton: true, progressBar: true, positionClass: "toast-top-right", timeOut: "3000" };
            @if (session('success')) toastr.success(@json(session('success'))); @endif
            @if (session('error')) toastr.error(@json(session('error'))); @endif
        </script>

        {{-- Script duration: hanya aktif saat status Open --}}
        @if ($ticket->status === 'Open')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const durationType        = document.getElementById('duration_type');
                const durationValueSelect = document.getElementById('duration_value_select');
                const durationHourTime    = document.getElementById('duration_hour_time');
                const durationValueInput  = document.getElementById('duration_value');
                const estimationInput     = document.getElementById('estimation');
                const estimationToInput   = document.getElementById('estimation_to');
                const durationHourHelp    = document.getElementById('duration-hour-help');
                if (!durationType || !durationValueSelect || !durationHourTime || !durationValueInput || !estimationInput || !estimationToInput) return;

                const ranges = { hour: { min:1, max:24, label:'Hour' }, day: { min:2, max:6, label:'Day' }, week: { min:1, max:4, label:'Week' } };
                const fmt = (d) => `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}T${String(d.getHours()).padStart(2,'0')}:${String(d.getMinutes()).padStart(2,'0')}`;

                // Estimation FROM = now() saat halaman dimuat (otomatis)
                const setEstimationNow = () => { estimationInput.value = fmt(new Date()); };
                setEstimationNow();

                const buildOpts = () => {
                    const type = durationType.value;
                    durationValueSelect.innerHTML = '';
                    if (!ranges[type]) { durationValueSelect.appendChild(new Option('Choose type first...', '')); return; }
                    durationValueSelect.appendChild(new Option('Choose duration...', ''));
                    for (let i = ranges[type].min; i <= ranges[type].max; i++) {
                        durationValueSelect.appendChild(new Option(`${i} ${ranges[type].label}`, i));
                    }
                };

                const syncVal = () => {
                    const type = durationType.value;
                    if (type === 'hour') {
                        // Ambil nilai dari Flatpickr
                        const v = fpHour.input.value;
                        if (!v) { durationValueInput.value = ''; return; }
                        const start = new Date(estimationInput.value);
                        const h = parseInt(v.split(':')[0] || '0', 10);
                        if (isNaN(start.getTime()) || isNaN(h)) { durationValueInput.value = ''; return; }
                        let diff = h - start.getHours();
                        if (diff <= 0) diff += 24;
                        durationValueInput.value = String(diff);
                        return;
                    }
                    durationValueInput.value = durationValueSelect.value || '';
                };

                const syncReq = () => {
                    const type = durationType.value;
                    if (type === 'hour') {
                        durationValueSelect.required = false;
                        durationHourHelp?.classList.remove('hidden');
                    } else {
                        durationValueSelect.required = true;
                        durationHourHelp?.classList.add('hidden');
                    }
                };

                const calcEnd = () => {
                    const type = durationType.value;
                    const val  = parseInt(durationValueInput.value || '0', 10);
                    if (!type || !val || !estimationInput.value) return;
                    const start = new Date(estimationInput.value);
                    if (isNaN(start.getTime())) return;
                    const mins = type === 'hour' ? val * 60 : type === 'day' ? val * 1440 : val * 10080;
                    estimationToInput.value = fmt(new Date(start.getTime() + mins * 60000));
                };

                // ✅ Inisialisasi Flatpickr untuk input jam (Hour)
                const fpHour = flatpickr(durationHourTime, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    minuteIncrement: 60,
                    onReady: function(selectedDates, dateStr, instance) {
                        instance.calendarContainer.style.zIndex = "99999";
                    },
                    onChange: function() {
                        syncVal();
                        calcEnd();
                    }
                });

                // ✅ Listen perubahan dari Select2 duration_type via jQuery
                $('#duration_type').on('change', function () {
                    const type = this.value;
                    if (type === 'hour') {
                        durationValueSelect.classList.add('hidden');
                        durationHourTime.classList.remove('hidden');
                    } else {
                        durationValueSelect.classList.remove('hidden');
                        durationHourTime.classList.add('hidden');
                        buildOpts();
                    }
                    syncReq();
                    syncVal();
                    calcEnd();
                });

                durationValueSelect.addEventListener('change', () => { syncVal(); calcEnd(); });

                buildOpts();
                syncReq();
                syncVal();
                calcEnd();
            });
        </script>
        @endif

        {{-- Script upload executor: hanya aktif saat Progress atau Overdue --}}
        @if (in_array($ticket->status, ['Progress', 'Overdue']))
        <script>
            (function () {
                const MAX_FILES      = 10;
                const addBtn         = document.getElementById('admin-executor-add-btn');
                const eraseBtn       = document.getElementById('admin-executor-erase-btn');
                const container      = document.getElementById('admin-executor-attachment-container');
                const sourceModal    = document.getElementById('executorSourceModal');
                const openCameraBtn  = document.getElementById('executorOpenCamera');
                const openFilesBtn   = document.getElementById('executorOpenFiles');
                const closeSourceBtn = document.getElementById('executorCloseSource');
                const btnClose       = document.getElementById('btn-close-ticket');
                const CSRF           = document.querySelector('meta[name="csrf-token"]')?.content ?? document.querySelector('input[name="_token"]')?.value;
                const UPLOAD_URL     = "{{ route('executor.attachments.store', $ticket->id) }}";
                const existingCount  = {{ $ticket->executorAttachments->count() }};

                let attachmentCount = 0;
                let uploadedCount   = 0;
                let activeInputId   = null;
                let isUploading     = false;

                const openModal  = () => { sourceModal.classList.remove('hidden'); sourceModal.classList.add('flex'); };
                const closeModal = () => { sourceModal.classList.add('hidden'); sourceModal.classList.remove('flex'); };

                function addAttachment() {
                    if (attachmentCount >= MAX_FILES) { toastr.warning('Maximum 10 files allowed.'); return; }
                    attachmentCount++;
                    const id = `exec_att_${attachmentCount}`;
                    document.getElementById('admin-executor-empty-text')?.remove();
                    container?.insertAdjacentHTML('beforeend', `
                        <div class="relative mb-3" id="wrap_${id}">
                            <input type="file" id="${id}" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip,.txt" class="hidden">
                            <label class="flex items-center justify-center w-full px-4 py-8 bg-slate-800 border-2 border-dashed border-slate-700 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-slate-800/50 transition-all duration-200 group"
                                onclick="window.showExecutorSourceModal('${id}'); return false;">
                                <div class="text-center pointer-events-none">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-600 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p id="fileName_${id}" class="text-sm font-medium text-slate-400 group-hover:text-blue-400">Click to upload file</p>
                                    <p class="text-xs text-slate-600 mt-1">JPG, PNG, GIF, PDF, DOC, XLS, ZIP, TXT (Max. 20MB)</p>
                                    <div id="prev_${id}" class="mt-2 text-xs text-slate-400"></div>
                                </div>
                            </label>
                        </div>`);
                    document.getElementById(id)?.addEventListener('change', () => handleChange(id));
                }

                function eraseAttachment() {
                    if (attachmentCount <= 0) return;
                    document.getElementById(`wrap_exec_att_${attachmentCount}`)?.remove();
                    attachmentCount--;
                    if (attachmentCount === 0 && container && !document.getElementById('admin-executor-empty-text'))
                        container.insertAdjacentHTML('beforeend', '<p id="admin-executor-empty-text" class="text-sm text-slate-500">No files selected</p>');
                }

                window.showExecutorSourceModal = (id) => { activeInputId = id; openModal(); };

                async function handleChange(id) {
                    const input = document.getElementById(id);
                    const label = document.getElementById(`fileName_${id}`);
                    const prev  = document.getElementById(`prev_${id}`);
                    if (!input?.files?.length) return;
                    const file = input.files[0];
                    if (label) label.textContent = file.name;
                    if (prev)  prev.textContent  = 'Uploading...';
                    await upload(file, prev);
                }

                async function upload(file, prevEl) {
                    if (isUploading) { toastr.warning('Please wait, upload in progress.'); return; }
                    isUploading = true;
                    const fd = new FormData();
                    fd.append('files[]', file);
                    try {
                        const res  = await fetch(UPLOAD_URL, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}, body:fd, credentials:'same-origin' });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data?.message || 'Upload failed.');
                        if (prevEl) prevEl.textContent = '✅ Uploaded';
                        uploadedCount++;
                        toastr.success(data?.message || 'Upload successful!');
                    } catch (err) {
                        if (prevEl) prevEl.textContent = '❌ Upload failed';
                        toastr.error(err.message || 'Upload failed.');
                    } finally { isUploading = false; }
                }

                function submitWithAttachmentCheck() {
                    const total = existingCount + uploadedCount;
                    if (total === 0) {
                        toastr.error('Wajib upload minimal 1 attachment bukti pengerjaan sebelum menutup ticket.');
                        document.getElementById('admin-executor-attachment-container')?.scrollIntoView({ behavior: 'smooth' });
                        return;
                    }
                    btnClose.closest('form').submit();
                }

                @if ($ticket->status === 'Overdue')
                const statusSelect = document.getElementById('statusSelect');
                const btnLabel     = document.getElementById('btn-close-label');

                $('#statusSelect').on('change', function () {
                    if (this.value === 'Progress') {
                        btnLabel.textContent = 'Back to Progress';
                    } else if (this.value === 'Closed') {
                        btnLabel.textContent = 'Close this Ticket';
                    }
                });

                btnClose?.addEventListener('click', function () {
                    if (!statusSelect?.value) {
                        toastr.error('Please choose a status first.');
                        statusSelect?.scrollIntoView({ behavior: 'smooth' });
                        return;
                    }
                    submitWithAttachmentCheck();
                });
                @else
                btnClose?.addEventListener('click', submitWithAttachmentCheck);
                @endif

                addBtn?.addEventListener('click', addAttachment);
                eraseBtn?.addEventListener('click', eraseAttachment);
                openCameraBtn?.addEventListener('click', () => { const i=document.getElementById(activeInputId); i?.setAttribute('capture','environment'); i?.click(); closeModal(); });
                openFilesBtn?.addEventListener('click',  () => { const i=document.getElementById(activeInputId); i?.removeAttribute('capture'); i?.click(); closeModal(); });
                closeSourceBtn?.addEventListener('click', closeModal);
                sourceModal?.addEventListener('click', (e) => { if (e.target===sourceModal) closeModal(); });
            })();
        </script>
        @endif

        <script>
            function openPreviewModal(url, name) {
                document.getElementById('previewModalTitle').textContent = name;
                document.getElementById('previewModalIframe').src = url;
                const m = document.getElementById('previewModal');
                m.classList.remove('hidden'); m.classList.add('flex');
            }
            function closePreviewModal() {
                const m = document.getElementById('previewModal');
                m.classList.add('hidden'); m.classList.remove('flex');
                document.getElementById('previewModalIframe').src = '';
            }
            document.getElementById('previewModal')?.addEventListener('click', function(e) { if(e.target===this) closePreviewModal(); });
        </script>
    @endpush
@endsection