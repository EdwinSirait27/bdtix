@extends('layouts.app')
@section('company', 'IT Departments')
@section('header', 'Profile')
@section('subtitle', 'your account information')
@section('content')
    <div class="px-4 space-y-6">
        {{-- Profile Header Card --}}
        <div
            class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700 shadow-xl relative overflow-hidden">
            {{-- Background Decoration --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-cyan-600/10 rounded-full blur-2xl"></div>

            <div class="relative flex items-center space-x-4">
                {{-- Avatar --}}
                <div class="relative">
                    <div
                        class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-600 shadow-lg shadow-blue-500/30 flex items-center justify-center text-2xl font-bold text-white">
                        {{ strtoupper(substr(Auth::user()->Employee->employee_name ?? 'U', 0, 2)) }}
                    </div>
                    <div
                        class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-3 border-slate-900 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                {{-- User Info --}}
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-white">{{ Auth::user()->Employee->employee_name ?? 'User Name' }}</h2>
                    <p class="text-sm text-slate-400 mt-0.5">Position :
                        {{ Auth::user()->Employee->position->name ?? 'user@company.com' }}</p>
                    <div class="flex items-center space-x-2 mt-2">
                        <span
                            class="px-3 py-1 bg-blue-500/20 text-blue-400 text-xs font-semibold rounded-lg border border-blue-500/30">
                            {{ Auth::user()->role ?? 'User' }}
                        </span>
                        <span
                            class="px-3 py-1 bg-green-500/20 text-green-400 text-xs font-semibold rounded-lg border border-green-500/30">
                            Active
                        </span>
                    </div>
                </div>
                {{-- Edit Button --}}
              
            </a> 
            </div>
        </div>
        {{-- Stats Cards --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-4 border border-slate-700">
                <div class="text-2xl font-bold text-white">{{$allticket ?? 0}}</div>
                <div class="text-xs text-slate-400 mt-1">Total Tickets</div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-4 border border-slate-700">
                <div class="text-2xl font-bold text-blue-400">{{$overdueticket ?? 0}}</div>
                <div class="text-xs text-slate-400 mt-1">Overdue's Ticket</div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-4 border border-slate-700">
                <div class="text-2xl font-bold text-green-400">{{$openticket ?? 0}}</div>
                <div class="text-xs text-slate-400 mt-1">Unchecked Tickets</div>
            </div>
        </div>

        {{-- Account Information --}}
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-700">
                <h3 class="font-semibold text-white flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Account Information</span>
                </h3>
            </div>
            <div class="divide-y divide-slate-700">


                {{-- Email --}}
                <a href="#"
                    class="flex items-center justify-between px-5 py-4 hover:bg-slate-700/30 transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-700/50 flex items-center justify-center">

                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 21h18M5 21V7a2 2 0 012-2h2a2 2 0 012 2v14M11 21V11a2 2 0 012-2h2a2 2 0 012 2v10M7 10h1m-1 4h1m4-4h1m-1 4h1" />
                            </svg>

                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Company</div>
                            <div class="text-sm text-white font-medium">
                                {{ Auth::user()->employee->company->name ?? ' +62 812-3456-9999' }}</div>
                        </div>
                    </div>

                </a>
                <a href="#"
                    class="flex items-center justify-between px-5 py-4 hover:bg-slate-700/30 transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-700/50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Department</div>
                            <div class="text-sm text-white font-medium">
                                {{ Auth::user()->employee->department->department_name ?? ' +62 812-3456-9999' }}</div>
                        </div>
                    </div>

                </a>
                <a href="#"
                    class="flex items-center justify-between px-5 py-4 hover:bg-slate-700/30 transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-700/50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11a4 4 0 100-8 4 4 0 000 8zm0 0c-4.418 0-8 2.239-8 5v2h16v-2c0-2.761-3.582-5-8-5z" />
                            </svg>

                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Location</div>
                            <div class="text-sm text-white font-medium">
                                {{ Auth::user()->employee->store->name ?? ' +62 812-3456-9999' }}</div>
                        </div>
                    </div>

                </a>
                <a href="#"
                    class="flex items-center justify-between px-5 py-4 hover:bg-slate-700/30 transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-700/50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Email</div>
                            <div class="text-sm text-white font-medium">
                                {{ Auth::user()->employee->email ?? 'user@company.com' }}</div>
                        </div>
                    </div>

                </a>
                <a href="#"
                    class="flex items-center justify-between px-5 py-4 hover:bg-slate-700/30 transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-700/50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <line x1="2" y1="10" x2="22" y2="10" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <line x1="7" y1="15" x2="11" y2="15" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                        <div>
                            <div class="text-xs text-slate-500">NIP</div>
                            <div class="text-sm text-white font-medium">
                                {{ Auth::user()->employee->employee_pengenal ?? 'user@company.com' }}</div>
                        </div>
                    </div>

                </a>

                {{-- Phone --}}
                <a href="#"
                    class="flex items-center justify-between px-5 py-4 hover:bg-slate-700/30 transition-colors group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-700/50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-slate-500">Phone Number</div>
                            <div class="text-sm text-white font-medium">
                                {{ Auth::user()->employee->telp_number ?? ' +62 812-3456-9999' }}</div>
                        </div>
                    </div>

                </a>

                {{-- Department --}}


            </div>
        </div>



        {{-- Logout Button --}}
        <form method="POST" action="{{ route('logout.post') }}">
            @csrf
            <button type="submit"
                class="w-full bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 font-semibold py-4 rounded-2xl transition-all duration-300 flex items-center justify-center space-x-2 group">
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </button>
        </form>

        {{-- App Version --}}
        {{-- <div class="text-center pb-4">
            <p class="text-xs text-slate-500">Ticketing v.1.0</p>
            <p class="text-xs text-slate-600 mt-1">&copy; 2025 IT Departments, Developed by Edwin Sirait</p>
        </div> --}}

    </div>
@endsection
{{-- Settings Menu --}}
{{-- <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-700">
            <h3 class="font-semibold text-white flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Pengaturan</span>
            </h3>
        </div>
        
        <div class="divide-y divide-slate-700">
            <a href="#" class="flex items-center justify-between px-5 py-4 hover:bg-slate-700/30 transition-colors group">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-700/50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="text-sm text-white font-medium">Ubah Password</div>
                </div>
                <svg class="w-5 h-5 text-slate-500 group-hover:text-slate-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="#" class="flex items-center justify-between px-5 py-4 hover:bg-slate-700/30 transition-colors group">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-700/50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="text-sm text-white font-medium">Notifikasi</div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-slate-500">Aktif</span>
                    <svg class="w-5 h-5 text-slate-500 group-hover:text-slate-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="#" class="flex items-center justify-between px-5 py-4 hover:bg-slate-700/30 transition-colors group">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-700/50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                    </div>
                    <div class="text-sm text-white font-medium">Bahasa</div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs text-slate-500">Indonesia</span>
                    <svg class="w-5 h-5 text-slate-500 group-hover:text-slate-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="#" class="flex items-center justify-between px-5 py-4 hover:bg-slate-700/30 transition-colors group">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-700/50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-sm text-white font-medium">Bantuan & Dukungan</div>
                </div>
                <svg class="w-5 h-5 text-slate-500 group-hover:text-slate-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div> --}}
