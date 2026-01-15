@extends('layouts.app')
@section('title', 'Resolved Tickets')
@section('header', 'Resolved Tickets')
@section('subtitle', 'Manage Resolved Tickets Ticketing System')
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
            /* text-transform: uppercase; */
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
            /* text-transform: uppercase; */
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

        /* untuk select 2 */
        /* ===== Select2 Container ===== */
        .select2-container .select2-selection--single {
            background-color: #5e6f88;
            /* dark-800 */
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            height: 40px;
            display: flex;
            align-items: center;
        }

        /* warna placeholder */
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #ffffff;
            /* slate-400 */
        }

        /* kalau mau beda saat focus */
        .select2-container--open .select2-selection__placeholder {
            color: #7f8b99;
            /* slate-300 */
        }


        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #ffffff;
            padding-left: 0.75rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 8px;
        }

        /* ===== Dropdown ===== */
        .select2-dropdown {
            background-color: #1f2937;
            /* dark */
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* option normal */
        .select2-results__option {
            color: #ffffff;
            /* text-slate-200 */
        }

        /* hover */
        .select2-results__option--highlighted {
            background-color: #374151 !important;
            /* dark-600 */
            color: #ffffff !important;
        }

        /* selected */
        .select2-results__option--selected {
            background-color: #2563eb !important;
            /* blue-600 */
            color: #ffffff !important;
        }

        /* search box */
        .select2-search__field {
            background-color: #111827;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
         <div class="space-y-4 md:space-y-6">
            {{-- Statistics Cards --}}
           <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl md:rounded-2xl p-4 md:p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">Resolve Tickets</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-bold mb-1" id="total-users"></p>
                <p class="text-blue-100 text-xs">Resolve Tickets</p>
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
                    <h3 class="text-xs md:text-sm font-semibold opacity-90">Ratings</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                {{-- <p class="text-2xl md:text-3xl font-bold mb-1">{{ number_format($avgRating ?? 0, 1) }} / 5</p> --}}
                <p class="text-2xl md:text-3xl font-bold mb-1">
                        {{
                            fmod(round($avgRating ?? 0,1),1) == 0
                            ? number_format($avgRating, 0)
                            : number_format($avgRating, 1)
                        }} / 5
                    </p>

                <p class="text-purple-100 text-xs">Total Handled : {{ $totalReviewedTickets }} Ticket's</p>
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
                <p class="text-2xl md:text-3xl font-bold mb-1">{{ $overdueticket ?? 0 }}</p>
                <p class="text-orange-100 text-xs">Overdue Ticket</p>
            </div>
        </div>
            {{-- Main Content Card --}}
            <div class="bg-slate-800 rounded-xl md:rounded-2xl shadow-lg border border-slate-700 overflow-hidden">
                {{-- Card Header --}}
                <div class="px-4 py-4 md:px-6 md:py-5 border-b border-slate-700">
                    <div class="flex flex-col gap-3 md:gap-4">
                        <div>
                            <h2 class="text-lg md:text-xl font-bold text-white">All your closed tickets</h2>
                            <p class="text-xs md:text-sm text-slate-400 mt-1">Manage and view all your closed ticket's</p>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
                            {{-- Search Input --}}
                            {{-- <div class="flex flex-col gap-1">
                                <label for="filterStatus" class="text-xs text-slate-300">Status</label>
                                <select id="filterStatus" class="select2">

                                    <option value="">All Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Progress">Progress</option>
                                    <option value="Closed">Closed</option>
                                    <option value="OVerdue">OVerdue</option>
                                </select>
                            </div> --}}
                            <div class="flex flex-col gap-1">
                                <label for="filterCategory" class="text-xs text-slate-300">Category</label>

                                <select id="filterCategory" class="select2">
                                    <option value="">All Category</option>
                                    <option value="Hardware & Software">Hardware & Software</option>
                                    <option value="Network">Network</option>
                                    <option value="Account & Access">Account & Access</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="filterPriority" class="text-xs text-slate-300">Dificulty</label>

                                <select id="filterPriority" class="select2">
                                    <option value="">All Dificulty</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="dateFrom" class="text-xs text-slate-300">Date From</label>

                                <input type="date" id="dateFrom" placeholder="YYYY-MM-DD"
                                   class="px-3 py-2 rounded w-40
               bg-slate-600 text-white
               placeholder-white
               border border-slate-500
               focus:outline-none focus:ring-2 focus:ring-slate-400">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="dateTo" class="text-xs text-slate-300">To</label>
                                <input placeholder="YYYY-MM-DD" type="date" id="dateTo"
                                    class="px-3 py-2 rounded w-40
               bg-slate-600 text-white
               placeholder-white
               border border-slate-500
               focus:outline-none focus:ring-2 focus:ring-slate-400">
                            </div>

                            <div class="flex flex-col gap-1">
                                <label for="filterStatus" class="text-xs text-slate-300">Filter</label>

                                <button id="btnFilter"
                                    class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg transition">
                                    Filter
                                </button>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="filterStatus" class="text-xs text-slate-300">Reset Filter</label>

                                <button id="btnReset"
                                    class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition">
                                    Reset
                                </button>
                            </div>
                            <div class="relative flex-1">
                                <input type="text" id="table-search" placeholder="Search tickes by title"
                                    class="w-full pl-10 pr-4 py-2 border border-slate-600 rounded-lg bg-slate-700 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-slate-400">
                                <svg class="absolute left-3 top-2.5 w-5 h-5 text-slate-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
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
                            <svg class="animate-spin h-10 w-10 text-blue-500 mx-auto mb-4" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <p class="text-slate-400 text-sm">Loading tickets...</p>
                        </div>
                    </div>

                    <div id="users-table-wrapper" class="overflow-x-auto -mx-4 md:mx-0" style="display: none;">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-700" id="users-table">
                                    <thead class="bg-slate-900">
                                        <tr>
                                            <th class="text-center">Queue</th>
                                            <th class="text-center">User</th>
                                        <th class="text-center">Title</th>
                                        <th class="text-center">Categories</th>
                                        <th class="text-center">Dificulty</th>
                                        <th class="text-center">Estimation</th>
                                        <th class="text-center">Finished</th>
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

                    <div id="mobile-cards-view" style="display: none;">
                        <div id="mobile-cards-container">
                        </div>
                        <div id="mobile-pagination"></div>
                        <div id="mobile-info" class="mobile-info-text"></div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
          
            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
            <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    flatpickr("#month", {
                        dateFormat: "Y-m",
                        plugins: [
                            new monthSelectPlugin({
                                shorthand: true,
                                dateFormat: "Y-m",
                                altFormat: "F Y"
                            })
                        ]
                    });
                });
                document.addEventListener('DOMContentLoaded', function() {
                    flatpickr("#from", {
                        dateFormat: "Y-m-d",
                        defaultDate: "{{ request('from') }}",
                        allowInput: true
                    });
                });
                document.addEventListener('DOMContentLoaded', function() {
                    flatpickr("#to", {
                        dateFormat: "Y-m-d",
                        defaultDate: "{{ request('to') }}",
                        allowInput: true
                    });
                });
                document.addEventListener('DOMContentLoaded', function() {
                    flatpickr("#dateFrom", {
                        dateFormat: "Y-m-d",
                        defaultDate: "{{ request('dateFrom') }}",
                        allowInput: true
                    });
                });
                document.addEventListener('DOMContentLoaded', function() {
                    flatpickr("#dateTo", {
                        dateFormat: "Y-m-d",
                        defaultDate: "{{ request('dateTo') }}",
                        allowInput: true
                    });
                });
            </script>




           

            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

            <script>
                $(document).ready(function() {
                    $('#filterCategory').select2({
                        placeholder: 'Choose Category...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#filterCategory').parent()
                    });
                    // $('#filterStatus').select2({
                    //     placeholder: 'Choose Status...',
                    //     allowClear: true,
                    //     width: '100%',
                    //     dropdownParent: $('#filterStatus').parent()
                    // });
                    $('#filterPriority').select2({
                        placeholder: 'Choose Dificulty...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#filterPriority').parent()
                    });
                    $('#quarter').select2({
                        placeholder: 'Choose Quarter...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#quarter').parent()
                    });
                    $('#category').select2({
                        placeholder: 'Choose Category...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#category').parent()
                    });
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
                        ajax: {
                            url: "{{ route('resolveticket.resolveticket') }}",
                            data: function(d) {
                                d.status = $('#filterStatus').val();
                                d.category = $('#filterCategory').val();
                                d.priority = $('#filterPriority').val();
                                d.date_from = $('#dateFrom').val();
                                d.date_to = $('#dateTo').val();
                            }
                        },
                        columnDefs: [{
                            targets: '_all',
                            className: 'dt-center'
                        }],
                        columns: [
                            {
                                    data: 'queue_number',
                                    name: 'queue_number',
                                    width: '5%'
                                },
                              {
                                data: 'employee_name',
                                name: 'employees_tables.employee_name',
                                width: '20%',
                                className: 'text-center',
                                orderable: false,
                                searchable: false
                            },    

                        {
                            data: 'title',
                            name: 'title',
                            width: '20%'
                        },
                        {
                            data: 'category',
                            name: 'category',
                            width: '10%'
                        },
                        // { data: 'executor', name: 'employees_tables.employee_name', width: '15%' },
                        
                        {
                            data: 'priority',
                            name: 'priority',
                            width: '5%',
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
                                data: 'status',
                                name: 'status',
                                width: '15%',
                                className: 'text-center',
                                render: function(data, type, row) {
                                    if (!data) return '-';

                                    let status = data.toLowerCase();
                                    let badgeClass = 'bg-slate-500 text-white';

                                    if (status === 'closed') {
                                        badgeClass = 'bg-green-600 text-white'; // SUCCESS
                                    } else if (status === 'open') {
                                        badgeClass = 'bg-blue-600 text-white';
                                    } else if (status === 'progress') {
                                        badgeClass = 'bg-yellow-500 text-black';
                                    } else if (status === 'overdue') {
                                        badgeClass = 'bg-red-500 text-black';
                                    }
                                    return `
            <span class="px-3 py-1 rounded-full text-xs font-semibold ${badgeClass}">
                ${data}
            </span>
        `;
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
                    $('#btnFilter').on('click', function() {
                        table.ajax.reload();
                    });

                    $('#btnReset').on('click', function() {
                        $('#filterStatus').val('');
                        $('#filterCategory').val('');
                        $('#filterPriority').val('');
                        $('#dateFrom').val('');
                        $('#dateTo').val('');
                        table.ajax.reload();
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
                         var initials = (ticket.title || 'U').substring(0, 2).toUpperCase();
                            var card = `
                            <div class="user-card">
                                <div class="user-card-header">
                                    <div class="user-card-avatar">${initials}</div>
                                    <div class="user-card-title">
                                        <div class="user-card-name">Title : ${ticket.title || 'N/A'}</div>
                                        <div class="user-card-username">Queue Number : ${ticket.queue_number}</div>
                                        <div class="user-card-username">Date : ${ticket.created_at}</div>
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
                                        <div class="user-card-label">Dificulty</div>
                                        <div class="user-card-value">${ticket.priority || 'N/A'}</div>
                                    </div>
                                     <div class="user-card-field">
                                        <div class="user-card-label">Estimation</div>
                                        <div class="user-card-value">${ticket.estimation || 'N/A'}</div>
                                    </div>
                                     <div class="user-card-field">
                                        <div class="user-card-label">Notes IT</div>
                                        <div class="user-card-value">${ticket.notes_executor || 'N/A'}</div>
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
                                        if (status === 'Open') cls = 'bg-blue-500';
                                        else if (status === 'Progress') cls = 'bg-yellow-500';
                                        else if (status === 'Overdue') cls = 'bg-red-500';
                                        else if (status === 'Closed') cls = 'bg-green-600';
                                        return `
                                                <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold text-white rounded-full ${cls}">
                                                <span class="w-2 h-2 rounded-full bg-white"></span>${status}</span>`;
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