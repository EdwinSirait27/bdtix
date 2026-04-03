<nav
    class="fixed bottom-0 left-0 right-0 mx-auto max-w-md bg-slate-900/95 backdrop-blur-xl border-t border-slate-800 z-50">
    <div class="flex justify-between text-xs">

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
                {{-- <a href="{{ route('alltickets') }}"
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
                </a> --}}
                <a href="{{ route('resolvetickets') }}"
                    class="relative flex flex-col items-center justify-center flex-1 py-3
          {{ request()->routeIs('resolvetickets') ? 'text-blue-400' : 'text-slate-400 hover:text-white' }}
          transition">
                    @if (request()->routeIs('resolvetickets'))
                        <div
                            class="absolute top-0 left-1/2 -translate-x-1/2
                   w-12 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-b-full">
                        </div>
                    @endif
                  <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
                    <span>Res-Tix</span>
                </a>
            @endrole

            @role('human')
                {{-- <a href="{{ route('mytickets') }}"
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
                    <span>M-Tix</span> --}}
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

        {{-- <a href="{{ route('about') }}"
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
        </a> --}}
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
