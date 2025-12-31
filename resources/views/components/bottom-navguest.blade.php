{{-- <nav
    class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-slate-900/95 backdrop-blur-xl border-t border-slate-800 shadow-2xl">
    <div class="grid grid-cols-4 text-xs">

        <a href="{{ route('dashboard') }}" 
            class="relative flex flex-col items-center py-3 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'text-blue-400' : 'text-slate-500 hover:text-slate-300' }}">

            @if (request()->routeIs('dashboard'))
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                </div>
            @endif

            <div
                class="relative {{ request()->routeIs('dashboard') ? 'scale-110' : '' }} transition-transform duration-300">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                @if (request()->routeIs('dashboard'))
                    <div class="absolute -top-1 -right-1 w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                @endif
            </div>
            <span class="font-medium">Home</span>
        </a>

        <a href="#" 
            class="relative flex flex-col items-center py-3 transition-all duration-300 {{ request()->routeIs('tickets.*') ? 'text-blue-400' : 'text-slate-500 hover:text-slate-300' }}">

            @if (request()->routeIs('tickets.*'))
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                </div>
            @endif

            <div
                class="relative {{ request()->routeIs('tickets.*') ? 'scale-110' : '' }} transition-transform duration-300">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
              
            </div>
            <span class="font-medium">Your Tickets</span>
        </a>
        <a href="#"
            class="relative flex flex-col items-center py-3 transition-all duration-300 {{ request()->routeIs('tickets.*') ? 'text-blue-400' : 'text-slate-500 hover:text-slate-300' }}">

            @if (request()->routeIs('tickets.*'))
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                </div>
            @endif

            <div
                class="relative {{ request()->routeIs('tickets.*') ? 'scale-110' : '' }} transition-transform duration-300">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
               
            </div>
            <span class="font-medium">All Tickets</span>
        </a>

        <a href="{{ route('openticket') }}" 
            class="relative flex flex-col items-center py-3 transition-all duration-300 group">

            <div class="absolute -top-6 left-1/2 -translate-x-1/2">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-600 shadow-lg shadow-blue-500/50 flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-90 group-active:scale-95">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
            </div>

            <span class="mt-5 font-medium text-slate-500 group-hover:text-blue-400 transition-colors">New Tickets</span>
        </a>
      
    </div>
    <div class="h-safe-area-inset-bottom bg-slate-900"></div>
</nav>

<style>
    .h-safe-area-inset-bottom {
        height: env(safe-area-inset-bottom);
    }
</style> --}}
{{-- <nav
    class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-slate-900/95 backdrop-blur-xl border-t border-slate-800 shadow-2xl">
    
    <div class="flex text-xs overflow-x-auto scrollbar-hide snap-x snap-mandatory">

        <a href="{{ route('dashboard') }}"
            class="relative flex flex-col items-center py-3 min-w-[80px] snap-center transition-all duration-300
            {{ request()->routeIs('dashboard') ? 'text-blue-400' : 'text-slate-500 hover:text-slate-300' }}">
            @if (request()->routeIs('dashboard'))
                <div class="absolute top-0 w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full"></div>
            @endif
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l9-9 9 9v9a1 1 0 01-1 1h-5v-7H9v7H4a1 1 0 01-1-1z"/>
            </svg>
            <span>Home</span>
        </a>

        <a href="#"
            class="relative flex flex-col items-center py-3 min-w-[80px] snap-center transition-all duration-300 text-slate-500 hover:text-slate-300">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M15 5v14M5 5h14"/>
            </svg>
            <span>Your</span>
        </a>

        <a href="#"
            class="relative flex flex-col items-center py-3 min-w-[80px] snap-center transition-all duration-300 text-slate-500 hover:text-slate-300">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span>All</span>
        </a>

        <a href="{{ route('profile') }}"
            class="relative flex flex-col items-center py-3 min-w-[80px] snap-center transition-all duration-300 text-slate-500 hover:text-slate-300">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M12 12a5 5 0 100-10 5 5 0 000 10zM4 20a8 8 0 0116 0"/>
            </svg>
            <span>Profile</span>
        </a>

        <a href="{{ route('openticket') }}"
            class="relative flex flex-col items-center py-3 min-w-[90px] snap-center group">
            <div class="absolute -top-6">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-600 shadow-lg flex items-center justify-center transition-all group-hover:scale-110">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
            <span class="mt-5 text-slate-400">New</span>
        </a>

    </div>

    <div class="h-safe-area-inset-bottom bg-slate-900"></div>
</nav>
<style>
    .h-safe-area-inset-bottom {
        height: env(safe-area-inset-bottom);
    }
</style>  --}}
{{-- <nav class="fixed bottom-0 left-0 right-0 max-w-md mx-auto
            bg-slate-900/95 backdrop-blur-xl border-t border-slate-800">

    <div class="flex overflow-x-auto snap-x snap-mandatory scrollbar-hide text-xs">

        <a class="min-w-[80px] snap-center flex flex-col items-center py-3 text-slate-400">
            <svg class="w-6 h-6 mb-1"></svg>
            <span>Home</span>
        </a>

        <a class="min-w-[80px] snap-center flex flex-col items-center py-3 text-slate-400">
            <svg class="w-6 h-6 mb-1"></svg>
            <span>Your</span>
        </a>

        <a class="min-w-[80px] snap-center flex flex-col items-center py-3 text-slate-400">
            <svg class="w-6 h-6 mb-1"></svg>
            <span>All</span>
        </a>

        <a class="min-w-[80px] snap-center flex flex-col items-center py-3 text-slate-400">
            <svg class="w-6 h-6 mb-1"></svg>
            <span>Profile</span>
        </a>

        <a class="min-w-[80px] snap-center flex flex-col items-center py-3 text-slate-400">
            <svg class="w-6 h-6 mb-1"></svg>
            <span>Notif</span>
        </a>

        <a class="min-w-[80px] snap-center flex flex-col items-center py-3 text-slate-400">
            <svg class="w-6 h-6 mb-1"></svg>
            <span>History</span>
        </a>

        <a class="min-w-[90px] snap-center flex flex-col items-center py-3 group">
            <div class="w-14 h-14 -mt-6 rounded-2xl bg-blue-600 flex items-center justify-center">
                <svg class="w-7 h-7 text-white"></svg>
            </div>
            <span class="mt-2 text-slate-400">New</span>
        </a>

    </div>
</nav> --}}
<nav
    class="fixed bottom-0 left-0 right-0 mx-auto max-w-md bg-slate-900/95 backdrop-blur-xl border-t border-slate-800 z-50">
    <div class="flex justify-between text-xs">

        <!-- Home -->
        {{-- <a href="{{ route('dashboard') }}"
            class="flex flex-col items-center justify-center flex-1 py-3 text-slate-400 hover:text-white transition">
             @if (request()->routeIs('dashboard'))
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                </div>
            @endif
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="1.8"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 9.75L12 4l9 5.75V20a1 1 0 01-1 1h-5.25v-6h-5.5v6H4a1 1 0 01-1-1V9.75z" />
            </svg>
            <span>Home</span>
        </a> --}}
        @auth
            <a href="{{ route('dashboard') }}"
                class="relative flex flex-col items-center justify-center flex-1 py-3
          {{ request()->routeIs('dashboard') ? 'text-blue-400' : 'text-slate-400 hover:text-white' }}
          transition">

                @if (request()->routeIs('dashboard'))
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2
                   w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                    </div>
                @endif

                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 9.75L12 4l9 5.75V20a1 1 0 01-1 1h-5.25v-6h-5.5v6H4a1 1 0 01-1-1V9.75z" />
                </svg>

                <span>Home</span>
            </a>
