<nav class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-slate-900/95 backdrop-blur-xl border-t border-slate-800 shadow-2xl">
    <div class="flex overflow-x-auto scrollbar-hide -webkit-overflow-scrolling-touch px-1" id="main-nav">

        {{-- Home --}}
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            @if(request()->routeIs('dashboard'))
                <div class="nav-indicator"></div>
            @endif
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12L11.204 3.045a1.125 1.125 0 011.591 0L21.75 12M4.5 9.75V19.5a.75.75 0 00.75.75h4.5a.75.75 0 00.75-.75v-4.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v4.5a.75.75 0 00.75.75h4.5a.75.75 0 00.75-.75V9.75"/>
            </svg>
            <span class="nav-label">Home</span>
        </a>

        {{-- Profile --}}
        <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            @if(request()->routeIs('profile'))
                <div class="nav-indicator"></div>
            @endif
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
            </svg>
            <span class="nav-label">Profile</span>
        </a>

        {{-- Members (conditional) --}}
        <a href="{{ route('tickets.*') }}" class="nav-item {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
            @if(request()->routeIs('tickets.*'))
                <div class="nav-indicator"></div>
            @endif
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
            </svg>
            <span class="nav-label">Your Tickets</span>
        </a>  
        {{-- Roles (conditional) --}}
            @role('Admin')
        
        <a href="{{ route('roles.index') }}" class="nav-item {{ request()->routeIs('roles.index') ? 'active' : '' }}">
            @if(request()->routeIs('roles.index'))
                <div class="nav-indicator"></div>
            @endif
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.43.992a6.759 6.759 0 010 .255c-.008.378.137.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="nav-label">Roles</span>
        </a>
        @endrole
       
       
    </div>
    <div class="h-safe-area-inset-bottom bg-slate-900"></div>
</nav>

<style>
    .scrollbar-hide { scrollbar-width: none; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .h-safe-area-inset-bottom { height: env(safe-area-inset-bottom); }

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
    .nav-item:hover { color: #94a3b8; }
    .nav-item.active { color: #60a5fa; }

    .nav-icon {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
        transition: transform 0.2s;
    }
    .nav-item.active .nav-icon { transform: scale(1.1); }

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
