{{-- @extends('layouts.app')
@section('title', 'My Tickets')
@section('header', 'My Tickets')
@section('subtitle', 'Manage All Your Tickets in the System')
@section('content')
    <div class="space-y-4 md:space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">Total Tickets</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1" id="total-users">{{ $allticket }}</p>
                <p class="text-blue-100 text-xs">My Tickets</p>
            </div>
            <div
                class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">Tickets Today</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1">{{ $todaysticket }}</p>
                <p class="text-emerald-100 text-xs">Tickets</p>
            </div>
            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">On Progress Tickets</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1">{{ $onprogressticket }}</p>
                <p class="text-purple-100 text-xs">On Progress Tickets</p>
            </div>
            <div
                class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">Overdue</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1">{{ $overdueticket }}</p>
                <p class="text-orange-100 text-xs">Overdue's Tickets</p>
            </div>
        </div>

      
        <div class="bg-slate-800 rounded-xl md:rounded-2xl shadow-lg border border-slate-700 overflow-hidden">
            <div class="px-4 py-4 md:px-6 md:py-5 border-b border-slate-700">
                <div class="flex flex-col gap-3 md:gap-4">
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-white">All Tickets</h2>
                        <p class="text-xs md:text-sm text-slate-400 mt-1">Manage and view all tickets users</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
                        <div class="relative flex-1">
                            <input type="text" id="table-search" placeholder="Search..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-600 rounded-lg bg-slate-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-400">
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
                        <p class="text-slate-400 text-sm">Loading users...</p>
                    </div>
                </div>

                <div class="overflow-x-auto -mx-4 md:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-slate-700" id="users-table">
                                <thead class="bg-slate-900">
                                    <tr>
                                        <th class="text-center">Queue</th>
                                        <th class="text-center">Title</th>
                                        <th class="text-center">Categories</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-slate-800 divide-y divide-slate-700">
                                </tbody>
                            </table>
                        </div>
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
            background: linear-gradient(to right, #000000, #000000);
            color: rgb(255, 255, 255);
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
            background: #0F172A;
        }

        #users-table tbody tr {
            border-bottom: 1px solid #ffffff;
            transition: background-color 0.2s;
        }

        .dark #users-table tbody tr {
            border-bottom-color: #ffffff;
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
    </style>
@endsection

@push('scripts')
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
                ajax: "{{ route('allmytickets.allmytickets') }}",
                columnDefs: [{
                    targets: '_all',
                    className: 'dt-center'
                }],
                columns: [{
                        data: 'queue_number',
                        name: 'queue_number',
                        width: '10%'
                    },

                    {
                        data: 'title',
                        name: 'title',
                        width: '35%'
                    },
                    {
                        data: 'category',
                        name: 'category',
                        width: '15%'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        width: '15%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '20%',
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

            adjustPaginationForMobile();
            $(window).on('resize', adjustPaginationForMobile);

            function adjustPaginationForMobile() {
                if ($(window).width() < 768) {
                    $('.dataTables_paginate').addClass('mobile-pagination');
                } else {
                    $('.dataTables_paginate').removeClass('mobile-pagination');
                }
            }
        });
    </script>
@endpush --}}
@extends('layouts.app')
@section('title', 'My Tickets')
@section('header', 'My Tickets')
@section('subtitle', 'Manage All My Tickets in the System')
@section('content')

    <style>
        /* Base DataTables Styling */
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

        /* Table Styling - Desktop Only */
        #users-table {
            width: 100% !important;
        }

        #users-table thead {
            background: linear-gradient(to right, #000000, #000000);
            color: rgb(255, 255, 255);
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
            background: #0F172A;
        }

        #users-table tbody tr {
            border-bottom: 1px solid #ffffff;
            transition: background-color 0.2s;
        }

        .dark #users-table tbody tr {
            border-bottom-color: #ffffff;
        }

        #users-table tbody tr:hover {
            background-color: #000000;
        }

        .dark #users-table tbody tr:hover {
            background-color: #1e293b;
        }

        #users-table tbody td {
            padding: 1rem;
            color: #ffffff;
            font-size: 0.875rem;
            vertical-align: middle;
        }

        .dark #users-table tbody td {
            color: #cbd5e1;
        }

        /* Pagination Styling */
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
            min-width: 2rem;
            text-align: center;
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

        /* Dark mode pagination */
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

        /* Action Buttons */
        .btn-action {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
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

        .text-center {
            text-align: center;
        }

        /* ========== MOBILE CARD VIEW STYLES ========== */
        @media (max-width: 767px) {

            /* Hide desktop table view */
            #users-table-wrapper {
                display: none !important;
            }

            /* Show mobile card view */
            #mobile-cards-view {
                display: block !important;
            }

            /* Hide DataTables controls on mobile */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_info {
                display: none !important;
            }

            /* Adjust pagination for mobile */
            .dataTables_wrapper .dataTables_paginate {
                justify-content: center;
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.4rem 0.6rem;
                font-size: 0.813rem;
            }
        }

        /* Desktop - Hide mobile cards */
        @media (min-width: 768px) {
            #mobile-cards-view {
                display: none !important;
            }

            #users-table-wrapper {
                display: block !important;
            }
        }

        /* Mobile Card Styles */
        .user-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid #334155;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
        }

        .user-card:active {
            transform: scale(0.98);
        }

        .user-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #334155;
        }

        .user-card-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .user-card-title {
            flex: 1;
            margin-left: 0.75rem;
        }

        .user-card-name {
            color: #f1f5f9;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.125rem;
        }

        .user-card-username {
            color: #94a3b8;
            font-size: 0.813rem;
        }

        .user-card-body {
            display: grid;
            gap: 0.75rem;
        }

        .user-card-field {
            display: flex;
            align-items: flex-start;
        }

        .user-card-label {
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            min-width: 90px;
            flex-shrink: 0;
        }

        .user-card-value {
            color: #e2e8f0;
            font-size: 0.875rem;
            flex: 1;
        }

        .user-card-badge {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            color: white;
        }

        .user-card-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid #334155;
        }

        .user-card-actions .btn-action {
            flex: 1;
            justify-content: center;
            padding: 0.625rem;
            font-size: 0.875rem;
        }

        /* Mobile pagination */
        #mobile-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1.5rem;
            gap: 0.5rem;
        }

        .mobile-page-btn {
            padding: 0.5rem 0.75rem;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            background-color: #1e293b;
            color: #cbd5e1;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .mobile-page-btn:active {
            transform: scale(0.95);
        }

        .mobile-page-btn.active {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            color: white;
            border-color: transparent;
        }

        .mobile-page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .mobile-info-text {
            color: #94a3b8;
            font-size: 0.813rem;
            text-align: center;
            margin-top: 0.75rem;
        }
    </style>

    <div class="space-y-4 md:space-y-6">
        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">Total My Tickets</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1" id="total-users"></p>
                <p class="text-blue-100 text-xs">All My Tickets</p>
            </div>
            <div
                class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">Tickets Today</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1">{{ $todaysticket ?? 0 }}</p>
                <p class="text-emerald-100 text-xs">Tickets</p>
            </div>
            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">On Progress Tickets</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1">{{ $onprogressticket ?? 0 }}</p>
                <p class="text-purple-100 text-xs">On Progress Tickets</p>
            </div>
            <div
                class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">Overdue's Ticket</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1">{{$overdueticket ?? 0}}</p>
                <p class="text-orange-100 text-xs">Overdue Ticket</p>
            </div>
        </div>

        {{-- Main Content Card --}}
        <div class="bg-slate-800 rounded-xl md:rounded-2xl shadow-lg border border-slate-700 overflow-hidden">
            {{-- Card Header --}}
            <div class="px-4 py-4 md:px-6 md:py-5 border-b border-slate-700">
                <div class="flex flex-col gap-3 md:gap-4">
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-white">All My Tickets</h2>
                        <p class="text-xs md:text-sm text-slate-400 mt-1">Manage and view all my tickets</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
                        {{-- Search Input --}}
                        <div class="relative flex-1">
                            <input type="text" id="table-search" placeholder="Search tickes..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-600 rounded-lg bg-slate-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-400">
                            <svg class="absolute left-3 top-2.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="p-4 md:p-6">
                {{-- Loading State --}}
                <div id="loading-state" class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <svg class="animate-spin h-10 w-10 text-blue-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <p class="text-slate-400 text-sm">Loading tickets...</p>
                    </div>
                </div>

                {{-- Desktop Table View --}}
                <div id="users-table-wrapper" class="overflow-x-auto -mx-4 md:mx-0" style="display: none;">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-slate-700" id="users-table">
                                <thead class="bg-slate-900">
                                    <tr>
                                        <th class="text-center">Queue</th>
                                        <th class="text-center">Title</th>
                                        <th class="text-center">Categories</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Executor</th>
                                        <th class="text-center">Priority</th>
                                        <th class="text-center">Estimation</th>
                                        <th class="text-center">Finished</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-slate-800 divide-y divide-slate-700">
                                    <!-- DataTable will populate this -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Mobile Cards View --}}
                <div id="mobile-cards-view" style="display: none;">
                    <div id="mobile-cards-container">
                        <!-- Cards will be populated here -->
                    </div>
                    <div id="mobile-pagination"></div>
                    <div id="mobile-info" class="mobile-info-text"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
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
                    ajax: "{{ route('allmytickets.allmytickets') }}",
                    columnDefs: [{
                        targets: '_all',
                        className: 'dt-center'
                    }],
                    columns: [{
                            data: 'queue_number',
                            name: 'queue_number',
                            width: '10%'
                        },
                       
                        {
                            data: 'title',
                            name: 'title',
                            width: '25%'
                        },
                        {
                            data: 'category',
                            name: 'category',
                            width: '15%'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            width: '15%'
                        },
                        // { data: 'executor', name: 'employees_tables.employee_name', width: '15%' },
                        {
                            data: 'executor_employee_name',
                            name: 'employees_tables.employee_name',
                            width: '15%',
                             orderable: false,
                            searchable: false
                        },
                        {
                            data: 'priority',
                            name: 'priority',
                            width: '15%',
                            render: function(data) {
                                return data ? data : 'empty';
                            }
                        },

                        {
                            data: 'estimation',
                            name: 'estimation',
                            width: '15%',
                            render: function(data) {
                                return data ? data : 'empty';
                            }
                        },

                        {
                            data: 'finished',
                            name: 'finished',
                            width: '15%',
                            render: function(data) {
                                return data ? data : 'empty';
                            }
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
                    dom: '<"flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4"<"length-wrapper"l><"info-wrapper"i>>rtip',
                    initComplete: function() {
                        $('#loading-state').hide();
                        $('#users-table-wrapper').fadeIn();

                        var info = this.api().page.info();
                        $('#total-users').text(info.recordsTotal);

                        // Initialize mobile view
                        renderMobileCards();
                    },
                    drawCallback: function() {
                        var info = this.api().page.info();
                        $('#total-users').text(info.recordsTotal);

                        // Update mobile view
                        if ($(window).width() < 768) {
                            renderMobileCards();
                        }
                    }
                });

                // Custom search functionality
                $('#table-search').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // Function to render mobile cards
                function renderMobileCards() {
                    if ($(window).width() >= 768) return;

                    var data = table.rows({
                        page: 'current'
                    }).data();
                    var container = $('#mobile-cards-container');
                    container.empty();

                    if (data.length === 0) {
                        container.html('<div class="text-center py-8 text-slate-400">No tickets found</div>');
                        return;
                    }

                    data.each(function(ticket) {
                        var initials = ticket.title ? ticket.title
                            .substring(0, 2).toUpperCase() : 'U';

                        var card = `
                            <div class="user-card">
                                <div class="user-card-header">
                                    <div class="user-card-avatar">${initials}</div>
                                    <div class="user-card-title">
                                        <div class="user-card-name">${ticket.title || 'N/A'}</div>
                                        <div class="user-card-username">Queue : ${ticket.queue_number}</div>
                                    </div>
                                </div>
                                <div class="user-card-body">
                                   
                                    <div class="user-card-field">
                                        <div class="user-card-label">Categories</div>
                                        <div class="user-card-value">${ticket.category || 'N/A'}</div>
                                    </div>
                                    <div class="user-card-field">
                                        <div class="user-card-label">Description</div>
                                        <div class="user-card-value">${ticket.description || 'N/A'}</div>
                                    </div>
                                    <div class="user-card-field">
                                        <div class="user-card-label">Executor</div>
                                        <div class="user-card-value">${ticket.executor_employee_name || 'N/A'}</div>
                                    </div>
                                    <div class="user-card-field">
                                        <div class="user-card-label">Priority</div>
                                        <div class="user-card-value">${ticket.priority || 'N/A'}</div>
                                    </div>
                                    <div class="user-card-field">
                                        <div class="user-card-label">Estimation</div>
                                        <div class="user-card-value">${ticket.estimation || 'N/A'}</div>
                                    </div>
                                    <div class="user-card-field">
                                        <div class="user-card-label">Finished</div>
                                        <div class="user-card-value">${ticket.finished || 'N/A'}</div>
                                    </div>
                                    <div class="user-card-field">
    <div class="user-card-label">Status</div>
    <div class="user-card-value">
        ${(() => {
            const status = ticket.status || 'N/A';

            let cls = 'bg-slate-500';
            if (status === 'Open') cls = 'bg-green-500';
            else if (status === 'Progress') cls = 'bg-yellow-500 text-black';
            else if (status === 'Overdue') cls = 'bg-red-500 text-black';
            else if (status === 'Closed') cls = 'bg-slate-600';

            return `
                <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold text-white rounded-full ${cls}">
                    <span class="w-2 h-2 rounded-full bg-white"></span>
                    ${status}
                </span>
            `;
        })()}
    </div>
</div>

                                   
                                <div class="user-card-actions">
                                Action${ticket.action}
                                </div>
                            </div>
                        `;
                        container.append(card);
                    });

                    // Update mobile pagination
                    renderMobilePagination();
                }

                // Function to render mobile pagination
                function renderMobilePagination() {
                    var info = table.page.info();
                    var pagination = $('#mobile-pagination');
                    pagination.empty();

                    // Previous button
                    var prevBtn = $('<button class="mobile-page-btn">Prev</button>');
                    if (info.page === 0) prevBtn.prop('disabled', true);
                    prevBtn.on('click', function() {
                        table.page('previous').draw('page');
                    });
                    pagination.append(prevBtn);

                    // Page info
                    var pageInfo = $(`<span class="mobile-page-btn active">${info.page + 1} / ${info.pages}</span>`);
                    pagination.append(pageInfo);

                    // Next button
                    var nextBtn = $('<button class="mobile-page-btn">Next</button>');
                    if (info.page >= info.pages - 1) nextBtn.prop('disabled', true);
                    nextBtn.on('click', function() {
                        table.page('next').draw('page');
                    });
                    pagination.append(nextBtn);

                    // Info text
                    $('#mobile-info').text(`Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal} entries`);
                }

                // Handle window resize
                $(window).on('resize', function() {
                    if ($(window).width() < 768) {
                        renderMobileCards();
                    }
                });
            });
        </script>
    @endpush
@endsection

      {{-- Users Table Card --}}
        {{-- <div
            class="bg-white dark:bg-slate-800 rounded-xl md:rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-4 py-4 md:px-6 md:py-5 border-b border-slate-200 dark:border-slate-700">
                <div class="flex flex-col gap-3 md:gap-4">
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">Your Tickets</h2>
                        <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">Manage and view all {{$user->employee->employee_name}} tickets
                            </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
                        <div class="relative flex-1">
                            <input type="text" id="table-search" placeholder="Search..."
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
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700" id="users-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Queue</th>
                                        <th class="text-center hidden md:table-cell">Title</th>
                                        <th class="text-center hidden md:table-cell">Categories</th>
                                        <th class="text-center hidden md:table-cell">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
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
