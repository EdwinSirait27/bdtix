@extends('layouts.app')
@section('title', 'Users Management')
@section('header', 'Users')
@section('subtitle', 'Manage system users and permissions')
@section('content')
    <style>
        /* Base DataTables Styling */
        .dataTables_wrapper {
            font-family: var(--font-body);
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            color: var(--color-ink);
            font-size: 0.875rem;
        }

        .dark .dataTables_wrapper .dataTables_length,
        .dark .dataTables_wrapper .dataTables_filter,
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_paginate {
            color: var(--color-ink);
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--color-rule);
            border-radius: var(--radius-pill);
            padding: 0.5rem;
            font-size: 0.875rem;
            margin: 0 0.5rem;
        }

        .dark .dataTables_wrapper .dataTables_length select,
        .dark .dataTables_wrapper .dataTables_filter input {
            border-color: var(--color-rule-2);
            background-color: var(--color-paper-3);
            color: var(--color-ink);
        }

        /* Table Styling - Desktop Only */
        #users-table {
            width: 100% !important;
        }

        #users-table thead {
            background: var(--color-paper-3);
            color: var(--color-ink);
        }

        #users-table thead th {
            padding: 1rem;
            font-weight: 500;
            text-transform: none;
            font-size: 0.75rem;
            letter-spacing: 0;
            border: none;
            white-space: nowrap;
        }

        .dark #users-table thead {
            background: var(--color-paper);
        }

        #users-table tbody tr {
            border-bottom: 1px solid var(--color-rule);
            transition: background-color 0.2s;
        }

        .dark #users-table tbody tr {
            border-bottom-color: var(--color-rule);
        }

        #users-table tbody tr:hover {
            background-color: var(--color-paper-3);
        }

        .dark #users-table tbody tr:hover {
            background-color: var(--color-paper-2);
        }

        #users-table tbody td {
            padding: 1rem;
            color: var(--color-ink);
            font-size: 0.875rem;
            vertical-align: middle;
        }

        .dark #users-table tbody td {
            color: var(--color-ink);
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
            border: 1px solid var(--color-rule);
            border-radius: var(--radius-pill);
            background-color: var(--color-paper-2);
            color: var(--color-muted);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition-property: background-color, border-color, color; transition-duration: 0.2s;
            min-width: 2rem;
            text-align: center;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: var(--color-paper-2);
            border-color: var(--color-rule);
            color: var(--color-ink);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--color-accent);
            border-color: transparent;
            color: var(--color-accent-ink);
            box-shadow: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
        .dataTables_wrapper .dataTables_paginate .paginate_button.next {
            font-weight: 500;
        }

        /* Dark mode pagination */
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: var(--color-paper-2);
            border-color: var(--color-rule);
            color: var(--color-ink);
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: var(--color-paper-3);
            border-color: var(--color-rule-2);
            color: var(--color-ink);
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--color-accent);
            color: var(--color-accent-ink);
        }

        /* Action Buttons */
        .btn-action {
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-pill);
            font-size: 0.875rem;
            font-weight: 500;
            transition-property: background-color, border-color, color; transition-duration: 0.2s;
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
            background: var(--color-accent);
            color: var(--color-accent-ink);
            border: none;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: none;
        }

        .btn-delete {
            background: var(--color-bad-solid);
            color: var(--color-on-solid);
            border: none;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: none;
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
            background: var(--color-paper-2);
            border: 1px solid var(--color-rule);
            border-radius: var(--radius-card);
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: none;
            transition-property: background-color, border-color, color; transition-duration: 0.3s;
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
            border-bottom: 1px solid var(--color-rule);
        }

        .user-card-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--color-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-accent-ink);
            font-weight: 500;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .user-card-title {
            flex: 1;
            margin-left: 0.75rem;
        }

        .user-card-name {
            color: var(--color-ink);
            font-weight: 500;
            font-size: 1rem;
            margin-bottom: 0.125rem;
        }

        .user-card-username {
            color: var(--color-ink-2);
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
            color: var(--color-ink-2);
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: none;
            letter-spacing: 0;
            min-width: 90px;
            flex-shrink: 0;
        }

        .user-card-value {
            color: var(--color-ink);
            font-size: 0.875rem;
            flex: 1;
        }

        .user-card-badge {
            display: inline-block;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            background: var(--color-accent);
            color: var(--color-accent-ink);
        }

        .user-card-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--color-rule);
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
            border: 1px solid var(--color-rule);
            border-radius: var(--radius-pill);
            background-color: var(--color-paper-2);
            color: var(--color-ink);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition-property: background-color, border-color, color; transition-duration: 0.2s;
        }

        .mobile-page-btn:active {
            transform: scale(0.95);
        }

        .mobile-page-btn.active {
            background: var(--color-accent);
            color: var(--color-accent-ink);
            border-color: transparent;
        }

        .mobile-page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .mobile-info-text {
            color: var(--color-ink-2);
            font-size: 0.813rem;
            text-align: center;
            margin-top: 0.75rem;
        }
    </style>

    <div class="space-y-4 md:space-y-6">
        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            <div
                class="rounded-[10px] md:rounded-[10px] p-4 md:p-6 text-ink bg-slate-800 border border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-medium opacity-90">Total Users</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-medium mb-1" id="total-users"></p>
                <p class="text-slate-400 text-xs">Active accounts</p>
            </div>
            <div
                class="rounded-[10px] md:rounded-[10px] p-4 md:p-6 text-ink bg-slate-800 border border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-medium opacity-90">Yang Maha Besar</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-2xl md:text-xl font-medium mb-1">Christopher Edwin Sirait, S.Kom</p>
                <p class="text-slate-400 text-xs">~bukan sarjana kaleng"</p>
            </div>
            <div
                class="rounded-[10px] md:rounded-[10px] p-4 md:p-6 text-ink bg-slate-800 border border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-medium opacity-90">1 Thessalonians 5:17</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20M7 7h10" />
                    </svg>

                </div>
                <p class="text-2xl md:text-3xl font-medium mb-1">pray without ceasing</p>
                <p class="text-slate-400 text-xs">Christ is King!</p>
            </div>
            <div
                class="rounded-[10px] md:rounded-[10px] p-4 md:p-6 text-ink bg-slate-800 border border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs md:text-sm font-medium opacity-90">Administrators</h3>
                    <svg class="w-6 h-6 md:w-8 md:h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <p class="text-2xl md:text-3xl font-medium mb-1">Edwin Sirait</p>
                <p class="text-slate-400 text-xs">namanya juga yang buat ya mau gimana</p>
            </div>
        </div>

        {{-- Main Content Card --}}
        <div class="bg-slate-800 rounded-[10px] md:rounded-[10px] border border-slate-700 overflow-hidden">
            {{-- Card Header --}}
            <div class="px-4 py-4 md:px-6 md:py-5 border-b border-slate-700">
                <div class="flex flex-col gap-3 md:gap-4">
                    <div>
                        <h2 class="text-lg md:text-xl font-medium text-ink">All Users</h2>
                        <p class="text-xs md:text-sm text-slate-400 mt-1">Manage and view all system users</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
                        {{-- Search Input --}}
                        <div class="relative flex-1">
                            <input type="text" id="table-search" placeholder="Search users..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-600 rounded-md bg-slate-950 text-ink text-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent placeholder-slate-400">
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
                        <p class="text-slate-400 text-sm">Loading users...</p>
                    </div>
                </div>

                {{-- Desktop Table View --}}
                <div id="users-table-wrapper" class="overflow-x-auto -mx-4 md:mx-0" style="display: none;">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <div class="flex gap-2 mb-4">
                                <button id="checkAllBtn"
                                    class="px-4 py-2 bg-slate-600 text-ink rounded hover:bg-slate-700">
                                    Check All
                                </button>

                                <button id="uncheckAllBtn"
                                    class="px-4 py-2 bg-slate-600 text-ink rounded hover:bg-slate-700">
                                    Uncheck All
                                </button>

                                <button id="updateRoleHumanBtn"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                    Update Role to Human
                                </button>
                            </div>

                            <table class="min-w-full divide-y divide-slate-700" id="users-table">
                                <thead class="bg-slate-900">
                                    <tr>
                                        <th class="text-center">Selext</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Employee Name</th>
                                        <th class="text-center">Company</th>
                                        <th class="text-center">Department</th>
                                        <th class="text-center">Location</th>
                                        <th class="text-center">Position</th>
                                        <th class="text-center">Roles</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-slate-800 divide-y divide-slate-700">
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            data: 'checkbox',
                            name: 'checkbox',
                            orderable: false,
                            searchable: false,
                            width: '5%',
                            className: 'text-center'
                        },
                        {
                            data: 'username',
                            name: 'username',
                            width: '10%'
                        },
                        {
                            data: 'employee_name',
                            name: 'employees_tables.employee_name',
                            width: '25%'
                        },
                        {
                            data: 'company_name',
                            name: 'company_tables.name',
                            width: '25%'
                        },
                        {
                            data: 'department_name',
                            name: 'departments_tables.department_name',
                            width: '25%'
                        },
                        {
                            data: 'store_name',
                            name: 'stores_tables.name',
                            width: '25%'
                        },
                        {
                            data: 'position_name',
                            name: 'position_tables.name',
                            width: '25%'
                        },
                        {
                            data: 'roles',
                            name: 'roles',
                            width: '25%'
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
                        container.html('<div class="text-center py-8 text-slate-400">No users found</div>');
                        return;
                    }

                    data.each(function(user) {
                        var initials = user.employee_name ? user.employee_name.substring(0, 2).toUpperCase() :
                            'U';

                        var card = `
                            <div class="user-card">
                                <div class="user-card-header">
                                    <div class="user-card-avatar">${initials}</div>
                                    <div class="user-card-title">
                                        <div class="user-card-name">${user.employee_name || 'N/A'}</div>
                                        <div class="user-card-username">NIP : ${user.username}</div>
                                    </div>
                                </div>
                                <div class="user-card-body">
                                    <div class="user-card-field">
                                        <div class="user-card-label">Company</div>
                                        <div class="user-card-value">${user.company_name || 'N/A'}</div>
                                    </div>
                                    <div class="user-card-field">
                                        <div class="user-card-label">Department</div>
                                        <div class="user-card-value">${user.department_name || 'N/A'}</div>
                                    </div>
                                    <div class="user-card-field">
                                        <div class="user-card-label">Location</div>
                                        <div class="user-card-value">${user.store_name || 'N/A'}</div>
                                    </div>
                                    <div class="user-card-field">
                                        <div class="user-card-label">Position</div>
                                        <div class="user-card-value">${user.position_name || 'N/A'}</div>
                                    </div>
                                    <div class="user-card-field">
                                        <div class="user-card-label">Role</div>
                                        <div class="user-card-value">
                                            <span class="user-card-badge">${user.roles || 'User'}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="user-card-actions">
                                    ${user.action}
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
        <script>
            // CHECK ALL
            $('#checkAllBtn').on('click', function() {
                $('.user-checkbox').prop('checked', true);
            });

            // UNCHECK ALL
            $('#uncheckAllBtn').on('click', function() {
                $('.user-checkbox').prop('checked', false);
            });

            // UPDATE ROLE TO HUMAN
            $('#updateRoleHumanBtn').on('click', function() {

                let userIds = [];
                $('.user-checkbox:checked').each(function() {
                    userIds.push($(this).val());
                });

                if (userIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No user selected',
                        text: 'Please select at least one user'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Update Role?',
                    text: 'Selected users will be assigned role HUMAN',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Update',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#4f46e5'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('users.bulkUpdateRole') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                user_ids: userIds
                            },
                            success: function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Role updated successfully'
                                });

                                $('#users-table').DataTable().ajax.reload(null, false);
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to update role'
                                });
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
