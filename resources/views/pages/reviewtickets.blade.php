@extends('layouts.app')
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
    @role('human')
        <div class="px-4 space-y-6 pb-8 max-w-3xl mx-auto">
            {{-- Detail Ticket - Ditampilkan Selalu --}}
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
                            <h3 class="text-lg font-bold text-white">Title : {{ $ticket->title }}</h3>
                            <p class="text-sm text-slate-400">Ticket Queue Number : {{ $ticket->queue_number }}</p>
                            <p class="text-sm text-slate-400">Date :  {{ $ticket->created_at->format('d F Y H:i') }}</p>
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
                        <p class="text-xs text-slate-500 mb-1">Dificulty</p>
                        <p class="text-sm font-medium text-slate-300">
                            {{ $ticket->priority === 'High' ? '🔴 High' : ($ticket->priority === 'Medium' ? '🟡 Medium' : '🟢 Low') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Estimation</p>
                        <p class="text-sm font-medium text-slate-300">
                            {{ $ticket->estimation->format('d F Y H:i') }}
                        </p>
                    </div>
                       <div>
    <p class="text-xs text-slate-500 mb-1">Estimation To</p>
    <p class="text-sm font-medium text-slate-300">
        {{ optional($ticket->estimation_to)->format('d F Y H:i') ?? '-' }}
    </p>
</div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Finished</p>
                        <p class="text-sm font-medium text-slate-300">
                            {{ $ticket->finished->format('d F Y H:i') }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-700 space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">User Problem Description</p>
                        <p class="text-sm text-slate-300">{{ $ticket->description }}</p>
                    </div>
                    @if ($ticket->notes_executor)
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Notes IT</p>
                            <p class="text-sm text-slate-300">{{ $ticket->notes_executor }}</p>
                        </div>
                    @endif
                    @if ($ticket->attachments->count())
                        <div>
                            <p class="text-xs text-slate-500 mb-2">Attachments</p>

                            <div class="space-y-2">
                                @foreach ($ticket->attachments as $file)
                                    <a href="{{ $file->web_view_link }}" target="_blank"
                                        class="flex items-center space-x-2 text-sm text-blue-400 hover:text-blue-300 hover:underline">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                        </svg>
                                        <span>{{ $file->original_name ?? $file->file_name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if (in_array($ticket->status, ['Closed']) && !$ticket->review)
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
            {{-- Detail Ticket - Ditampilkan Selalu --}}
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
                            <h3 class="text-lg font-bold text-white">Title : {{ $ticket->title }}</h3>
                            <p class="text-sm text-slate-400">Ticket Queue Number : {{ $ticket->queue_number }}</p>
                            <p class="text-sm text-slate-400">Date :  {{ $ticket->created_at->format('d F Y H:i') }}</p>
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
                        <p class="text-xs text-slate-500 mb-1">Dificulty</p>
                        <p class="text-sm font-medium text-slate-300">
                            {{ $ticket->priority === 'High' ? '🔴 High' : ($ticket->priority === 'Medium' ? '🟡 Medium' : '🟢 Low') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Estimation</p>
                        <p class="text-sm font-medium text-slate-300">
                            {{ $ticket->estimation->format('d F Y H:i') }}
                        </p>
                    </div>
                   <div>
    <p class="text-xs text-slate-500 mb-1">Estimation To</p>
    <p class="text-sm font-medium text-slate-300">
        {{ optional($ticket->estimation_to)->format('d F Y H:i') ?? '-' }}
    </p>
</div>

                    <div>
                        <p class="text-xs text-slate-500 mb-1">Finished</p>
                        <p class="text-sm font-medium text-slate-300">
                            {{ $ticket->finished->format('d F Y H:i') }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-700 space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">User Problem Description</p>
                        <p class="text-sm text-slate-300">{{ $ticket->description }}</p>
                    </div>
                    @if ($ticket->notes_executor)
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Notes IT</p>
                            <p class="text-sm text-slate-300">{{ $ticket->notes_executor }}</p>
                        </div>
                    @endif
                    @if ($ticket->attachments->count())
                        <div>
                            <p class="text-xs text-slate-500 mb-2">Attachments</p>

                            <div class="space-y-2">
                                @foreach ($ticket->attachments as $file)
                                    <a href="{{ $file->web_view_link }}" target="_blank"
                                        class="flex items-center space-x-2 text-sm text-blue-400 hover:text-blue-300 hover:underline">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                        </svg>
                                        <span>{{ $file->original_name ?? $file->file_name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

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

                    <div class="bg-slate-900/50 rounded-xl p-4 space-y-3">
                        <div>
                            <p class="text-xs text-slate-500 mb-2">Ratings By : {{ $ticket->user->employee->employee_name }}</p>
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
                                <p class="text-xs text-slate-500 mb-2">{{ $ticket->user->employee->employee_name }} Comment</p>
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
