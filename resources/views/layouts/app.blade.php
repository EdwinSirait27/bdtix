<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', 'IT Departments Ticketing')</title>
    <link rel="icon" type="image/png" href="https://cloud.mjm-bali.co.id/index.php/s/J3Wob5N5LjzHwik/download">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <meta name="theme-color" content="#0F172A">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-gray-100 transition-colors duration-300">
    <aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
        <div
            class="flex grow flex-col gap-y-5 overflow-y-auto bg-dark dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 px-6 pb-4">
            <div class="flex h-24 shrink-0 items-center border-b border-slate-200 dark:border-slate-800">
                <img src="https://cloud.mjm-bali.co.id/index.php/s/7ixWakyMn8JCe9F/download"
                    class="h-12 w-12 select-none pointer-events-none" draggable="false" alt="icon">
                <div class="ml-3">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">IT Departments</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Ticketing System</p>
                </div>
            </div>
            <nav class="flex flex-1 flex-col">
                <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                        <ul role="list" class="-mx-2 space-y-1">
                            <li>
                                <a href="{{ route('dashboard') }}"
                                    class="group flex gap-x-3 rounded-lg p-3 text-sm leading-6 font-semibold transition-all
                                   {{ request()->routeIs('dashboard')
                                       ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-md'
                                       : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('alltickets') }}"
                                    class="group flex gap-x-3 rounded-lg p-3 text-sm leading-6 font-semibold transition-all
                                   {{ request()->routeIs('alltickets')
                                       ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-md'
                                       : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    All Tickets
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mytickets') }}"
                                    class="group flex gap-x-3 rounded-lg p-3 text-sm leading-6 font-semibold transition-all
                                   {{ request()->routeIs('mytickets')
                                       ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-md'
                                       : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    My Tickets
                                    <span
                                        class="ml-auto inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900 px-2.5 py-0.5 text-xs font-bold text-blue-600 dark:text-blue-300">{{ $openTicketCount ?? 0 }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('users') }}"
                                    class="group flex gap-x-3 rounded-lg p-3 text-sm leading-6 font-semibold transition-all
                                   {{ request()->routeIs('users')
                                       ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-md'
                                       : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Users
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('openticket') }}"
                                    class="group flex gap-x-3 rounded-lg p-3 text-sm leading-6 font-semibold transition-all
                                   {{ request()->routeIs('openticket')
                                       ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-md'
                                       : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Ticket
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="group flex gap-x-3 rounded-lg p-3 text-sm leading-6 font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Resolved Tickets
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="group flex gap-x-3 rounded-lg p-3 text-sm leading-6 font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    History
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="text-xs font-semibold leading-6 text-slate-400 uppercase tracking-wider mb-2">
                            Settings</div>
                        <ul role="list" class="-mx-2 space-y-1">
                            <li>
                                <a href="{{ route('profile') }}"
                                    class="group flex gap-x-3 rounded-lg p-3 text-sm leading-6 font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                                    <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('about') }}"
                                    class="group flex gap-x-3 rounded-lg p-3 text-sm leading-6 font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
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
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                <div class="flex items-center gap-x-3 min-w-0">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">
                                            {{ Auth::user()->employee->employee_name ?? ' +62 812-3456-9999' }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
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
        <div
            class="max-w-md lg:max-w-none mx-auto min-h-screen bg-dark dark:bg-slate-900 lg:bg-slate-100 lg:dark:bg-slate-950 shadow-2xl lg:shadow-none transition-colors duration-300">
            <header
                class="sticky top-0 z-40 bg-dark dark:bg-slate-900 lg:backdrop-blur-sm lg:bg-dark/80 lg:dark:bg-slate-900/80 border-b border-slate-200 dark:border-slate-800 transition-colors duration-300">
                <div class="px-4 sm:px-6 lg:px-8 py-4 lg:py-6">
                    <div class="flex items-center justify-between mb-4 lg:hidden">
                        <div class="flex items-center space-x-3">
                            <img src="https://cloud.mjm-bali.co.id/index.php/s/7ixWakyMn8JCe9F/download"
                                class="w-16 h-16 select-none pointer-events-none" draggable="false" alt="icon">
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white">@yield('company', 'IT Departments')</h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Ticketing</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 lg:space-x-4">
                            <div
                                class="hidden lg:block h-10 w-1 bg-gradient-to-b from-blue-500 to-cyan-500 rounded-full">
                            </div>
                            <div class="lg:hidden h-6 w-1 bg-gradient-to-b from-blue-500 to-cyan-500 rounded-full">
                            </div>
                            <div>
                                <h1 class="text-lg sm:text-xl lg:text-3xl font-bold text-slate-900 dark:text-white">
                                    @yield('header', 'Dashboard')
                                </h1>
                                <p class="text-xs sm:text-sm lg:text-base text-slate-500 dark:text-slate-400 mt-0.5">
                                    @yield('subtitle', 'Manage your support tickets')
                                </p>
                            </div>
                        </div>
                        @auth
                            <div class="hidden lg:flex items-center space-x-3">
                                <form action="{{ route('logout.post') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-500 text-white rounded-xl font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all text-sm">
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
    <div class="hidden lg:block fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div
            class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-300/10 dark:bg-blue-900/10 rounded-full filter blur-3xl transition-colors">
        </div>
        <div
            class="absolute bottom-0 left-72 w-[600px] h-[600px] bg-cyan-300/10 dark:bg-cyan-900/10 rounded-full filter blur-3xl transition-colors">
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
 {{-- <script>
        // Apply saved theme on load
        if (localStorage.theme === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
        // Toggle theme
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                html.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script> --}}
{{-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', 'IT Departments Ticketing')</title>
    <link rel="icon" type="image/png" href="https://cloud.mjm-bali.co.id/index.php/s/J3Wob5N5LjzHwik/download">

    @vite('resources/css/app.css')
    <meta name="theme-color" content="#0F172A">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-gray-100 transition-colors duration-300">
    
    <aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:block lg:w-72 lg:overflow-y-auto lg:bg-white lg:dark:bg-slate-900 lg:border-r lg:border-slate-200 lg:dark:border-slate-800">
        <div class="flex flex-col h-full">
            <div class="flex items-center space-x-3 px-6 py-6 border-b border-slate-200 dark:border-slate-800">
                <img src="https://cloud.mjm-bali.co.id/index.php/s/7ixWakyMn8JCe9F/download"
                    class="w-12 h-12 select-none pointer-events-none" draggable="false" alt="icon">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">IT Departments</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Ticketing System</p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('dashboard') }}"
   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all
   {{ request()->routeIs('dashboard') 
        ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-500/50' 
        : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
    
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>

    <span class="font-semibold">Dashboard</span>
</a>

                
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="font-medium">My Tickets</span>
                    <span class="ml-auto px-2 py-1 text-xs font-bold bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 rounded-full">24</span>
                </a>
                
               <a href="{{ route('openticket') }}"
   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all
   {{ request()->routeIs('openticket') 
        ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-500/50' 
        : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
    
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4v16m8-8H4"/>
    </svg>

    <span class="font-medium">Create Ticket</span>
</a>


                
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Resolved Tickets</span>
                </a>
                
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">History</span>
                </a>
                
                <div class="pt-6 mt-6 border-t border-slate-200 dark:border-slate-800">
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">Settings</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">Profile</span>
                    </a>
                </div>
            </nav>

            <div class="px-4 py-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between px-4 py-3 bg-slate-50 dark:bg-slate-800 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold">
                            JD
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">John Doe</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">john@example.com</p>
                        </div>
                    </div>
                    <button onclick="toggleTheme()" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                        <svg class="w-5 h-5 hidden dark:block text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg class="w-5 h-5 block dark:hidden text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <div class="lg:pl-72">
        <div class="max-w-md lg:max-w-none mx-auto min-h-screen bg-white dark:bg-slate-900 lg:bg-transparent shadow-2xl lg:shadow-none transition-colors duration-300">
        <header class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 transition-colors duration-300">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between mb-4 lg:hidden">
                        <div class="flex items-center space-x-3">
                            <img src="https://cloud.mjm-bali.co.id/index.php/s/7ixWakyMn8JCe9F/download"
                                class="w-16 h-16 select-none pointer-events-none" draggable="false" alt="icon">
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white">@yield('company', 'IT Departments')</h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Ticketing</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="h-6 sm:h-8 w-1 bg-gradient-to-b from-blue-500 to-cyan-500 rounded-full"></div>
                            <div>
                                <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-900 dark:text-white">
                                    @yield('header', 'Dashboard')
                                </h1>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">@yield('subtitle', 'Manage your support tickets')</p>
                            </div>
                        </div>
                        
                        <div class="hidden lg:flex items-center space-x-3">
                            <button class="px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/50 hover:shadow-xl hover:shadow-blue-500/60 transition-all">
                                + New Ticket
                            </button>
                            <button onclick="toggleTheme()" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
            @yield('content')
            <div class="lg:hidden">
                @include('components.bottom-nav')
            </div>
        </div>
    </div>
    
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-300/20 dark:bg-blue-900/20 rounded-full filter blur-3xl transition-colors"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-300/20 dark:bg-cyan-900/20 rounded-full filter blur-3xl transition-colors"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-slate-300/30 dark:bg-slate-800/30 rounded-full filter blur-3xl transition-colors"></div>
    </div>
    
    <div class="fixed inset-0 -z-10 opacity-[0.05] dark:opacity-[0.02] pointer-events-none"
        style="background-image: linear-gradient(#000 1px, transparent 1px), 
               linear-gradient(90deg, #000 1px, transparent 1px);
               background-size: 50px 50px;">
    </div>
    
    <script>
        if (localStorage.theme === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                html.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html> --}}
{{-- <main class="pb-24 lg:pb-8 pt-6 px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">Open Tickets</h3>
                            <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-4xl font-bold mb-2">24</p>
                        <p class="text-blue-100 text-sm">+3 from yesterday</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">Resolved</h3>
                            <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-4xl font-bold mb-2">156</p>
                        <p class="text-emerald-100 text-sm">This month</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold">In Progress</h3>
                            <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-4xl font-bold mb-2">8</p>
                        <p class="text-purple-100 text-sm">Being worked on</p>
                    </div>
                </div>
                
                <div class="mt-8 bg-white dark:bg-slate-800 rounded-2xl shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Recent Tickets</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 dark:bg-slate-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">#1234</td>
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-300">Network Issue in Building A</td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300 hidden md:table-cell">High</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400 hidden lg:table-cell">Dec 12, 2025</td>
                                </tr>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">#1233</td>
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-300">Printer Error - Floor 3</td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Resolved
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300 hidden md:table-cell">Low</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400 hidden lg:table-cell">Dec 11, 2025</td>
                                </tr>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">#1232</td>
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-300">Email Configuration Help</td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            In Progress
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300 hidden md:table-cell">Medium</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400 hidden lg:table-cell">Dec 11, 2025</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main> --}}
