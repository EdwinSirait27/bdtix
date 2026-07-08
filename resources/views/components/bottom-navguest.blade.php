<nav
    class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-slate-900/95 backdrop-blur-xl border-t border-slate-800 shadow-2xl">
    <div class="flex overflow-x-auto scrollbar-hide -webkit-overflow-scrolling-touch px-1" id="main-nav">

        {{-- Home --}}
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            @if (request()->routeIs('dashboard'))
                <div class="nav-indicator"></div>
            @endif
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12L11.204 3.045a1.125 1.125 0 011.591 0L21.75 12M4.5 9.75V19.5a.75.75 0 00.75.75h4.5a.75.75 0 00.75-.75v-4.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v4.5a.75.75 0 00.75.75h4.5a.75.75 0 00.75-.75V9.75" />
            </svg>
            <span class="nav-label">Home</span>
        </a>

        {{-- Profile --}}
        <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            @if (request()->routeIs('profile'))
                <div class="nav-indicator"></div>
            @endif
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <span class="nav-label">Profile</span>
        </a>
        @role('admin|executor')
            <a href="{{ route('resolvetickets') }}"
                class="nav-item {{ request()->routeIs('resolvetickets') ? 'active' : '' }}">
                @if (request()->routeIs('resolvetickets'))
                    <div class="nav-indicator"></div>
                @endif
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                <span class="nav-label">Resolve Tickets</span>
            </a>
        @endrole
        @role('human')

            <a href="{{ route('openticket') }}" class="nav-item {{ request()->routeIs('openticket') ? 'active' : '' }}">
                @if (request()->routeIs('openticket'))
                    <div class="nav-indicator"></div>
                @endif
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                </svg>
                <span class="nav-label">New Tickets</span>
            </a>
        @endrole

      
        @auth
            <form action="{{ route('logout.post') }}" method="POST" class="contents" id="mobile-logout-form">
                @csrf
                <a href="#" onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();"
                    class="nav-item text-slate-400 hover:text-red-400">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 9V5.25A2.25 2.25 0 0110.5 3h6a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0116.5 21h-6a2.25 2.25 0 01-2.25-2.25V15m-3 0l-3-3m0 0l3-3m-3 3H15" />
                    </svg>
                    <span class="nav-label">Logout</span>
                </a>
            </form>
        @endauth
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
    <div class="h-safe-area-inset-bottom bg-slate-900"></div>
</nav>

<style>
    .scrollbar-hide {
        scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .h-safe-area-inset-bottom {
        height: env(safe-area-inset-bottom);
    }

    .nav-item {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        /* Kunci: 4 item default, sisanya scroll */
        min-width: calc(100% / 4.3);
        flex-shrink: 0;
        padding: 10px 4px;
        gap: 3px;
        text-decoration: none;
        color: #64748b;
        transition: all 0.2s;
    }

    .nav-item:hover {
        color: #94a3b8;
    }

    .nav-item.active {
        color: #60a5fa;
    }

    .nav-icon {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
        transition: transform 0.2s;
    }

    .nav-item.active .nav-icon {
        transform: scale(1.1);
    }

    .nav-label {
        font-size: 10px;
        font-weight: 500;
        white-space: nowrap;
    }

    /* Single unified indicator */
    .nav-indicator {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 32px;
        height: 3px;
        border-radius: 0 0 4px 4px;
        background: linear-gradient(90deg, #3b82f6, #06b6d4);
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(-4px);
        }

        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }
</style>
