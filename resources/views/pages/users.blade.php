@extends('layouts.app')
@section('title', 'Users Management')
@section('header', 'Users')
@section('subtitle', 'Manage system users and permissions')
@section('content')
  {{-- <style>
        .dataTables_wrapper {
            font-family: inherit;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            color: #ffffff;
            font-size: 0.875rem;
        }

        .dark .dataTables_wrapper .dataTables_length,
        .dark .dataTables_wrapper .dataTables_filter,
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_paginate {
            color: #ffffff;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.5rem;
            font-size: 0.875rem;
            margin: 0 0.5rem;
        }

        .dark .dataTables_wrapper .dataTables_length select,
        .dark .dataTables_wrapper .dataTables_filter input {
            border-color: #475569;
            background-color: #334155;
            color: #f1f5f9;
        }

        #users-table {
            width: 100% !important;
        }

        #users-table thead {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            color: rgb(0, 0, 0);
        }

        #users-table thead th {
            padding: 0.75rem 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            border: none;
            white-space: nowrap;
        }

        @media (min-width: 768px) {
            #users-table thead th {
                padding: 1rem;
                font-size: 0.75rem;
            }
        }

        .dark #users-table thead {
            background: linear-gradient(to right, #09090e, #0891b2);
        }

        #users-table tbody tr {
            border-bottom: 1px solid #000000;
            transition: background-color 0.2s;
        }

        .dark #users-table tbody tr {
            border-bottom-color: #334155;
        }

        #users-table tbody tr:hover {
            background-color: #000000;
        }

        .dark #users-table tbody tr:hover {
            background-color: #1e293b;
        }

        #users-table tbody td {
            padding: 0.75rem 0.5rem;
            color: #ffffff;
            font-size: 0.813rem;
            vertical-align: middle;
        }

        @media (min-width: 768px) {
            #users-table tbody td {
                padding: 1rem;
                font-size: 0.875rem;
            }
        }

        .dark #users-table tbody td {
            color: #cbd5e1;
        }

        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1rem;
            gap: 0.25rem;
            flex-wrap: wrap;
            padding: 0 0.5rem;
        }

        @media (min-width: 768px) {
            .dataTables_wrapper .dataTables_paginate {
                justify-content: flex-end;
                gap: 0.5rem;
                padding: 0;
            }
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            display: inline-block;
            padding: 0.4rem 0.6rem;
            margin: 0 0.125rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background-color: white;
            color: #475569;
            font-size: 0.813rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 2rem;
            text-align: center;
        }

        @media (min-width: 768px) {
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next {
            font-weight: 600;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #334155;
            border-color: #475569;
            color: #f1f5f9;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            color: white;
        }

        .dataTables_wrapper .dataTables_info {
            padding: 0.5rem 0;
            font-size: 0.75rem;
        }

        @media (min-width: 768px) {
            .dataTables_wrapper .dataTables_info {
                font-size: 0.875rem;
            }
        }

        .dataTables_wrapper .dataTables_length {
            font-size: 0.75rem;
        }

        @media (min-width: 768px) {
            .dataTables_wrapper .dataTables_length {
                font-size: 0.875rem;
            }
        }

        .btn-action {
            padding: 0.4rem 0.6rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            white-space: nowrap;
        }

        @media (min-width: 768px) {
            .btn-action {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
                gap: 0.5rem;
            }
        }

        .btn-action svg {
            width: 1rem;
            height: 1rem;
        }

        .btn-edit {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            color: white;
            border: none;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-delete {
            background: linear-gradient(to right, #ef4444, #dc2626);
            color: white;
            border: none;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        @media (max-width: 767px) {
            .btn-action .btn-text {
                display: none;
            }

            .btn-action {
                padding: 0.5rem;
                min-width: 2.25rem;
                justify-content: center;
            }
        }

        .dataTables_wrapper .dataTables_scroll {
            overflow-x: auto;
        }

        @media (max-width: 767px) {
            .dataTables_wrapper {
                overflow-x: auto;
            }

            #users-table {
                min-width: 500px;
            }
        }

        .text-center {
            text-align: center;
        }
        @media (max-width: 767px) {
    #users-table {
        min-width: 720px !important; 
    }

    .dataTables_wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .dataTables_scrollBody {
        overflow-x: auto !important;
    }
}

    </style> --}}
      <style>
        .dataTables_wrapper {
            font-family: inherit;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            color: #ffffff;
            font-size: 0.875rem;
        }

        .dark .dataTables_wrapper .dataTables_length,
        .dark .dataTables_wrapper .dataTables_filter,
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_paginate {
            color: #ffffff;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.5rem;
            font-size: 0.875rem;
            margin: 0 0.5rem;
        }

        .dark .dataTables_wrapper .dataTables_length select,
        .dark .dataTables_wrapper .dataTables_filter input {
            border-color: #475569;
            background-color: #334155;
            color: #f1f5f9;
        }

        /* Desktop Table Styling */
        @media (min-width: 768px) {
            #users-table {
                width: 100% !important;
                display: table;
            }

            #users-table thead {
                background: linear-gradient(to right, #3b82f6, #06b6d4);
                color: rgb(0, 0, 0);
                display: table-header-group;
            }

            #users-table thead th {
                padding: 1rem;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
                border: none;
                white-space: nowrap;
            }

            .dark #users-table thead {
                background: linear-gradient(to right, #09090e, #0891b2);
            }

            #users-table tbody {
                display: table-row-group;
            }

            #users-table tbody tr {
                display: table-row;
                border-bottom: 1px solid #000000;
                transition: background-color 0.2s;
            }

            .dark #users-table tbody tr {
                border-bottom-color: #334155;
            }

            #users-table tbody tr:hover {
                background-color: #000000;
            }

            .dark #users-table tbody tr:hover {
                background-color: #1e293b;
            }

            #users-table tbody td {
                display: table-cell;
                padding: 1rem;
                color: #ffffff;
                font-size: 0.875rem;
                vertical-align: middle;
            }

            .dark #users-table tbody td {
                color: #cbd5e1;
            }
        }

        @media (max-width: 767px) {
            /* Hide default table structure */
            #users-table thead {
                display: none;
            }

            #users-table tbody {
                display: block;
            }

            #users-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                background: rgb(18, 23, 42);
                border-radius: 0.75rem;
                padding: 1rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                border: 1px solid #e2e8f0;
            }

            .dark #users-table tbody tr {
                background: #1e293b;
                border-color: #334155;
            }

            #users-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                border-bottom: 1px solid #f1f5f9;
                color: #ffffff;
            }

            .dark #users-table tbody td {
                border-bottom-color: #334155;
                color: #cbd5e1;
            }

            #users-table tbody td:last-child {
                border-bottom: none;
                padding-top: 0.75rem;
                justify-content: center;
            }

            #users-table tbody td:before {
                content: attr(data-label);
                font-weight: 600;
                font-size: 0.75rem;
                text-transform: uppercase;
                color: #64748b;
                letter-spacing: 0.05em;
            }

            .dark #users-table tbody td:before {
                color: #94a3b8;
            }

            #users-table tbody td:last-child:before {
                content: none;
            }

            #users-table tbody td > * {
                text-align: right;
            }
        }

        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1rem;
            gap: 0.25rem;
            flex-wrap: wrap;
            padding: 0 0.5rem;
        }

        @media (min-width: 768px) {
            .dataTables_wrapper .dataTables_paginate {
                justify-content: flex-end;
                gap: 0.5rem;
                padding: 0;
            }
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            display: inline-block;
            padding: 0.4rem 0.6rem;
            margin: 0 0.125rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background-color: white;
            color: #475569;
            font-size: 0.813rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 2rem;
            text-align: center;
        }

        @media (min-width: 768px) {
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next {
            font-weight: 600;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #334155;
            border-color: #475569;
            color: #f1f5f9;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            color: white;
        }

        .dataTables_wrapper .dataTables_info {
            padding: 0.5rem 0;
            font-size: 0.75rem;
        }

        @media (min-width: 768px) {
            .dataTables_wrapper .dataTables_info {
                font-size: 0.875rem;
            }
        }

        .dataTables_wrapper .dataTables_length {
            font-size: 0.75rem;
        }

        @media (min-width: 768px) {
            .dataTables_wrapper .dataTables_length {
                font-size: 0.875rem;
            }
        }

        .btn-action {
            padding: 0.4rem 0.6rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            white-space: nowrap;
        }

        @media (min-width: 768px) {
            .btn-action {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
                gap: 0.5rem;
            }
        }

        .btn-action svg {
            width: 1rem;
            height: 1rem;
        }

        .btn-edit {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            color: white;
            border: none;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-delete {
            background: linear-gradient(to right, #ef4444, #dc2626);
            color: white;
            border: none;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        @media (max-width: 767px) {
            .action-buttons {
                display: flex;
                gap: 0.5rem;
                width: 100%;
                justify-content: center;
            }

            .btn-action {
                flex: 1;
            }
        }

        .text-center {
            text-align: center;
        }

    </style>
    <div class="space-y-4 md:space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">Total Users</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1" id="total-users"></p>
                <p class="text-blue-100 text-xs">Active accounts</p>
            </div>
            <div
                class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">Active Today</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1">24</p>
                <p class="text-emerald-100 text-xs">Users online</p>
            </div>
            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">New This Week</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1">8</p>
                <p class="text-purple-100 text-xs">New registrations</p>
            </div>
            <div
                class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">Administrators</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1">5</p>
                <p class="text-orange-100 text-xs">Admin accounts</p>
            </div>
        </div>

        <div
            class="bg-white dark:bg-slate-800 rounded-xl md:rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-4 py-4 md:px-6 md:py-5 border-b border-slate-200 dark:border-slate-700">
                <div class="flex flex-col gap-3 md:gap-4">
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">All Users</h2>
                        <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">Manage and view all system
                            users</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
                        <div class="relative flex-1">
                            <input type="text" id="table-search" placeholder="Search users..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <svg class="absolute left-3 top-2.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div id="loading-state" class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <svg class="animate-spin h-10 w-10 text-blue-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Loading users...</p>
                    </div>
                </div>

                <div class="overflow-x-auto -mx-4 md:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        {{-- <div class="overflow-hidden"> --}}
                        <div class="overflow-x-auto">
                            
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700" id="users-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Username</th>
                                        <th class="text-center hidden md:table-cell">Employee Name</th>
                                        <th class="text-center hidden md:table-cell">Comapany</th>
                                        <th class="text-center hidden md:table-cell">Department</th>
                                        <th class="text-center hidden md:table-cell">Location</th>
                                        <th class="text-center hidden md:table-cell">Position</th>
                                        <th class="text-center hidden md:table-cell">Roles</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   


@push('scripts')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
    <script>
        $(function() {
            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                ajax: "{{ route('users.users') }}",
                columns: [{
                        data: 'username',
                        name: 'username',
                        width: '10%'
                    },
                    
                       { data: 'employee_name', name: 'employees_tables.employee_name', width: '25%', className: 'hidden md:table-cell' },
                    {
                        data: 'company_name',
                        name: 'company_tables.name',
                        width: '25%',
                        className: 'hidden md:table-cell'
                    },
                    {
                        data: 'department_name',
                        name: 'departments_tables.department_name',
                        width: '25%',
                        className: 'hidden md:table-cell'
                    },
                    {
                        data: 'store_name',
                        name: 'stores_tables.name',
                        width: '25%',
                        className: 'hidden md:table-cell'
                    },
                    {
                        data: 'position_name',
                        name: 'position_tables.name',
                        width: '25%',
                        className: 'hidden md:table-cell'
                    },
                    {
                        data: 'roles',
                        name: 'roles',
                        width: '25%',
                        className: 'hidden md:table-cell'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '30%',
                        className: 'text-center'
                    },
                ],
                language: {
                    lengthMenu: "_MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Prev"
                    }
                },
                pageLength: 10,
               lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                dom: '<"flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4"<"length-wrapper"l><"info-wrapper"i>>rtip',
                initComplete: function() {
                    // Hide loading, show table
                    $('#loading-state').hide();
                    $('#users-table').fadeIn();
                    // Update total users count
                    var info = this.api().page.info();
                    $('#total-users').text(info.recordsTotal);
                },
                drawCallback: function() {
                    // Update total users count on redraw
                    var info = this.api().page.info();
                    $('#total-users').text(info.recordsTotal);
                }
            });
            // Custom search functionality
            $('#table-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Adjust pagination on mobile
            adjustPaginationForMobile();
            $(window).on('resize', adjustPaginationForMobile);

            function adjustPaginationForMobile() {
                if ($(window).width() < 768) {
                    // Show fewer page numbers on mobile
                    $('.dataTables_paginate').addClass('mobile-pagination');
                } else {
                    $('.dataTables_paginate').removeClass('mobile-pagination');
                }
            }
        });
    </script>
@endpush
@endsection

{{-- @extends('layouts.app')
@section('title', 'Users')
@section('header', 'Users')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100" id="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Employee ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(function () {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('users.users') }}",
        columns: [
            { data: 'id_hashed', name: 'id_hashed' },
            { data: 'username', name: 'username' },
            { data: 'employee_id', name: 'employee_id' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
});
</script>
@endpush
 --}}
{{-- @extends('layouts.app')
@section('title', 'Users Management')
@section('header', 'Users')
@section('subtitle', 'Manage system users and permissions')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Total Users</h3>
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1" id="total-users">0</p>
            <p class="text-blue-100 text-xs">Active accounts</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Active Today</h3>
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1">24</p>
            <p class="text-emerald-100 text-xs">Users online</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">New This Week</h3>
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1">8</p>
            <p class="text-purple-100 text-xs">New registrations</p>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold opacity-90">Administrators</h3>
                <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1">5</p>
            <p class="text-orange-100 text-xs">Admin accounts</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">All Users</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage and view all system users</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <input type="text" 
                               id="table-search"
                               placeholder="Search users..." 
                               class="pl-10 pr-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-lg font-semibold shadow-md shadow-blue-500/30 hover:shadow-lg hover:shadow-blue-500/40 transition-all text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add User
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div id="loading-state" class="flex items-center justify-center py-12">
                <div class="text-center">
                    <svg class="animate-spin h-10 w-10 text-blue-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Loading users...</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-full" id="users-table" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Employee ID</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dataTables_wrapper {
    font-family: inherit;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
 {
    color: #64748b;
    font-size: 0.875rem;
}

.dark .dataTables_wrapper .dataTables_length,
.dark .dataTables_wrapper .dataTables_filter,
.dark .dataTables_wrapper .dataTables_info,
{
    color: #94a3b8;
}

.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 0.5rem;
    font-size: 0.875rem;
    margin: 0 0.5rem;
}

.dark .dataTables_wrapper .dataTables_length select,
.dark .dataTables_wrapper .dataTables_filter input {
    border-color: #475569;
    background-color: #334155;
    color: #f1f5f9;
}

#users-table {
    width: 100% !important;
}

#users-table thead {
    background: linear-gradient(to right, #3b82f6, #06b6d4);
    color: white;
}

#users-table thead th {
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    border: none;
}

.dark #users-table thead {
    background: linear-gradient(to right, #2563eb, #0891b2);
}

#users-table tbody tr {
    border-bottom: 1px solid #e2e8f0;
    transition: background-color 0.2s;
}

.dark #users-table tbody tr {
    border-bottom-color: #334155;
}

