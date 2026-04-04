@extends('layouts.layout-theme-toggle')
@section('company', 'BD Departments')
@section('header', 'About Us')
@section('subtitle', 'Our Team')
@section('content')
    <div class="px-4 space-y-6 pb-8">
        <div
            class="relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700 shadow-xl overflow-hidden">
            {{-- Background Decoration --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-cyan-600/10 rounded-full blur-2xl"></div>

            <div class="relative text-center space-y-3">
                <div
                    class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center shadow-lg shadow-blue-500/30 mb-4">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white">{{ __('auth.itheader') }}</h2>
                <p class="text-slate-400 text-sm leading-relaxed max-w-sm mx-auto">
                    {{ __('auth.itheader2') }}
                </p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-4 border border-slate-700 text-center">
                <div class="text-2xl font-bold text-blue-400">4</div>
                <div class="text-xs text-slate-400 mt-1">Member</div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-4 border border-slate-700 text-center">
                <div class="text-2xl font-bold text-green-400">24/7</div>
                <div class="text-xs text-slate-400 mt-1">Support</div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-4 border border-slate-700 text-center">
                <div class="text-2xl font-bold text-cyan-400">∞</div>
                <div class="text-xs text-slate-400 mt-1">Tickets</div>
            </div>
        </div>
        <div class="flex items-center space-x-2 pt-2">
            <div class="h-6 w-1 bg-gradient-to-b from-blue-500 to-cyan-500 rounded-full"></div>
            <h3 class="text-lg font-bold text-white">Meet Our Team</h3>
        </div>
        <div class="space-y-4">

            {{-- Welky Setiawan --}}
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700 overflow-hidden hover:border-blue-500/50 transition-all duration-300 group">
                <div class="p-5">
                    <div class="flex items-center space-x-4">
                        <div class="relative flex-shrink-0">
                            <div
                                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 overflow-hidden ring-2 ring-blue-500/30 group-hover:ring-blue-500/60 transition-all">
                                <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-white">
                                    WS
                                </div>
                            </div>
                            <div
                                class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-3 border-slate-800 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-bold text-white group-hover:text-blue-400 transition-colors">Welky
                                Setiawan</h4>
                            <p class="text-sm text-blue-400 font-medium">IT Manager</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span
                                    class="px-2.5 py-1 bg-blue-500/20 text-blue-400 text-xs font-semibold rounded-lg border border-blue-500/30">
                                    Senior Manager
                                </span>
                                <span
                                    class="px-2.5 py-1 bg-green-500/20 text-green-400 text-xs font-semibold rounded-lg border border-green-500/30">
                                    Online
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-slate-600 group-hover:text-blue-400 group-hover:translate-x-1 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-700">
                        <p class="text-sm text-slate-400 leading-relaxed mb-3">
                            IT Manager specializing in network infrastructure and system security, with more than 8 years of
                            experience in enterprise IT support.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Network</span>
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Security</span>
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Cloud</span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center space-x-3">
                        <a href="mailto:it@mjm-bali.co.id"
                            class="flex-1 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Email</span>
                        </a>
                        <a href="tel:+6281234567890"
                            class="flex-1 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>Call</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Gede Arya --}}
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700 overflow-hidden hover:border-cyan-500/50 transition-all duration-300 group">
                <div class="p-5">
                    <div class="flex items-center space-x-4">
                        <div class="relative flex-shrink-0">
                            <div
                                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-cyan-600 to-cyan-800 overflow-hidden ring-2 ring-cyan-500/30 group-hover:ring-cyan-500/60 transition-all">
                                <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-white">
                                    GA
                                </div>
                            </div>
                            <div
                                class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-3 border-slate-800 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-bold text-white group-hover:text-cyan-400 transition-colors">Gede Arya
                            </h4>
                            <p class="text-sm text-cyan-400 font-medium">IT Support</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span
                                    class="px-2.5 py-1 bg-cyan-500/20 text-cyan-400 text-xs font-semibold rounded-lg border border-cyan-500/30">
                                    Assistant Manager
                                </span>
                                <span
                                    class="px-2.5 py-1 bg-green-500/20 text-green-400 text-xs font-semibold rounded-lg border border-green-500/30">
                                    Online
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-slate-600 group-hover:text-cyan-400 group-hover:translate-x-1 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-700">
                        <p class="text-sm text-slate-400 leading-relaxed mb-3">
                            IT Support Specialist with over 8 years of experience in network infrastructure management,
                            system security, and enterprise-level technical support.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Hardware</span>
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Helpdesk</span>
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Network</span>
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Desktop Support</span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center space-x-3">
                        <a href="mailto:it@mjm-bali.co.id"
                            class="flex-1 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Email</span>
                        </a>
                        <a href="tel:+6281234567891"
                            class="flex-1 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>Call</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Edwin Sirait --}}
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700 overflow-hidden hover:border-purple-500/50 transition-all duration-300 group">
                <div class="p-5">
                    <div class="flex items-center space-x-4">
                        <div class="relative flex-shrink-0">
                            <div
                                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-600 to-purple-800 overflow-hidden ring-2 ring-purple-500/30 group-hover:ring-purple-500/60 transition-all">
                                <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-white">
                                    ES
                                </div>
                            </div>
                            <div
                                class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-3 border-slate-800 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-bold text-white group-hover:text-purple-400 transition-colors">Edwin
                                Sirait</h4>
                            <p class="text-sm text-purple-400 font-medium">IT Developer</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span
                                    class="px-2.5 py-1 bg-purple-500/20 text-purple-400 text-xs font-semibold rounded-lg border border-purple-500/30">
                                    Supervisor
                                </span>
                                <span
                                    class="px-2.5 py-1 bg-green-500/20 text-green-400 text-xs font-semibold rounded-lg border border-green-500/30">
                                    Online
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-slate-600 group-hover:text-purple-400 group-hover:translate-x-1 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-700">
                        <p class="text-sm text-slate-400 leading-relaxed mb-3">
                            IT Developer with over 3 years of experience in web application development, backend
                            engineering, and database management. Skilled in building scalable systems, optimizing
                            application performance, and implementing secure, maintainable code for enterprise environments.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Dev Ops</span>
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Full Stack Dev</span>
                            <span class="px-3 py-1 bg-slate-700/100 text-slate-300 text-xs rounded-lg">Server Administrators</span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center space-x-3">
                        <a href="mailto:it@mjm-bali.co.id"
                            class="flex-1 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Email</span>
                        </a>
                        <a href="tel:+6281234567892"
                            class="flex-1 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>Call</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Jery Andrian - RED ✅ --}}
            <div
                class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700 overflow-hidden hover:border-red-500/50 transition-all duration-300 group">
                <div class="p-5">
                    <div class="flex items-center space-x-4">
                        <div class="relative flex-shrink-0">
                            <div
                                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-red-600 to-red-800 overflow-hidden ring-2 ring-red-500/30 group-hover:ring-red-500/60 transition-all">
                                <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-white">
                                    JA
                                </div>
                            </div>
                            <div
                                class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-3 border-slate-800 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-bold text-white group-hover:text-red-400 transition-colors">Jery Andrian</h4>
                            <p class="text-sm text-red-400 font-medium">IT Support</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span
                                    class="px-2.5 py-1 bg-red-500/20 text-red-400 text-xs font-semibold rounded-lg border border-red-500/30">
                                    Junior
                                </span>
                                <span
                                    class="px-2.5 py-1 bg-green-500/20 text-green-400 text-xs font-semibold rounded-lg border border-green-500/30">
                                    Online
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-slate-600 group-hover:text-red-400 group-hover:translate-x-1 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-700">
                        <p class="text-sm text-slate-400 leading-relaxed mb-3">
                            Junior IT Support with approximately 1 year of experience, focusing on network troubleshooting
                            and system development. Eager to grow and contribute to enterprise IT operations with a strong
                            foundation in both networking and development disciplines.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Network</span>
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Development</span>
                            <span class="px-3 py-1 bg-slate-700/50 text-slate-300 text-xs rounded-lg">Helpdesk</span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center space-x-3">
                        <a href="mailto:it@mjm-bali.co.id"
                            class="flex-1 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Email</span>
                        </a>
                        <a href="tel:+6281234567893"
                            class="flex-1 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-300 text-xs font-medium rounded-lg transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>Call</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <div
            class="bg-gradient-to-r from-blue-500/10 to-cyan-500/10 border border-blue-500/30 rounded-2xl p-5 text-center">
            <h3 class="text-lg font-bold text-white mb-2">{{ __('auth.help') }}</h3>
            <p class="text-sm text-slate-400 mb-4">{{ __('auth.help2') }}</p>
            <a href="{{ route('openticket') }}"
                class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Make new ticket</span>
            </a>
        </div>
    </div>
    <footer class="bg-dark dark:bg-slate-900 mt-auto">
        <div class="px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                {{-- Company Info --}}
                <div class="space-y-3">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('img/AsianBay.png') }}"
                            class="w-12 h-12 select-none pointer-events-none" draggable="false" alt="icon">
                        <h3 class="text-sm font-bold text-slate-900 text-white">{{ __('auth.departemen') }}
                        </h3>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400">
                        {{ __('auth.departemennote') }}
                    </p>
                </div>
            </div>
            {{-- Bottom Bar --}}
            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        © {{ date('Y') }} SerTix. Developed by Edwin Sirait
                    </p>
                </div>
            </div>
        </div>
    </footer>
@endsection