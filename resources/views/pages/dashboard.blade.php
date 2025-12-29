@extends('layouts.app')
@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subtitle', 'Manage Dashboard Ticketing System')
@section('content')
    <div class="p-4 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">Open</p>
                        <p class="text-2xl font-bold text-white">{{ $todaysticket }}</p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Today's Unchecked tickets</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">In Progress</p>
                        <p class="text-2xl font-bold text-white">{{ $onprogressticket }}</p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Currently being worked on by the team</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">Closed</p>
                        <p class="text-2xl font-bold text-white">{{ $closedticket }}</p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Ticket resolved</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">Overdue</p>
                        <p class="text-2xl font-bold text-rose-400">{{ $overdueticket }}</p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Tiket melewati SLA</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4"> 
            <div class="lg:col-span-2 space-y-4"> 
                <h1 class="mt-3 text-xl text-slate-100">Recent Tickets</h1>
                
                <!-- Mobile Search and Filter -->
                <div class="md:hidden space-y-3">
                    <div class="flex gap-2">
                        <div class="flex-1 relative">
                            <input type="text" id="mobile-search" placeholder="Search tickets..." 
                                class="w-full px-4 py-2 pl-10 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-slate-300 text-sm whitespace-nowrap">Show:</label>
                       <select id="mobile-length"
    class="px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-white
           focus:outline-none focus:ring-2 focus:ring-cyan-500">
    <option value="10">10</option>
    <option value="25">25</option>
    <option value="50">50</option>
    <option value="100">100</option>
    <option value="-1">All</option>