#users-table tbody tr:hover {
    background-color: #f8fafc;
}

.dark #users-table tbody tr:hover {
    background-color: #1e293b;
}

#users-table tbody td {
    padding: 1rem;
    color: #334155;
    font-size: 0.875rem;
}

.dark #users-table tbody td {
    color: #cbd5e1;
}

.dataTables_wrapper 
{
    padding: 0.5rem 0.75rem;
    margin: 0 0.25rem;
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
    background: white;
    color: #64748b;
    transition: all 0.2s;
}

.dark .dataTables_wrapper 
{
    border-color: #475569;
    background: #334155;
    color: #94a3b8;
}

.dataTables_wrapper 
{
    background: linear-gradient(to right, #3b82f6, #06b6d4);
    color: white;
    border-color: transparent;
}

.dataTables_wrapper 
{
    background: linear-gradient(to right, #3b82f6, #06b6d4);
    color: white;
    border-color: transparent;
}

.btn-action {
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-edit {
    background: linear-gradient(to right, #3b82f6, #06b6d4);
    color: white;
    border: none;
}

.btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

.btn-delete {
    background: linear-gradient(to right, #ef4444, #dc2626);
    color: white;
    border: none;
}

.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}
.dataTables_wrapper .dataTables_paginate {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-top: 1rem;
    gap: 0.5rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    display: inline-block;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    background-color: white;
    color: #475569;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #1e293b;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(to right, #3b82f6, #06b6d4);
    border-color: transparent;
    color: white;
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.previous,
.dataTables_wrapper .dataTables_paginate .paginate_button.next {
    font-weight: 600;
}

.dark .dataTables_wrapper .dataTables_paginate .paginate_button {
    background-color: #1e293b;
    border-color: #334155;
    color: #cbd5e1;
}

.dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #334155;
    border-color: #475569;
    color: #f1f5f9;
}

.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(to right, #3b82f6, #06b6d4);
    color: white;
}

@media (max-width: 640px) {
    .dataTables_wrapper .dataTables_paginate {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>

@endsection

@push('scripts')
<script>
$(function () {
    var table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('users.users') }}",
        columns: [
            { data: 'id_hashed', name: 'id_hashed' },
            { data: 'username', name: 'username' },
            { data: 'employee_id', name: 'employee_id' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        initComplete: function() {
            $('#loading-state').hide();
            $('#users-table').fadeIn();
            
            var info = this.api().page.info();
            $('#total-users').text(info.recordsTotal);
        },
        drawCallback: function() {
            var info = this.api().page.info();
            $('#total-users').text(info.recordsTotal);
        }
    });

    $('#table-search').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endpush --}}