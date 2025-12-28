@extends('layouts.app')
@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subtitle', 'Manage Dashboard Ticketing System')
@section('content')


    <div class="p-4 space-y-4">
        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Example: expects $stats = ['open'=>x, 'in_progress'=>y, 'closed'=>z, 'overdue'=>w] --}}
            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">Open</p>
                        <p class="text-2xl font-bold text-white">{{ $todaysticket }}</p>
                        {{-- <p class="text-2xl font-bold text-white">{{ $stats['open'] ?? 0 }}</p> --}}
                    </div>
                    <div class="text-sm text-slate-400">▸ {{ $stats['open_change'] ?? '+0' }}</div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Unchecked tickets</p>
            </div>

            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">In Progress</p>
                        <p class="text-2xl font-bold text-white">{{ $onprogressticket }}</p>
                        {{-- <p class="text-2xl font-bold text-white">{{ $stats['in_progress'] ?? 0 }}</p> --}}
                    </div>
                    <div class="text-sm text-slate-400">▸ {{ $stats['in_progress_change'] ?? '+0' }}</div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Currently being worked on by the team</p>
            </div>

            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">Closed</p>
                        {{-- <p class="text-2xl font-bold text-white">{{ $stats['closed'] ?? 0 }}</p> --}}
                        <p class="text-2xl font-bold text-white">{{ $closedticket }}</p>
                    </div>
                    <div class="text-sm text-slate-400">▸ {{ $stats['closed_change'] ?? '+0' }}</div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Ticket resolved</p>
            </div>

            <div class="bg-white/5 backdrop-blur-sm p-4 rounded-lg shadow-sm flex flex-col">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-300">Overdue</p>
                        <p class="text-2xl font-bold text-rose-400">{{ $overdueticket }}</p>
                        {{-- <p class="text-2xl font-bold text-rose-400">{{ $stats['overdue'] ?? 0 }}</p> --}}
                    </div>
                    <div class="text-sm text-slate-400">▸ {{ $stats['overdue_change'] ?? '+0' }}</div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Tiket melewati SLA</p>
            </div>
        </div>
        {{-- QUICK ACTIONS + FILTERS --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4"> 
            <div class="lg:col-span-2 space-y-4"> 
                <h1 class="mt-3 text-xl text-slate-100">Recent Tickets</h1>

                {{-- <div class="bg-white/3 p-4 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-white mb-3">Recent Tickets</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-white/10 rounded-lg overflow-hidden"
                            id="users-table">
                            <thead class="text-slate-300/80 text-left">
                                <tr>
                                    <th class="px-3 py-2 text-center">Queue</th>
                                    <th class="px-3 py-2 text-center">User</th>
                                    <th class="px-3 py-2 text-center">Tittle</th>
                                    <th class="px-3 py-2 text-center">Categories</th>
                                    <th class="px-3 py-2 text-center">Status</th>
                                    <th class="px-3 py-2 text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="mt-3 text-right">
                        <a href="#" class="text-sm text-slate-300 hover:underline">View all tickets &rarr;</a>
                    </div>
                </div> --}}
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
                        {{-- simple progress bar --}}
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

        /* Table Styling */
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

        /* Pagination Styling */
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

        /* DataTables Info & Length */
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

        /* Action Buttons */
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

        /* Mobile: Hide text in action buttons, show only icons */
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

        /* Responsive table wrapper */
        .dataTables_wrapper .dataTables_scroll {
            overflow-x: auto;
        }

        /* Fix for mobile horizontal scroll */
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

        /* === HARD FIX Mobile DataTables === */
        @media (max-width: 767px) {
            #users-table {
                min-width: 720px !important;
                /* paksa tabel lebih lebar dari layar */
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
{{-- @push('scripts') --}}
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
            // responsive: false
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