@role('admin|executor')
            <!-- Your -->
            <a href="{{ route('alltickets') }}"
                class="relative flex flex-col items-center justify-center flex-1 py-3
          {{ request()->routeIs('alltickets') ? 'text-blue-400' : 'text-slate-400 hover:text-white' }}
          transition">
                @if (request()->routeIs('alltickets'))
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2
                   w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                    </div>
                @endif
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <span>All-Tix</span>
            </a>
            @endrole

                            @role('human')
            <a href="{{ route('mytickets') }}"
                class="relative flex flex-col items-center justify-center flex-1 py-3
          {{ request()->routeIs('mytickets') ? 'text-blue-400' : 'text-slate-400 hover:text-white' }}
          transition">
                @if (request()->routeIs('mytickets'))
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2
                   w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                    </div>
                @endif
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <span>M-Tix</span>
                <a href="{{ route('openticket') }}"
                class="relative flex flex-col items-center justify-center flex-1 py-3
                {{ request()->routeIs('openticket') ? 'text-blue-400' : 'text-slate-400 hover:text-white' }}
                transition">
                <div
                class="w-14 h-14 rounded-2xl bg-blue-600 shadow-lg flex items-center justify-center hover:bg-blue-500 transition">
                @if (request()->routeIs('openticket'))
                <div
                class="absolute top-0 left-1/2 -translate-x-1/2
                w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
            </div>
            @endif
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
    </div>
    <span class="mt-2 text-slate-400">New</span>
</a>
</a>
@endrole
                            @role('admin')

            <a href="{{ route('users') }}"
                class="relative flex flex-col items-center justify-center flex-1 py-3
          {{ request()->routeIs('users') ? 'text-blue-400' : 'text-slate-400 hover:text-white' }}
          transition">
                @if (request()->routeIs('users'))
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2
                   w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                    </div>
                @endif
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 20a6 6 0 0112 0" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 10v4M17 12h4" />
                </svg>


                <span>Users</span>
            </a>
            @endrole
            <!-- Floating New -->


            <!-- All -->


            <!-- Profile -->

            <a href="{{ route('profile') }}"
                class="relative flex flex-col items-center justify-center flex-1 py-3
          {{ request()->routeIs('profile') ? 'text-blue-400' : 'text-slate-400 hover:text-white' }}
          transition">
                @if (request()->routeIs('profile'))
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2
                   w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                    </div>
                @endif

                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 20a6 6 0 0112 0" />
                </svg>

                <span>Profile</span>
            </a>
        @endauth

        <a href="{{ route('about') }}"
            class="relative flex flex-col items-center justify-center flex-1 py-3
          {{ request()->routeIs('about') ? 'text-blue-400' : 'text-slate-400 hover:text-white' }}
          transition">
            @if (request()->routeIs('about'))
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2
                   w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                </div>
            @endif
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 11v5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 8h.01" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span>About</span>
        </a>
        @guest
            <a href="{{ route('login') }}"
                class="relative flex flex-col items-center justify-center flex-1 py-3
          {{ request()->routeIs('login') ? 'text-blue-400' : 'text-slate-400 hover:text-white' }}
          transition">
                @if (request()->routeIs('login'))
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2
                   w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                    </div>
                @endif
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                </svg>
                <span>Login</span>
            </a>
        @endguest
    </div>
</nav>
