@extends('layouts.app')
@section('title', 'Tickets Queue' . $ticket->queue_number)
@section('header', 'Detail Ticket ')
@section('subtitle', 'Detail and ticket status')
@section('content')
    <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">
        {{-- Ticket Info --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg p-4 sm:p-6">
            <div class="flex flex-col space-y-3">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                        Title : {{ $ticket->title ?? 0 }}
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Ticket Queue Number {{ $ticket->queue_number ?? 0 }} • Created
                        {{ $ticket->created_at->format('d F Y H:i') }}
                    </p>
                </div>
                
                <div>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white">
                        Status : {{ strtoupper(str_replace('_', ' ', $ticket->status)) ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Ticket Meta --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Created By</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->user->employee->employee_name ?? $ticket->user->email }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Priority</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ ucfirst($ticket->priority ?? '-') }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Category</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->category ?? '-' }}
                </p>
            </div>
            
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Executor</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->executor?->employee?->employee_name ?? '-' }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase">Finished</p>
                <p class="font-semibold mt-1 text-slate-900 dark:text-white text-sm sm:text-base">
                    {{ $ticket->finished ?? '-' }}
                </p>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold mb-3 text-slate-900 dark:text-white">Description</h3>
            <div class="prose dark:prose-invert max-w-none text-sm text-slate-700 dark:text-slate-300">
                {!! nl2br(e($ticket->description)) !!}
            </div>
        </div>
        
        {{-- Notes from IT --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold mb-3 text-slate-900 dark:text-white">Notes from IT</h3>
            <div class="prose dark:prose-invert max-w-none text-sm text-slate-700 dark:text-slate-300">
                {!! nl2br(e($ticket->notes_executor ?? '-')) !!}
            </div>
        </div>

        {{-- Attachments --}}
        @if (!empty($ticket->attachment_url))
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold mb-4 text-slate-900 dark:text-white">Attachments</h3>

            <a href="{{ $ticket->attachment_url }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm
                      bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-md">
                Open Attachment Folder
            </a>
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
                                    {{ $reply->user->employee->employee_name ?? $reply->user->email }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $reply->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300">
                                {{ $reply->message }}
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
@endsection
{{-- @extends('layouts.app')
@section('title', 'Tickets Queue' . $ticket->queue_number)
@section('header', 'Ticket Detail')
@section('subtitle', 'Detail dan status ticket')
@section('content')
    <div class="max-w-10xl mx-auto space-y-12">
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

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Created By</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->user->employee->employee_name ?? $ticket->user->email }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Priority</p>
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
    <p class="text-xs text-slate-500 uppercase">Executor</p>
    <p class="font-semibold mt-1">
        {{ $ticket->executor?->employee?->employee_name ?? '-' }}
    </p>
</div>

            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Finished</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->finished ?? '-' }}
                </p>
            </div>

        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
            <h3 class="text-lg font-bold mb-3">Description</h3>
            <div class="prose dark:prose-invert max-w-none text-sm">
                {!! nl2br(e($ticket->description)) !!}
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
            <h3 class="text-lg font-bold mb-3">Notes from IT</h3>
            <div class="prose dark:prose-invert max-w-none text-sm">
                {!! nl2br(e($ticket->notes_executor ?? '-')) !!}
            </div>
        </div>
@if (!empty($ticket->attachment_url))
<div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-6">
    <h3 class="text-lg font-bold mb-4">Attachments</h3>

    <a href="{{ $ticket->attachment_url }}"
       target="_blank"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
              bg-indigo-600 text-white hover:bg-indigo-700 transition">
        Open Attachment Folder
    </a>
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
        <div class="flex justify-end">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-5 py-2.5 rounded-xl
                  bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white
                  hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                Back to Dashboard
            </a>
        </div>
    </div>
@endsection --}}
{{-- @extends('layouts.app')
@section('title', 'Tickets Queue' . $ticket->queue_number)
@section('header', 'Ticket Detail')
@section('subtitle', 'Detail dan status ticket')
@section('content')
    <div class="max-w-10xl mx-auto space-y-6">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-4 sm:p-6">
            <div class="space-y-3">
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                        Title : {{ $ticket->title ?? 0 }}
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Ticket Queue Number {{ $ticket->queue_number ?? 0 }} • Created
                        {{ $ticket->created_at->format('d F Y H:i') }}
                    </p>
                </div>
                
                <div>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-slate-100 dark:bg-slate-800">
                        Status : {{ strtoupper(str_replace('_', ' ', $ticket->status)) ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 sm:gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Created By</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->user->employee->employee_name ?? $ticket->user->email }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Priority</p>
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
                <p class="text-xs text-slate-500 uppercase">Executor</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->executor?->employee?->employee_name ?? '-' }}
                </p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl p-5 shadow">
                <p class="text-xs text-slate-500 uppercase">Finished</p>
                <p class="font-semibold mt-1">
                    {{ $ticket->finished ?? '-' }}
                </p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold mb-3">Description</h3>
            <div class="prose dark:prose-invert max-w-none text-sm">
                {!! nl2br(e($ticket->description)) !!}
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold mb-3">Notes from IT</h3>
            <div class="prose dark:prose-invert max-w-none text-sm">
                {!! nl2br(e($ticket->notes_executor ?? '-')) !!}
            </div>
        </div>

        @if (!empty($ticket->attachment_url))
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold mb-4">Attachments</h3>

            <a href="{{ $ticket->attachment_url }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm
                      bg-indigo-600 text-white hover:bg-indigo-700 transition w-full sm:w-auto justify-center">
                Open Attachment Folder
            </a>
        </div>
        @endif

        @if ($ticket->replies && $ticket->replies->count())
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold mb-4">Activity</h3>
                <div class="space-y-3">
                    @foreach ($ticket->replies as $reply)
                        <div class="p-3 sm:p-4 rounded-xl bg-slate-50 dark:bg-slate-800">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-2 mb-2">
                                <p class="text-xs sm:text-sm font-semibold">
                                    {{ $reply->user->employee->employee_name ?? $reply->user->email }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $reply->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300">
                                {{ $reply->message }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex justify-end pb-4">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 sm:px-5 py-2.5 rounded-xl text-sm
                  bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white
                  hover:bg-slate-300 dark:hover:bg-slate-600 transition w-full sm:w-auto justify-center">
                Back to Dashboard
            </a>
        </div>
    </div>
@endsection --}}