</select>

                        <span class="text-slate-400 text-sm ml-auto" id="mobile-info">Loading...</span>
                    </div>
                </div>
                
                <!-- Desktop Table View -->
                <div class="hidden md:block relative -mx-4 md:mx-0">
                    <div class="overflow-x-auto overflow-y-hidden w-full">
                        <table id="users-table" class="w-full text-sm border border-white/10 rounded-lg">
                            <thead class="text-slate-300/80 text-center">
                                <tr>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">Queue</th>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">User</th>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">Title</th>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">Categories</th>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">Status</th>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3" id="mobile-cards-container">
                    <!-- Cards will be inserted here by JavaScript -->
                </div>

                <!-- Mobile Pagination -->
                <div class="md:hidden" id="mobile-pagination">
                    <!-- Pagination will be inserted here by JavaScript -->
                </div>

                <div class="bg-white/3 p-4 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-white mb-3">Activity</h3>
                    <ul class="space-y-3 text-sm text-slate-300">
                        @foreach ($activities ?? [] as $act)
                            <li class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full mt-2 bg-slate-400"></div>
                                <div>
                                    <div class="text-slate-100">{{ $act['title'] }}</div>
                                    <div class="text-xs text-slate-400">{{ $act['time'] }}</div>
                                </div>
                            </li>
                        @endforeach
                        @if (empty($activities))
                            <li class="text-slate-400">No activity</li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="space-y-4">
                <div class="bg-white/3 p-4 rounded-lg shadow-sm">
                    <h4 class="font-semibold text-white">SLA Compliance</h4>
                    <div class="mt-3">
                        <div class="w-full bg-white/5 rounded-full h-3 overflow-hidden">
                            <div class="h-3 rounded-full"
                                style="width: {{ $slaCompliance ?? 0 }}%; background: linear-gradient(90deg,#06b6d4,#3b82f6);">
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-slate-300">{{ $slaCompliance ?? 0 }}% memenuhi SLA</div>
                    </div>
                </div>
                <div class="bg-white/3 p-4 rounded-lg shadow-sm">
                    <h4 class="font-semibold text-white">Quick Stats</h4>
                   <div class="mt-3 grid grid-cols-2 gap-2 text-sm text-slate-300">
                        <div>
                            <div class="text-xs">Assigned to Me</div>
                            <div class="font-medium text-white">{{ $stats['assigned_to_me'] ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs">New Today</div>
                            <div class="font-medium text-white">{{ $stats['today'] ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs">High Priority</div>
                            <div class="font-medium text-white">{{ $stats['high'] ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs">Waiting Tikets</div>
                            <div class="font-medium text-white">{{ $stats['waiting_customer'] ?? 0 }}</div>
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
        .text-center {
            text-align: center;
        }

        /* Mobile Card Styles */
        .ticket-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 0.75rem;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }
        .ticket-card:active {
            transform: scale(0.98);
            background: rgba(255, 255, 255, 0.08);
        }
        .ticket-card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .ticket-queue {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .ticket-status {
            padding: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .ticket-info {
            margin-bottom: 0.5rem;
        }
        .ticket-label {
            color: #94a3b8;
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
        }
        .ticket-value {
            color: #f1f5f9;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .ticket-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .ticket-actions .btn-action {
            flex: 1;
            justify-content: center;
        }

        /* Mobile Pagination Styles */
        .mobile-pagination-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            padding: 1rem 0;
        }
        .mobile-page-btn {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 2.5rem;
            text-align: center;
        }
        .mobile-page-btn:active {
            transform: scale(0.95);
        }
        .mobile-page-btn.active {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
            border-color: transparent;
            color: white;
        }
        .mobile-page-btn.disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }
        .mobile-page-info {
            color: #94a3b8;
            font-size: 0.813rem;
        }

        /* Loading animation */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(function() {
        var table;
        var mobileData = [];
        var currentPage = 1;
        var totalPages = 1;
        var itemsPerPage = 10;
        var searchQuery = '';
        var totalRecords = 0;
        var searchTimeout;

        // Initialize DataTable for desktop
        if ($(window).width() >= 768) {
            initializeDataTable();
        } else {
            loadMobileData();
        }

        // Mobile search handler
        $('#mobile-search').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchQuery = $(this).val();
            searchTimeout = setTimeout(function() {
                currentPage = 1;
                loadMobileData();
            }, 500);
        });

        // Mobile length change handler
        $('#mobile-length').on('change', function() {
            itemsPerPage = parseInt($(this).val());
            currentPage = 1;
            loadMobileData();
        });

        // Handle window resize
        $(window).on('resize', function() {
            if ($(window).width() >= 768 && !table) {
                initializeDataTable();
                $('#mobile-cards-container').empty();
                $('#mobile-pagination').empty();
            } else if ($(window).width() < 768 && table) {
                table.destroy();
                table = null;
                loadMobileData();
            }
        });

        function initializeDataTable() {
            table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                scrollX: true,
                autoWidth: false,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                ajax: "{{ route('allticketforadmins.allticketforadmins') }}",
                columnDefs: [
                    { targets: '_all', className: 'dt-center' }
                ],
                columns: [{
                        data: 'queue_number',
                        name: 'queue_number',
                        width: '10%',
                        className: 'text-center'
                    },
                    {
                        data: 'employee_name',
                        name: 'employees_tables.employee_name',
                        width: '25%',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                        width: '25%',
                        className: 'text-center'
                    },
                    {
                        data: 'category',
                        name: 'category',
                        width: '15%',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        width: '15%',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        width: '30%',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
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
        }

        function loadMobileData() {
            // Show loading state
            $('#mobile-cards-container').html('<div class="text-center text-slate-400 py-8"><div class="animate-pulse">Loading...</div></div>');
            
            $.ajax({
                url: "{{ route('allticketforadmins.allticketforadmins') }}",
                type: 'GET',
                data: {
                    start: (currentPage - 1) * itemsPerPage,
                    length: itemsPerPage,
                    draw: 1,
                    'search[value]': searchQuery
                },
                success: function(response) {
                    if (response && response.data) {
                        mobileData = response.data;
                        totalRecords = response.recordsTotal || response.recordsFiltered || 0;
                        var filteredRecords = response.recordsFiltered || totalRecords;
                        totalPages = Math.ceil(filteredRecords / itemsPerPage);
                        renderMobileCards();
                        renderMobilePagination(filteredRecords);
                        updateMobileInfo(filteredRecords);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading mobile data:', error);
                    $('#mobile-cards-container').html('<div class="text-center text-red-400 py-8">Error loading tickets</div>');
                }
            });
        }

        function renderMobileCards() {
            var container = $('#mobile-cards-container');
            container.empty();

            if (!mobileData || mobileData.length === 0) {
                container.append('<div class="text-center text-slate-400 py-8">No tickets found</div>');
                return;
            }

            mobileData.forEach(function(ticket) {
                var statusClass = getStatusClass(ticket.status || '');
                var queueNumber = ticket.queue_number || 'N/A';
                var employeeName = ticket.employee_name || '-';
                var title = ticket.title || 'No title';
                var description = ticket.description || 'No description';
                var category = ticket.category || '-';
                var status = ticket.status || 'Unknown';
                var action = ticket.action || '';
                
                var card = `
                    <div class="ticket-card">
                        <div class="ticket-card-header">
                            <span class="ticket-queue">#${queueNumber}</span>
                            <span class="ticket-status ${statusClass}">${status}</span>
                        </div>
                        <div class="ticket-info">
                            <div class="ticket-label">User</div>
                            <div class="ticket-value">${employeeName}</div>
                        </div>
                        <div class="ticket-info">
                            <div class="ticket-label">Title</div>
                            <div class="ticket-value">${title}</div>
                        </div>
                        <div class="ticket-info">
                            <div class="ticket-label">Description</div>
                            <div class="ticket-value">${description}</div>
                        </div>
                        <div class="ticket-info">
                            <div class="ticket-label">Category</div>
                            <div class="ticket-value">${category}</div>
                        </div>
                        <div class="ticket-actions">
                            ${action}
                        </div>
                    </div>
                `;
                container.append(card);
            });
        }

        function renderMobilePagination(total) {
            var pagination = $('#mobile-pagination');
            pagination.empty();

            if (!total || totalPages <= 1) return;

            var wrapper = $('<div class="mobile-pagination-wrapper"></div>');
            
            // Previous button
            var prevBtn = $('<button class="mobile-page-btn ' + (currentPage === 1 ? 'disabled' : '') + '">‹</button>');
            prevBtn.on('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    loadMobileData();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
            wrapper.append(prevBtn);

            // Page info
            wrapper.append(`<span class="mobile-page-info">${currentPage} / ${totalPages}</span>`);

            // Next button
            var nextBtn = $('<button class="mobile-page-btn ' + (currentPage === totalPages ? 'disabled' : '') + '">›</button>');
            nextBtn.on('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    loadMobileData();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
            wrapper.append(nextBtn);

            pagination.append(wrapper);
        }

        function updateMobileInfo(filteredRecords) {
            var start = (currentPage - 1) * itemsPerPage + 1;
            var end = Math.min(currentPage * itemsPerPage, filteredRecords);
            var infoText = filteredRecords > 0 ? `${start}-${end} of ${filteredRecords}` : 'No entries';
            $('#mobile-info').text(infoText);
        }

        function getStatusClass(status) {
            if (!status) return 'bg-slate-500/20 text-slate-300';
            
            status = status.toString().toLowerCase();
            if (status.includes('open') || status.includes('new')) {
                return 'bg-blue-500/20 text-blue-300';
            } else if (status.includes('progress') || status.includes('assigned')) {
                return 'bg-yellow-500/20 text-yellow-300';
            } else if (status.includes('closed') || status.includes('resolved')) {
                return 'bg-green-500/20 text-green-300';
            } else if (status.includes('overdue')) {
                return 'bg-red-500/20 text-red-300';
            }
            return 'bg-slate-500/20 text-slate-300';
        }
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
@endsection
{{-- @extends('layouts.app')
@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subtitle', 'Manage Dashboard Ticketing System')
@section('content')
    <div class="p-4 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">Open</p>
                        <p class="text-2xl font-bold text-white">{{ $todaysticket }}</p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Today's Unchecked tickets</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">In Progress</p>
                        <p class="text-2xl font-bold text-white">{{ $onprogressticket }}</p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Currently being worked on by the team</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">Closed</p>
                        <p class="text-2xl font-bold text-white">{{ $closedticket }}</p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Ticket resolved</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">Overdue</p>
                        <p class="text-2xl font-bold text-rose-400">{{ $overdueticket }}</p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Tiket melewati SLA</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4"> 
            <div class="lg:col-span-2 space-y-4"> 
                <h1 class="mt-3 text-xl text-slate-100">Recent Tickets</h1>
                <div class="relative -mx-4 md:mx-0">
                    <div class="overflow-x-auto overflow-y-hidden w-full">
                        <table id="users-table" class="w-full text-sm border border-white/10 rounded-lg">
                            <thead class="text-slate-300/80 text-center">
                                <tr>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">Queue</th>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">User</th>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">Title</th>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">Categories</th>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">Status</th>
                                    <th class="px-3 py-2 whitespace-nowrap dt-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="bg-white/3 p-4 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-white mb-3">Activity</h3>
                    <ul class="space-y-3 text-sm text-slate-300">
                        @foreach ($activities ?? [] as $act)
                            <li class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full mt-2 bg-slate-400"></div>
                                <div>
                                    <div class="text-slate-100">{{ $act['title'] }}</div>
                                    <div class="text-xs text-slate-400">{{ $act['time'] }}</div>
                                </div>
                            </li>
                        @endforeach
                        @if (empty($activities))
                            <li class="text-slate-400">No activity</li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="space-y-4">
                <div class="bg-white/3 p-4 rounded-lg shadow-sm">
                    <h4 class="font-semibold text-white">SLA Compliance</h4>
                    <div class="mt-3">
                        <div class="w-full bg-white/5 rounded-full h-3 overflow-hidden">
                            <div class="h-3 rounded-full"
                                style="width: {{ $slaCompliance ?? 0 }}%; background: linear-gradient(90deg,#06b6d4,#3b82f6);">
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-slate-300">{{ $slaCompliance ?? 0 }}% memenuhi SLA</div>
                    </div>
                </div>
                <div class="bg-white/3 p-4 rounded-lg shadow-sm">
                    <h4 class="font-semibold text-white">Quick Stats</h4>
                   <div class="mt-3 grid grid-cols-2 gap-2 text-sm text-slate-300">
                        <div>
                            <div class="text-xs">Assigned to Me</div>
                            <div class="font-medium text-white">{{ $stats['assigned_to_me'] ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs">New Today</div>
                            <div class="font-medium text-white">{{ $stats['today'] ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs">High Priority</div>
                            <div class="font-medium text-white">{{ $stats['high'] ?? 0 }}</div>
                        </div>
                        <div>
                            <div class="text-xs">Waiting Tikets</div>
                            <div class="font-medium text-white">{{ $stats['waiting_customer'] ?? 0 }}</div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(function() {
        var table = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            scrollX: true,
            autoWidth: false,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            ajax: "{{ route('allticketforadmins.allticketforadmins') }}",
             columnDefs: [
        { targets: '_all', className: 'dt-center' }
    ],
            columns: [{
                    data: 'queue_number',
                    name: 'queue_number',
                    width: '10%',
                    className: 'text-center'

                },
                {
                    data: 'employee_name',
                    name: 'employees_tables.employee_name',
                    width: '25%',
                    className: 'hidden md:table-cell',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'title',
                    name: 'title',
                    width: '25%',
                    className: 'hidden md:table-cell',
                    className: 'text-center'
                },
                {
                    data: 'category',
                    name: 'category',
                    width: '15%',
                    className: 'hidden md:table-cell',
                    className: 'text-center'
                },
                {
                    data: 'status',
                    name: 'status',
                    width: '15%',
                    className: 'hidden md:table-cell',
                    className: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    width: '30%',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
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
@endsection --}}

{{-- @endpush --}}
{{-- @extends('layouts.app')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')
<div class="p-4 space-y-4">

    <div class="grid grid-cols-3 gap-3">
        <div class="bg-red-100 text-red-600 p-3 rounded-lg text-center">
            <p class="text-sm">Open</p>
            <p class="text-xl font-bold">3</p>
        </div>

        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-lg text-center">
            <p class="text-sm">Progress</p>
            <p class="text-xl font-bold">2</p>
        </div>

        <div class="bg-green-100 text-green-600 p-3 rounded-lg text-center">
            <p class="text-sm">Closed</p>
            <p class="text-xl font-bold">8</p>
        </div>
    </div>

    <a href="#"
       class="block bg-blue-600 text-white text-center py-3 rounded-lg font-semibold">
        + Buat Ticket
    </a>

</div>
@endsection --}}
{{-- <a href="{{ route('tickets.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow"> --}}
{{-- <a href="#" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                Buat Ticket
            </a> --}}

{{-- <a href="{{ route('tickets.import') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg font-medium shadow-sm"> --}}
{{-- <a href="#" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg font-medium shadow-sm">
                Import CSV
            </a> --}}
{{-- <div class="flex items-center gap-2">
            @foreach ($quickFilters ?? ['All', 'Open', 'In Progress', 'Overdue', 'My Tickets'] as $filter)
                <a href="#" class="px-3 py-1 rounded-full text-sm bg-white/5 hover:bg-white/10 text-slate-200">{{ $filter }}</a>
            @endforeach
        </div> --}}
{{-- <tbody>
                            @forelse($recentTickets ?? [] as $ticket)
                                <tr class="border-t border-white/5 hover:bg-white/2">
                                    <td class="px-3 py-2">#{{ $ticket->id }}</td>
                                    <td class="px-3 py-2"><a href="{{ route('tickets.show', $ticket->id) }}" class="font-medium hover:underline">{{ Str::limit($ticket->title, 50) }}</a></td>
                                    <td class="px-3 py-2">{{ ucfirst($ticket->priority) }}</td>
                                    <td class="px-3 py-2">{{ ucfirst($ticket->status) }}</td>
                                    <td class="px-3 py-2">{{ $ticket->assigned_to?->name ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $ticket->updated_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-3 py-4 text-slate-400">There are no recent tickets.</td></tr>
                            @endforelse
                        </tbody> --}}
{{-- <div class="bg-white/3 p-4 rounded-lg shadow-sm">
                <h4 class="font-semibold text-white">Announcements</h4>
                <div class="mt-3 text-sm text-slate-300 space-y-2">
                    @forelse($announcements ?? [] as $ann)
                        <div class="p-2 rounded-md bg-white/2">
                            <div class="font-medium">{{ $ann->title }}</div>
                            <div class="text-xs text-slate-400">{{ $ann->created_at->format('d M Y H:i') }}</div>
                        </div>
                    @empty
                        <div class="text-slate-400">Tidak ada pengumuman.</div>
                    @endforelse
                </div>
            </div> --}}
{{-- <div class="text-center pb-4">
        <p class="text-xs text-slate-500">Ticketing v.1.0</p>
        <p class="text-xs text-slate-600 mt-1">&copy; 2025 IT Departments, Developed by Edwin Sirait</p>
    </div> --}}
{{-- Floating action button for mobile --}}
{{-- <a href="#" class="fixed right-4 bottom-6 sm:bottom-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg flex items-center justify-center md:hidden"> --}}
{{-- <a href="{{ route('tickets.create') }}" class="fixed right-4 bottom-6 sm:bottom-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg flex items-center justify-center md:hidden"> --}}
{{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg> --}}
{{-- </a> --}}
