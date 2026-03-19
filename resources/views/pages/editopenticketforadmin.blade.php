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
                    <p class="text-xs text-slate-400 leading-relaxed">Date : {{ $createdat }}
                    <p class="text-xs text-slate-400 leading-relaxed">Queue Number : {{ optional($ticket)->queue_number }}
                    </p>
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
                <textarea id="description" name="description" rows="5" disabled
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

            @error('priority')
                <p class="mt-2 text-sm text-red-400 flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ $message }}</span>
                </p>
            @enderror

            <div class="-mt-4">
                <label for="notes_executor"
                    class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span>Notes Executor</span>
                    <span class="text-red-400">*</span>
                </label>
                <textarea id="notes_executor" name="notes_executor" rows="5" required
                    placeholder="Describe user's problem in detail:
- What happened?"
                    class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none">{{ old('notes_executor', $ticket->notes_executor) }}</textarea>
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-slate-500">minimum 10 character</p>
                    <p class="text-xs text-slate-500"><span id="charCount">0</span> / 500</p>
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

            <div class="mt-4">
                <label for="duration_type"
                    class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Duration</span>
                    <span class="text-red-400">*</span>
                </label>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <div>
                        <select id="duration_type" name="duration_type"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3.5 bg-slate-800 border border-slate-700 rounded-lg sm:rounded-xl text-sm sm:text-base text-white" required>
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
                        <select id="duration_value_select"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3.5 bg-slate-800 border border-slate-700 rounded-lg sm:rounded-xl text-sm sm:text-base text-white" required>
                            <option value="">Choose Duration</option>
                        </select>
                        <input type="time" id="duration_hour_time"
                            class="hidden w-full px-3 sm:px-4 py-2.5 sm:py-3.5 bg-slate-800 border border-slate-700 rounded-lg sm:rounded-xl text-sm sm:text-base text-white"
                            step="3600">
                        <input type="hidden" id="duration_value" name="duration_value"
                            value="{{ old('duration_value') }}">
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

                <input type="datetime-local" id="estimation" name="estimation"
                    value="{{ old('estimation') ?? $ticket->estimation }}"
                    class="hidden">

                @error('estimation')
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

            <div class="hidden">
                <input type="datetime-local" id="estimation_to" name="estimation_to"
                    value="{{ old('estimation_to') ?? $ticket->estimation_to }}"
                    class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white">
                @error('estimation_to')
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

            {{-- Attachment Section --}}
            <div class="mt-4">
                <label class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <span>Attachments</span>
                </label>

                {{-- Attachment dari user --}}
                @if ($ticket->attachments->count())
                    <ul class="space-y-2 mb-3">
                        @foreach ($ticket->attachments as $file)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8 2a4 4 0 00-4 4v8a6 6 0 0012 0V6a2 2 0 10-4 0v7a1 1 0 102 0V6a4 4 0 00-8 0v8a4 4 0 008 0V6" />
                                </svg>
                                <a href="{{ $file->web_view_link }}" target="_blank"
                                    class="text-blue-400 hover:underline text-sm">
                                    {{ $file->original_name ?? $file->file_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-slate-500 mb-3">No attachments</p>
                @endif

                {{-- Kotak file list executor --}}
                <div class="border border-slate-700 rounded-xl p-4 bg-slate-800/40 min-h-[100px]">
                    <ul id="admin-executor-attachments-list" class="space-y-1 text-sm text-slate-300">
                        <li id="executor-empty-text" class="text-slate-500">No files selected</li>
                    </ul>
                </div>

                {{-- Tombol --}}
                <div class="mt-3 flex flex-col sm:flex-row gap-3">
                    <button type="button" id="admin-executor-select-files"
                        class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 rounded-lg transition">
                        Select Files
                    </button>
                    <button type="button" id="admin-executor-upload-btn"
                        class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white rounded-lg transition">
                        Upload
                    </button>
                </div>
                <p class="mt-2 text-xs text-slate-500">Max 10 files, 20MB each.</p>
            </div>

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
                        class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>take this ticket</span>
                    </button>
                @endif

                @if ($ticket->status === 'Progress')
                    <input type="hidden" name="status" value="Closed">
                    <button type="submit" name="action" value="close"
                        class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Closed this Ticket</span>
                    </button>
                @endif

                @if ($ticket->status === 'Overdue')
                    <input type="hidden" name="status" value="Closed">
                    <button type="submit" name="action" value="close"
                        class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Update this Ticket</span>
                    </button>
                @endif
            </div>

            {{-- Hidden form untuk upload executor --}}
            <form id="admin-executor-attachments-form"
                action="{{ route('executor.attachments.store', $ticket->id) }}"
                method="POST"
                enctype="multipart/form-data"
                class="hidden">
                @csrf
                <input type="file" id="admin-executor-files-input" name="files[]" multiple
                    accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip,.txt" style="display:none">
            </form>

        </form>
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

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const estimationInput = document.getElementById('estimation');
                flatpickr(estimationInput, {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true,
                    defaultDate: estimationInput.value || null,
                    minDate: estimationInput.value ? null : "today",
                    allowInput: true
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const estimationtoInput = document.getElementById('estimation_to');
                flatpickr(estimationtoInput, {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true,
                    defaultDate: estimationtoInput.value || null,
                    minDate: estimationtoInput.value ? null : "today",
                    allowInput: true
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const durationType = document.getElementById('duration_type');
                const durationValueSelect = document.getElementById('duration_value_select');
                const durationHourTime = document.getElementById('duration_hour_time');
                const durationValueInput = document.getElementById('duration_value');
                const estimationInput = document.getElementById('estimation');
                const estimationToInput = document.getElementById('estimation_to');

                if (!durationType || !durationValueSelect || !durationHourTime || !durationValueInput || !estimationInput || !estimationToInput) {
                    return;
                }

                const ranges = {
                    hour: { min: 1, max: 24, label: 'Hour' },
                    day: { min: 2, max: 6, label: 'Day' },
                    week: { min: 1, max: 4, label: 'Week' },
                };

                const formatDateTimeLocal = (date) => {
                    const yyyy = date.getFullYear();
                    const mm = String(date.getMonth() + 1).padStart(2, '0');
                    const dd = String(date.getDate()).padStart(2, '0');
                    const hh = String(date.getHours()).padStart(2, '0');
                    const mi = String(date.getMinutes()).padStart(2, '0');
                    return `${yyyy}-${mm}-${dd}T${hh}:${mi}`;
                };

                const syncStartTime = () => {
                    if (!estimationInput.value) {
                        estimationInput.value = formatDateTimeLocal(new Date());
                    }
                };

                const buildDurationOptions = () => {
                    const type = durationType.value;
                    durationValueSelect.innerHTML = '';
                    if (!ranges[type]) {
                        durationValueSelect.appendChild(new Option('Choose type first...', ''));
                        return;
                    }
                    durationValueSelect.appendChild(new Option('Choose duration...', ''));
                    const { min, max, label } = ranges[type];
                    for (let i = min; i <= max; i++) {
                        durationValueSelect.appendChild(new Option(`${i} ${label}`, i));
                    }
                };

                const syncDurationValue = () => {
                    const type = durationType.value;
                    if (type === 'hour') {
                        const value = durationHourTime.value;
                        if (!value) { durationValueInput.value = ''; return; }
                        const parts = value.split(':');
                        const hours = parseInt(parts[0] || '0', 10);
                        durationValueInput.value = hours ? String(hours) : '';
                        return;
                    }
                    durationValueInput.value = durationValueSelect.value || '';
                };

                const computeEstimationTo = () => {
                    syncStartTime();
                    const type = durationType.value;
                    const value = parseInt(durationValueInput.value || '0', 10);
                    if (!type || !value || !estimationInput.value) return;
                    const start = new Date(estimationInput.value);
                    if (Number.isNaN(start.getTime())) return;
                    let minutes = 0;
                    if (type === 'hour') minutes = value * 60;
                    if (type === 'day') minutes = value * 24 * 60;
                    if (type === 'week') minutes = value * 7 * 24 * 60;
                    const end = new Date(start.getTime() + minutes * 60000);
                    estimationToInput.value = formatDateTimeLocal(end);
                };

                durationType.addEventListener('change', () => {
                    const type = durationType.value;
                    if (type === 'hour') {
                        durationValueSelect.classList.add('hidden');
                        durationHourTime.classList.remove('hidden');
                    } else {
                        durationValueSelect.classList.remove('hidden');
                        durationHourTime.classList.add('hidden');
                        buildDurationOptions();
                    }
                    syncDurationValue();
                    computeEstimationTo();
                });
                durationValueSelect.addEventListener('change', () => { syncDurationValue(); computeEstimationTo(); });
                durationHourTime.addEventListener('change', () => { syncDurationValue(); computeEstimationTo(); });

                const showTimePicker = () => {
                    if (durationHourTime && typeof durationHourTime.showPicker === 'function') {
                        durationHourTime.showPicker();
                    }
                };
                durationHourTime.addEventListener('focus', showTimePicker);
                durationHourTime.addEventListener('click', showTimePicker);

                buildDurationOptions();
                syncDurationValue();
                computeEstimationTo();
            });
        </script>

        {{-- Script attachment executor dengan tombol ✕ per file --}}
        <script>
            (function () {
                const filesInput     = document.getElementById('admin-executor-files-input');
                const selectBtn      = document.getElementById('admin-executor-select-files');
                const uploadBtn      = document.getElementById('admin-executor-upload-btn');
                const list           = document.getElementById('admin-executor-attachments-list');
                const emptyText      = document.getElementById('executor-empty-text');
                const CSRF           = document.querySelector('meta[name="csrf-token"]')?.content
                                    ?? document.querySelector('input[name="_token"]')?.value;
                const UPLOAD_URL     = "{{ route('executor.attachments.store', $ticket->id) }}";
                const DELETE_URL     = (id) => `/tickets/{{ $ticket->id }}/executor-attachments/${id}`;

                let pendingFiles  = [];
                let uploadedFiles = [];

                // Klik Select Files
                selectBtn?.addEventListener('click', () => filesInput?.click());

                // Pilih file
                filesInput?.addEventListener('change', function () {
                    for (const f of this.files) {
                        if (!pendingFiles.find(x => x.name === f.name && x.size === f.size)) {
                            pendingFiles.push(f);
                        }
                    }
                    this.value = '';
                    renderList();
                });

                // Upload
                uploadBtn?.addEventListener('click', async () => {
                    if (!pendingFiles.length) {
                        toastr.error('Pilih file terlebih dahulu.');
                        return;
                    }
                    uploadBtn.disabled = true;
                    uploadBtn.textContent = 'Uploading...';

                    const formData = new FormData();
                    pendingFiles.forEach(f => formData.append('files[]', f));

                    try {
                        const res  = await fetch(UPLOAD_URL, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                            body: formData,
                            credentials: 'same-origin',
                        });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data?.message || 'Upload gagal.');

                        uploadedFiles.push(...(data.attachments ?? []));
                        pendingFiles = [];
                        renderList();
                        toastr.success(data?.message || 'Upload berhasil!');
                    } catch (err) {
                        toastr.error(err.message || 'Upload gagal.');
                    } finally {
                        uploadBtn.disabled = false;
                        uploadBtn.textContent = 'Upload';
                    }
                });

                function removePending(index) {
                    pendingFiles.splice(index, 1);
                    renderList();
                }

                function removeUploaded(index) {
                    const file = uploadedFiles[index];
                    if (!file?.id) {
                        uploadedFiles.splice(index, 1);
                        renderList();
                        return;
                    }
                    fetch(DELETE_URL(file.id), {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error();
                        uploadedFiles.splice(index, 1);
                        renderList();
                        toastr.success('File berhasil dihapus.');
                    })
                    .catch(() => toastr.error('Gagal menghapus file.'));
                }

                function renderList() {
                    const allEmpty = !pendingFiles.length && !uploadedFiles.length;
                    list.querySelectorAll('.exec-row').forEach(el => el.remove());
                    emptyText.style.display = allEmpty ? 'block' : 'none';

                    // File sudah terupload
                    uploadedFiles.forEach((f, i) => {
                        list.insertAdjacentHTML('beforeend', buildRow(
                            f.original_name, f.size, true, i, 'uploaded'
                        ));
                    });

                    // File pending
                    pendingFiles.forEach((f, i) => {
                        list.insertAdjacentHTML('beforeend', buildRow(
                            f.name, f.size, false, i, 'pending'
                        ));
                    });

                    // Pasang event ✕
                    list.querySelectorAll('.exec-row .btn-remove').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const type  = btn.dataset.type;
                            const index = parseInt(btn.dataset.index);
                            if (type === 'uploaded') removeUploaded(index);
                            else removePending(index);
                        });
                    });
                }

                function buildRow(name, size, uploaded, index, type) {
                    const ext = name.split('.').pop().toLowerCase();
                    let icon = '📎';
                    if (['png','jpg','jpeg','gif','webp'].includes(ext)) icon = '🖼';
                    else if (ext === 'pdf') icon = '📄';
                    else if (['doc','docx'].includes(ext)) icon = '📝';
                    else if (['xls','xlsx'].includes(ext)) icon = '📊';
                    else if (ext === 'zip') icon = '🗜';

                    const badge = uploaded
                        ? `<span class="text-xs text-green-400 ml-1">(uploaded)</span>`
                        : '';
                    return `
                    <li class="exec-row flex items-center justify-between px-3 py-2 bg-slate-700/60 rounded-lg mb-1">
                        <div class="flex items-center gap-2 min-w-0">
                            <span style="font-size:16px;">${icon}</span>
                            <span class="text-sm text-slate-200 truncate">${name}${badge}</span>
                            <span class="text-xs text-slate-400 shrink-0">${fmtSize(size)}</span>
                        </div>
                        <button type="button"
                            class="btn-remove text-slate-400 hover:text-red-400 transition-colors text-base leading-none px-2 shrink-0"
                            data-type="${type}"
                            data-index="${index}"
                            title="Hapus">✕</button>
                    </li>`;
                }

                function fmtSize(b) {
                    if (!b) return '';
                    if (b < 1024) return b + ' B';
                    if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
                    return (b / 1048576).toFixed(1) + ' MB';
                }
            })();
        </script>
    @endpush
@endsection
