@extends('layouts.app')
@section('title', 'BD Tickets Queue' . $ticket->queue_number)
@section('header', 'BD Ticket Detail')
@section('subtitle', 'Detail and status tickets')
@section('content')
    <div class="max-w-10xl mx-auto space-y-12">

        {{-- Ticket Info --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                        Title : {{ $ticket->title ?? 0 }}
                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Ticket Queue Number {{ $ticket->queue_number ?? 0 }} • Created
                        {{ $ticket->created_at->format('d F Y H:i') }}
                    </p>


                </div>
                <div>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold ">
                        Status : {{ strtoupper(str_replace('_', ' ', $ticket->status)) ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Ticket Meta --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Created By</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->user->employee->employee_name ?? $ticket->user->email }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Dificulty</p>
                <p class="font-semibold mt-1">
                    {{ ucfirst($ticket->priority ?? '-') }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Category</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->category ?? '-' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Sub Category</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->sub_category ?? '-' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Executor</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->executor ?? '-' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Finished</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->finished ?? '-' }}
                </p>
            </div>

        </div>

        {{-- Description --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
            <h3 class="text-lg font-bold mb-3">Description</h3>
            <div class="prose dark:prose-invert max-w-none text-sm">
                {!! nl2br(e($ticket->description)) !!}
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
            <h3 class="text-lg font-bold mb-3">Notes from BD</h3>
            <div class="prose dark:prose-invert max-w-none text-sm">
                {!! nl2br(e($ticket->notes_executor ?? '-')) !!}
            </div>
        </div>
        @if ($ticket->attachments->count())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
                <h3 class="text-lg font-bold mb-4">Attachments</h3>
                <ul class="space-y-2">
                    @foreach ($ticket->attachments as $file)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8 2a4 4 0 00-4 4v8a6 6 0 0012 0V6a2 2 0 10-4 0v7a1 1 0 102 0V6a4 4 0 00-8 0v8a4 4 0 008 0V6" />
                            </svg>
                            <span class="text-blue-500 hover:underline text-sm">
                                {{ $file->original_name ?? $file->file_name }}
                            </span>
                            @if (!empty($file->human_size))
                                <span class="text-xs text-slate-500">({{ $file->human_size }})</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($ticket->replies && $ticket->replies->count())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
                <h3 class="text-lg font-bold mb-4">Activity</h3>
                <div class="space-y-4">
                    @foreach ($ticket->replies as $reply)
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm font-semibold">
                                    {{ $reply->user->employee->employee_name ?? $reply->user->email }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $reply->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <p class="text-sm text-slate-700 dark:text-slate-300">
                                {{ $reply->message }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
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
@endsection

