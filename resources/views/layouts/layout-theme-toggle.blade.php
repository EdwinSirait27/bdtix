<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', 'BD Departments Ticketing')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/AsianBay logomark.ico') }}">
    @include('components.theme-init')
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-slate-950 text-gray-100 transition-colors duration-300">
    <aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 border-r border-slate-800 px-6 pb-4">
            <div class="flex h-24 shrink-0 items-center border-b border-slate-800">
                <img src="{{ asset('img/AsianBay.png') }}"
                    class="h-12 w-12 select-none pointer-events-none" draggable="false" alt="icon">
                <div class="ml-3">
                    <h2 class="text-base font-medium text-ink">BD Departments</h2>
                    <p class="text-xs text-slate-400 font-medium">Ticketing System</p>
                </div>
            </div>
            <nav class="flex flex-1 flex-col">
                <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                        <ul role="list" class="-mx-2 space-y-1">
                    @auth

                            <li>
                                <a href="{{ route('dashboard') }}"
                                    class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-medium transition-colors
                                   {{ request()->routeIs('dashboard')
                                       ?'     bg-accent text-accent-ink'
                                       :'text-slate-300 hover:bg-slate-800' }}">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Dashboard
                                </a>
                            </li>
                            @role('admin|executor')

                          
                            @endrole
                            @role('human')

                          
                            @endrole
                            @role('admin')

                            <li>
                                <a href="{{ route('users') }}"
                                    class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-medium transition-colors
                                   {{ request()->routeIs('users')
                                       ?'     bg-accent text-accent-ink'
                                       :'text-slate-300 hover:bg-slate-800' }}">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Users
                                </a>
                            </li>
                            @endrole
                            @role('human')

                            <li>
                                <a href="{{ route('openticket') }}"
                                    class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-medium transition-colors
                                   {{ request()->routeIs('openticket')
                                       ?'     bg-accent text-accent-ink'
                                       :'text-slate-300 hover:bg-slate-800' }}">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Ticket
                                </a>
                            </li>
                            @endrole
                            @role('admin|executor')

                            <li>
                                <a href="#"
                                    class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-medium text-slate-300 hover:bg-slate-800 transition-colors">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Resolved Tickets
                                </a>
                            </li>
                            @endrole
                        </ul>
                    </li>
                    @endauth
                    <li>
                        <div class="text-xs font-medium leading-6 text-slate-400 mb-2">
                            Settings</div>
                        <ul role="list" class="-mx-2 space-y-1">
                           @guest
                            <li>
                                <a href="{{ route('login') }}"
                                    class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-medium text-slate-300 hover:bg-slate-800 transition-colors">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Login Page
                                </a>
                            </li>
                            @endguest
                            @auth
                            <li>
                                <a href="{{ route('profile') }}"
                                    class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-medium transition-colors
                                   {{ request()->routeIs('profile')
                                       ?'     bg-accent text-accent-ink'
                                       :'text-slate-300 hover:bg-slate-800' }}">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                    </svg>
                                    Profile
                                </a>
                            </li>
                            @endauth
                            <li>
                                <a href="{{ route('about') }}"
                                    class="group flex gap-x-3 rounded-md p-3 text-sm leading-6 font-medium transition-colors
                                   {{ request()->routeIs('about')
                                       ?'     bg-accent text-accent-ink'
                                       :'text-slate-300 hover:bg-slate-800' }}">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                    </svg>
                                    About
                                </a>
                            </li>
                        </ul>
                    </li>
                    @auth
                        <li class="mt-auto">
                            <div class="flex items-center justify-between p-3 bg-slate-800 rounded-md">
                                <div class="flex items-center gap-x-3 min-w-0">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-ink truncate">
                                            {{ Auth::user()->employee->employee_name ?? ' +62 812-3456-9999' }}
                                        </p>
                                        <p class="text-xs text-slate-400 truncate">
                                            {{ Auth::user()->employee->email ?? ' +62 812-3456-9999' }}</p>
                                    </div>
               </div>
                            </div>
                        </li>
                    @endauth
                </ul>
            </nav>
        </div>
    </aside>
    <div class="lg:pl-72">
        <div class="max-w-md lg:max-w-none mx-auto min-h-screen bg-slate-900 lg:bg-slate-950 lg:shadow-none transition-colors duration-300">
            <header class="sticky top-0 z-40 bg-slate-900 lg:bg-slate-900/80 border-b border-slate-800 transition-colors duration-300">
                <div class="px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
                    <div class="flex items-center justify-between mb-4 lg:hidden">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('img/AsianBay.png') }}"
                                class="w-16 h-16 select-none pointer-events-none" draggable="false" alt="icon">
                            <div>
                                <h2 class="text-sm font-medium text-ink">@yield('company', 'BD Departments')</h2>
                                <p class="text-xs text-slate-400 font-medium">Ticketing</p>
                            </div>
                        </div>
                        @include('components.theme-toggle')
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 lg:space-x-4">
                            <div>
                                <h1 class="text-lg sm:text-xl lg:text-3xl font-medium text-ink">
                                    @yield('header', 'Dashboard')
                                </h1>
                                <p class="text-xs sm:text-sm lg:text-base text-slate-400 mt-0.5">
                                    @yield('subtitle', 'Manage your support tickets')
                                </p>
                            </div>
                        </div>
                        @auth
                            <div class="hidden lg:flex items-center space-x-3">
                                @include('components.theme-toggle')
                                <form action="{{ route('logout.post') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2.5 rounded-md font-medium transition-colors text-sm border border-slate-600 hover:border-red-400 hover:text-red-400 text-ink bg-slate-800">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>
            <main class="pb-24 lg:pb-8 pt-6 lg:pt-8 px-4 sm:px-6 lg:px-8">
                @yield('content')
            </main>
            <div class="lg:hidden">
                @include('components.bottom-navguest')
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    @stack('scripts')
</body>
</html>