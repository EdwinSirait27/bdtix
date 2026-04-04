@extends('layouts.app')
@section('company', 'BD Departments')
@section('header', 'Review Ticket')
@section('subtitle', 'Rate your experience with this ticket')
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
    @role('human')
        <div class="px-4 space-y-6 pb-8 max-w-3xl mx-auto">
            @if (in_array($ticket->status, ['Closed', 'Overdue']) && !$ticket->review)
                <form method="POST" action="{{ route('reviewticketsfromhuman', request()->route('hash')) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="updated_at" value="{{ $ticket->updated_at->toISOString() }}">

                    @if ($errors->has('conflict'))
                        <div class="p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/30">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-yellow-400 mb-1">Caution!</p>
                                    <p class="text-sm text-yellow-300">{{ $errors->first('conflict') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div
                        class="bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 rounded-2xl p-6 shadow-xl">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.377 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.377-2.455a1 1 0 00-1.175 0l-3.377 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.98 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.286-3.966z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Rate Your Experience</h3>
                                <p class="text-sm text-slate-400">Help us improve our service</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-slate-300 mb-3">
                                How satisfied are you with the ticket resolution? <span class="text-red-400">*</span>
                            </label>
                            <select id="rating" name="rating" required
                                class="select2 w-full bg-slate-800 border border-slate-700 rounded-xl text-white">
                                <option value="">-- Select Rating --</option>
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} ★ -
                                        {{ $i === 5 ? 'Excellent' : ($i === 4 ? 'Good' : ($i === 3 ? 'Average' : ($i === 2 ? 'Poor' : 'Very Poor'))) }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
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
                            <label class="block text-sm font-semibold text-slate-300 mb-3">
                                Additional Comments <span class="text-slate-500">(Optional)</span>
                            </label>
                            <textarea name="comment" rows="4"
                                placeholder="Share your experience, suggestions, or any feedback about the service..."
                                class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all resize-none">{{ old('comment') }}</textarea>
                            @error('comment')
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

                    <div
                        class="bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 rounded-2xl p-6 shadow-xl">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $ticket->title }}</h3>
                                    <p class="text-sm text-slate-400">Queue #{{ $ticket->queue_number }}</p>
                                </div>
                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $ticket->status === 'Closed' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                                {{ $ticket->status }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-700">
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Handled By</p>
                                <p class="text-sm font-medium text-slate-300">
                                    {{ optional($ticket->executor->employee)->employee_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Category</p>
                                <p class="text-sm font-medium text-slate-300">{{ $ticket->category }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Priority</p>
                                <p class="text-sm font-medium text-slate-300">
                                    {{ $ticket->priority === 'High' ? '🔴 High' : ($ticket->priority === 'Medium' ? '🟡 Medium' : '🟢 Low') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Completed</p>
                                <p class="text-sm font-medium text-slate-300">
                                    {{ $ticket->finished ? \Carbon\Carbon::parse($ticket->finished)->format('d M Y, H:i') : '-' }}
                                </p>
                            </div>
                        </div>

                        <details class="mt-4 pt-4 border-t border-slate-700">
                            <summary class="text-sm text-blue-400 cursor-pointer hover:text-blue-300 transition-colors">
                                View full ticket details
                            </summary>
                            <div class="mt-4 space-y-3">
                                <div>
                                    <p class="text-xs text-slate-500 mb-1">Problem Description</p>
                                    <p class="text-sm text-slate-300">{{ $ticket->description }}</p>
                                </div>
                                @if ($ticket->notes_executor)
                                    <div>
                                        <p class="text-xs text-slate-500 mb-1">Executor Notes</p>
                                        <p class="text-sm text-slate-300">{{ $ticket->notes_executor }}</p>
                                    </div>
                                @endif
                                @if ($ticket->attachments->count())
                                    <div>
                                        <p class="text-xs text-slate-500 mb-2">Attachments</p>

                                        <div class="space-y-2">
                                            @foreach ($ticket->attachments as $file)
                                                <span class="flex items-center space-x-2 text-sm text-blue-400
                       hover:text-blue-300 hover:underline">

                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                                    </svg>

                                                    <span>{{ $file->original_name ?? $file->file_name }}</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </details>
                    </div>

                    <div class="flex space-x-3">
                        <a href="{{ route('dashboard') }}"
                            class="flex-1 py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span>Back</span>
                        </a>

                        <button type="submit"
                            class="flex-1 py-3.5 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white font-semibold rounded-xl shadow-lg shadow-yellow-500/30 hover:shadow-yellow-500/50 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <span>Submit Review</span>
                        </button>
                    </div>
                </form>
            @endif

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

                    <div class="bg-slate-900/50 rounded-xl p-4 space-y-3">
                        <div>
                            <p class="text-xs text-slate-500 mb-2">Your Rating</p>
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
                                <p class="text-xs text-slate-500 mb-2">Your Comment</p>
                                <p class="text-sm text-slate-300 italic">"{{ $ticket->review->comment }}"</p>
                            </div>
                        @else
                            <p class="text-sm text-slate-500 italic">No comment provided</p>
                        @endif
                    </div>

                    <a href="{{ route('dashboard') }}"
                        class="mt-4 w-full py-3 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back to Dashboard</span>
                    </a>
                </div>
            @endif

        </div>

        @push('scripts')
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

            <script>
                $(document).ready(function() {
                    $('#rating').select2({
                        placeholder: '-- Select Rating --',
                        width: '100%',
                        dropdownParent: $('#rating').parent(),
                        minimumResultsForSearch: -1
                    });
                });

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
        @endpush
    @endsection
@endrole
@role('admin|executor')
    <div class="px-4 space-y-6 pb-8 max-w-3xl mx-auto">
        @if (in_array($ticket->status, ['Closed', 'Overdue']) && !$ticket->review)
            <form method="POST" action="{{ route('reviewticketsfromhuman', request()->route('hash')) }}"
                class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="updated_at" value="{{ $ticket->updated_at->toISOString() }}">

                @if ($errors->has('conflict'))
                    <div class="p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/30">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-yellow-400 mb-1">Caution!</p>
                                <p class="text-sm text-yellow-300">{{ $errors->first('conflict') }}</p>
                            </div>
                        </div>
                    </div>
                @endif



                <div
                    class="bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 rounded-2xl p-6 shadow-xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">{{ $ticket->title }}</h3>
                                <p class="text-sm text-slate-400">Queue #{{ $ticket->queue_number }}</p>
                            </div>
                        </div>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $ticket->status === 'Closed' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                            {{ $ticket->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-700">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Handled By</p>
                            <p class="text-sm font-medium text-slate-300">
                                {{ optional($ticket->executor->employee)->employee_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Category</p>
                            <p class="text-sm font-medium text-slate-300">{{ $ticket->category }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Priority</p>
                            <p class="text-sm font-medium text-slate-300">
                                {{ $ticket->priority === 'High' ? '🔴 High' : ($ticket->priority === 'Medium' ? '🟡 Medium' : '🟢 Low') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Completed</p>
                            <p class="text-sm font-medium text-slate-300">
                                {{ $ticket->finished ? \Carbon\Carbon::parse($ticket->finished)->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                    </div>

                    <details class="mt-4 pt-4 border-t border-slate-700">
                        <summary class="text-sm text-blue-400 cursor-pointer hover:text-blue-300 transition-colors">
                            View full ticket details
                        </summary>
                        <div class="mt-4 space-y-3">
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Problem Description</p>
                                <p class="text-sm text-slate-300">{{ $ticket->description }}</p>
                            </div>
                            @if ($ticket->notes_executor)
                                <div>
                                    <p class="text-xs text-slate-500 mb-1">Executor Notes</p>
                                    <p class="text-sm text-slate-300">{{ $ticket->notes_executor }}</p>
                                </div>
                            @endif
                            @if ($ticket->attachments->count())
                                <div>
                                    <p class="text-xs text-slate-500 mb-2">Attachments</p>

                                    <div class="space-y-2">
                                        @foreach ($ticket->attachments as $file)
                                            <span class="flex items-center space-x-2 text-sm text-blue-400
                       hover:text-blue-300 hover:underline">

                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                                </svg>

                                                <span>{{ $file->original_name ?? $file->file_name }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </details>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('dashboard') }}"
                        class="flex-1 py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back</span>
                    </a>
                </div>
            </form>
        @endif

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
                                {{ $ticket->user->employee->employee_name }}
                            </p>
                            <div class="flex items-center space-x-2">
                                <div class="flex text-yellow-400 text-lg">
                                    @for ($i = 1; $i <= 5; $i++)
                                        {{ $i <= $ticket->review->rating ? '★' : '☆' }}
                                    @endfor
                                </div>
                                <span class="text-sm text-slate-400">({{ $ticket->review->rating }}/5)</span>
                                <p class="text-sm text-slate-500 italic">No rating provided</p>

                            </div>
                        </div>
                @endif
                @if ($ticket->review->comment)
                    <div>
                        @role('admin|executor')
                            <p class="text-xs text-slate-500 mb-2">Comment By :
                                {{ $ticket->user->employee->employee_name }}</p>
                            <p class="text-sm text-slate-300 italic">"{{ $ticket->review->comment }}"</p>
                        @endrole
                    </div>
                @else
                    <p class="text-sm text-slate-500 italic">No comment provided</p>
                @endif
            </div>

            <a href="{{ route('dashboard') }}"
                class="mt-4 w-full py-3 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Dashboard</span>
            </a>
    </div>
    @endif

    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#rating').select2({
                    placeholder: '-- Select Rating --',
                    width: '100%',
                    dropdownParent: $('#rating').parent(),
                    minimumResultsForSearch: -1
                });
            });

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
    @endpush
@endsection
@endrole


{{-- @extends('layouts.app')
@section('company', 'IT Departments')
@section('header', 'Review Tickets')
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

        <form method="POST" action="{{ route('reviewticketsfromhuman', request()->route('hash')) }}" class="space-y-6">
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
                <input type="text" id="title" name="title" disabled
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
                    <select id="category" name="category" readonly
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
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-3 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Priority Level</span>
                    <span class="text-red-400">*</span>
                </label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="priority" value="Low" id="Low" class="peer sr-only"
                            disabled @checked(old('priority', $ticket->priority ?? '') === 'Low')>

                        <div
                            class="px-4 py-3 bg-slate-800 border-2 border-slate-700 rounded-xl text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-500/10 hover:border-slate-600">
                            <div class="text-2xl mb-1">🟢</div>
                            <div class="text-xs font-semibold text-slate-400 peer-checked:text-green-400">Low</div>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="priority" value="Medium" id="Medium" class="peer sr-only"
                            disabled @checked(old('priority', $ticket->priority ?? '') === 'Medium')>

                        <div
                            class="px-4 py-3 bg-slate-800 border-2 border-slate-700 rounded-xl text-center transition-all peer-checked:border-yellow-500 peer-checked:bg-yellow-500/10 hover:border-slate-600">
                            <div class="text-2xl mb-1">🟡</div>
                            <div class="text-xs font-semibold text-slate-400 peer-checked:text-yellow-400">Mid</div>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="priority" value="High" id="High" class="peer sr-only"
                            disabled @checked(old('priority', $ticket->priority ?? '') === 'High')>

                        <div
                            class="px-4 py-3 bg-slate-800 border-2 border-slate-700 rounded-xl text-center transition-all peer-checked:border-red-500 peer-checked:bg-red-500/10 hover:border-slate-600">
                            <div class="text-2xl mb-1">🔴</div>
                            <div class="text-xs font-semibold text-slate-400 peer-checked:text-red-400">High</div>
                        </div>
                    </label>
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
            </div>
            <div>
                <label for="notes_executor"
                    class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span>Notes Executor</span>
                    <span class="text-red-400">*</span>
                </label>
                <textarea id="notes_executor" name="notes_executor" rows="5" disabled
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

            <div>
                <label for="estimation"
                    class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Estimation</span>
                    <span class="text-red-400">*</span>
                </label>


                <input type="datetime-local" id="estimation" name="estimation"
                    value="{{ old('estimation') ?? $ticket->estimation }}"
                    class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white" disabled>




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
            <div>
                <label for="status" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-3-3v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Status</span>
                    <span class="text-red-400">*</span>
                </label>

                <div class="relative">
                    <select id="status" name="status" readonly
                        class="select2 w-full bg-slate-800 border border-slate-700 rounded-xl text-white">

                        <option value="">Choose Status...</option>
                        <option value="Open" @selected(old('status', $ticket->status) === 'Open')>Open</option>
                        <option value="Progress" @selected(old('status', $ticket->status) === 'Progress')>In Progress</option>
                        <option value="Closed" @selected(old('status', $ticket->status) === 'Closed')>Closed</option>
                    </select>
                </div>

                @error('status')
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

            <div class="relative">
                <label for="finished" class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Finished</span>
                    <span class="text-red-400">*</span>
                </label>

                <input type="datetime-local" id="finished" name="finished"
                    value="{{ old('finished') ?? $ticket->finished }}"
                    class="w-full px-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white" disabled>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-300 mb-2 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <span>Attachments</span>
                </label>
                @if ($ticket->attachments->count())
                    <ul class="space-y-2">
                        @foreach ($ticket->attachments as $file)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8 2a4 4 0 00-4 4v8a6 6 0 0012 0V6a2 2 0 10-4 0v7a1 1 0 102 0V6a4 4 0 00-8 0v8a4 4 0 008 0V6" />
                                </svg>
                                <span class="text-blue-400 hover:underline text-sm">
                                    {{ $file->file_name }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-slate-500">No attachments</p>
                @endif
            </div>
            @if (in_array($ticket->status, ['Closed', 'Overdue']) && !$ticket->review)
                <div class="pt-6 border-t border-slate-700 space-y-4">
                    <label class="block text-sm font-semibold text-slate-300 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286
                         3.966a1 1 0 00.95.69h4.173c.969 0
                         1.371 1.24.588 1.81l-3.377
                         2.455a1 1 0 00-.364 1.118l1.287
                         3.966c.3.921-.755 1.688-1.54
                         1.118l-3.377-2.455a1 1 0 00-1.175
                         0l-3.377 2.455c-.784.57-1.838
                         -.197-1.539-1.118l1.287-3.966a1
                         1 0 00-.364-1.118L2.98
                         9.393c-.783-.57-.38-1.81.588
                         -1.81h4.173a1 1 0 00.95-.69
                         l1.286-3.966z" />
                        </svg>
                        <span>Review Ticket</span>
                        <span class="text-red-400">*</span>
                    </label>
                    <div>
                        <label class="block text-sm text-slate-400 mb-2">Rating</label>
                        <select id="rating" name="rating" required
                            class="select2 w-full bg-slate-800 border border-slate-700 rounded-xl text-white">

                            <option value="">-- Select Rating --</option>
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} ★
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-slate-400 mb-2">Comment (optional)</label>
                        <textarea name="comment" rows="4" placeholder="Write down your experience regarding solving this ticket..."
                            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-yellow-500">{{ old('comment') }}</textarea>
                    </div>

                </div>
            @endif
            @if ($ticket->review)
                <div class="pt-6 border-t border-slate-700 space-y-4">
                    <label class="block text-sm font-semibold text-slate-300 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586
                         4.707 9.293a1 1 0 00-1.414
                         1.414l4 4a1 1 0 001.414
                         0l8-8a1 1 0 000-1.414z" />
                        </svg>
                        @role('human')
                            <span>Your review</span>
                        @endrole

                        @role('admin|executor')
                            <span>Reviewed By : {{ $ticket->user->employee->employee_name }}</span>
                        @endrole

                    </label>

                 
                    <div class="flex items-center space-x-2 text-yellow-400">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $ticket->review->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                        <span class="text-sm text-slate-400 ml-2">
                            ({{ $ticket->review->rating }}/5)
                        </span>
                    </div>

                    @if ($ticket->review->comment)
                        <p class="text-slate-300 italic">
                            Comment : “{{ $ticket->review->comment }}”
                        </p>
                    @else
                        <p class="text-slate-500 italic">
                            No comment provided.
                        </p>
                    @endif
                </div>
            @endif



            <div class="flex space-x-3 pt-4">
                <a href="{{ route('dashboard') }}"
                    class="flex-1 py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Back</span>
                </a>

                @role('human')
                    @if (is_null($ticket->review))
                        <button type="submit"
                            class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600
                   hover:from-blue-700 hover:to-cyan-700 text-white font-semibold
                   rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50
                   transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]
                   flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Submit Review</span>
                        </button>
                    @endif

                @endrole

        </form>
    </div>
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#status').select2({
                    placeholder: 'Choose Status...',
                    width: '100%',
                    dropdownParent: $('#status').parent()
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#rating').select2({
                    placeholder: 'Choose Rating...',
                    width: '100%',
                    dropdownParent: $('#rating').parent()
                });
            });
        </script>
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
                const finishedInput = document.getElementById('finished');

                flatpickr(finishedInput, {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true,
                    defaultDate: finishedInput.value || null,
                    minDate: finishedInput.value ? null : "today",
                    allowInput: true
                });
            });
        </script>
        <script>
            flatpickr("#finished", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minDate: "today",
                defaultHour: 9
            });
        </script>
    @endpush
@endsection --}}

















































{{-- @extends('layouts.app')
@section('company', 'IT Departments')
@section('header', 'Review Ticket')
@section('subtitle', 'Rate your experience with this ticket')
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

    <div class="px-4 space-y-6 pb-8 max-w-3xl mx-auto">
        @if (in_array($ticket->status, ['Closed', 'Overdue']) && !$ticket->review)
            <form method="POST" action="{{ route('reviewticketsfromhuman', request()->route('hash')) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="updated_at" value="{{ $ticket->updated_at->toISOString() }}">

                @if ($errors->has('conflict'))
                    <div class="p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/30">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-yellow-400 mb-1">Caution!</p>
                                <p class="text-sm text-yellow-300">{{ $errors->first('conflict') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div
                    class="bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 rounded-2xl p-6 shadow-xl">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.377 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.377-2.455a1 1 0 00-1.175 0l-3.377 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.98 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.286-3.966z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Rate Your Experience</h3>
                            <p class="text-sm text-slate-400">Help us improve our service</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-300 mb-3">
                            How satisfied are you with the ticket resolution? <span class="text-red-400">*</span>
                        </label>
                        <select id="rating" name="rating" required
                            class="select2 w-full bg-slate-800 border border-slate-700 rounded-xl text-white">
                            <option value="">-- Select Rating --</option>
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} ★ -
                                    {{ $i === 5 ? 'Excellent' : ($i === 4 ? 'Good' : ($i === 3 ? 'Average' : ($i === 2 ? 'Poor' : 'Very Poor'))) }}
                                </option>
                            @endfor
                        </select>
                        @error('rating')
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
                        <label class="block text-sm font-semibold text-slate-300 mb-3">
                            Additional Comments <span class="text-slate-500">(Optional)</span>
                        </label>
                        <textarea name="comment" rows="4"
                            placeholder="Share your experience, suggestions, or any feedback about the service..."
                            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all resize-none">{{ old('comment') }}</textarea>
                        @error('comment')
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

                <div
                    class="bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 rounded-2xl p-6 shadow-xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">{{ $ticket->title }}</h3>
                                <p class="text-sm text-slate-400">Queue #{{ $ticket->queue_number }}</p>
                            </div>
                        </div>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $ticket->status === 'Closed' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                            {{ $ticket->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-700">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Handled By</p>
                            <p class="text-sm font-medium text-slate-300">
                                {{ optional($ticket->executor->employee)->employee_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Category</p>
                            <p class="text-sm font-medium text-slate-300">{{ $ticket->category }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Priority</p>
                            <p class="text-sm font-medium text-slate-300">
                                {{ $ticket->priority === 'High' ? '🔴 High' : ($ticket->priority === 'Medium' ? '🟡 Medium' : '🟢 Low') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Completed</p>
                            <p class="text-sm font-medium text-slate-300">
                                {{ $ticket->finished ? \Carbon\Carbon::parse($ticket->finished)->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                    </div>

                    <details class="mt-4 pt-4 border-t border-slate-700">
                        <summary class="text-sm text-blue-400 cursor-pointer hover:text-blue-300 transition-colors">
                            View full ticket details
                        </summary>
                        <div class="mt-4 space-y-3">
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Problem Description</p>
                                <p class="text-sm text-slate-300">{{ $ticket->description }}</p>
                            </div>
                            @if ($ticket->notes_executor)
                                <div>
                                    <p class="text-xs text-slate-500 mb-1">Executor Notes</p>
                                    <p class="text-sm text-slate-300">{{ $ticket->notes_executor }}</p>
                                </div>
                            @endif
                            @if ($ticket->attachments->count())
                                <div>
                                    <p class="text-xs text-slate-500 mb-2">Attachments</p>

                                    <div class="space-y-2">
                                        @foreach ($ticket->attachments as $file)
                                            <span class="flex items-center space-x-2 text-sm text-blue-400
                       hover:text-blue-300 hover:underline">

                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                                </svg>

                                                <span>{{ $file->original_name ?? $file->file_name }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                            @endif
                        </div>
                    </details>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('dashboard') }}"
                        class="flex-1 py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Back</span>
                    </a>

                    <button type="submit"
                        class="flex-1 py-3.5 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white font-semibold rounded-xl shadow-lg shadow-yellow-500/30 hover:shadow-yellow-500/50 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <span>Submit Review</span>
                    </button>
                </div>
            </form>
        @endif

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

                <div class="bg-slate-900/50 rounded-xl p-4 space-y-3">
                    <div>
                        @role('human')
                            <p class="text-xs text-slate-500 mb-2">Your Rating</p>
                        @endrole
                        @role('admin|executor')
                            <p class="text-xs text-slate-500 mb-2">Reviewed By : {{ $ticket->user->employee->employee_name }}
                            </p>
                        @endrole
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
                            @role('human')
                                <p class="text-xs text-slate-500 mb-2">Your Comment</p>
                                <p class="text-sm text-slate-300 italic">"{{ $ticket->review->comment }}"</p>
                            @endrole
                            @role('admin|executor')
                                <p class="text-xs text-slate-500 mb-2">Comment By :
                                    {{ $ticket->user->employee->employee_name }}</p>
                                <p class="text-sm text-slate-300 italic">"{{ $ticket->review->comment }}"</p>
                            @endrole
                        </div>
                    @else
                        <p class="text-sm text-slate-500 italic">No comment provided</p>
                    @endif
                </div>

                <a href="{{ route('dashboard') }}"
                    class="mt-4 w-full py-3 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        @endif

    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#rating').select2({
                    placeholder: '-- Select Rating --',
                    width: '100%',
                    dropdownParent: $('#rating').parent(),
                    minimumResultsForSearch: -1
                });
            });

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
    @endpush
@endsection --}}


