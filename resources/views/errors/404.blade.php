<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>404 - Page Not Found</title>
    <link rel="icon" type="image/png" href="https://cloud.mjm-bali.co.id/index.php/s/CHRczpYqozMHjoD/download">

    {{-- Tailwind --}}
    @vite('resources/css/app.css')

    {{-- PWA --}}
    <meta name="theme-color" content="#0F172A">
</head>

<body class="bg-slate-950 text-gray-100">

    {{-- APP CONTAINER --}}
    {{-- <div class="max-w-md mx-auto min-h-screen bg-slate-900 shadow-2xl flex flex-col"> --}}
        {{-- <div class="w-full max-w-3xl mx-auto min-h-screen bg-slate-900 shadow-2xl flex flex-col"> --}}
<div class="min-h-screen bg-slate-900 flex flex-col 
        mx-auto w-full 
        max-w-md md:max-w-full
        px-4 md:px-0">

        {{-- Header --}}
        <header class="px-6 py-4 border-b border-slate-800">
            <div class="flex items-center space-x-3">
                {{-- <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center shadow-lg shadow-blue-500/30"> --}}
                    {{-- <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg> --}}
                                          <img src="https://cloud.mjm-bali.co.id/index.php/s/fMMRXmq5cdkApNc/download"

                        class="w-15 h-15 select-none pointer-events-none" draggable="false" alt="icon">
                {{-- </div> --}}
                <div>
                    <h2 class="text-sm font-bold text-white">IT Departments</h2>
                    <p class="text-xs text-slate-400 font-medium">Ticketing</p>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 flex items-center justify-center px-6 py-12">
            <div class="text-center space-y-6 w-full">

                {{-- 404 Illustration --}}
                <div class="relative">
                    {{-- Animated Background Elements --}}
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-blue-600/5 rounded-full blur-3xl animate-pulse-slow"></div>
                    
                    {{-- 404 Number with Glitch Effect --}}
                    <div class="relative">
                        <div class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-br from-blue-500 via-cyan-500 to-blue-600 leading-none animate-glitch">
                            404
                        </div>
                        <div class="absolute inset-0 text-9xl font-black text-blue-500/20 leading-none" style="transform: translate(-2px, -2px);">
                            404
                        </div>
                        <div class="absolute inset-0 text-9xl font-black text-cyan-500/20 leading-none" style="transform: translate(2px, 2px);">
                            404
                        </div>
                    </div>

                    {{-- Floating Icons --}}
                    <div class="absolute top-0 left-1/4 animate-float">
                        <div class="w-12 h-12 rounded-xl bg-slate-800/50 border border-slate-700 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute top-8 right-1/4 animate-float-delayed">
                        <div class="w-10 h-10 rounded-lg bg-slate-800/50 border border-slate-700 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Error Message --}}
                <div class="space-y-3 pt-6">
                    <h1 class="text-2xl font-bold text-white">Page Not Found</h1>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm mx-auto">
                        Sorry, the page you are looking for does not exist or has been moved. Make sure the URL you entered is correct.
                    </p>
                </div>

                {{-- Suggestions Card --}}
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-5 text-left space-y-3 backdrop-blur-sm">
                    <div class="flex items-center space-x-2 text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <h3 class="font-semibold">Suggest for you</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li class="flex items-start space-x-2">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Double check the URL you entered</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Return to previous page</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Contact the support team if the problem persists</span>
                        </li>
                    </ul>
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-3 pt-4">
                    <a href="{{ route('dashboard') }}" class="block w-full py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Back to Dashboard</span>
                        </div>
                    </a>

                    <button onclick="history.back()" class="block w-full py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-semibold rounded-xl transition-all duration-200">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Previous Page</span>
                        </div>
                    </button>
                </div>

                {{-- Contact Support --}}
                <div class="pt-6">
                    <p class="text-xs text-slate-500 mb-3">Need Help?</p>
                    <a href="mailto:it@mjm-bali.co.id" class="inline-flex items-center space-x-2 text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium group">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Contact IT Departments</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

            </div>
        </main>

        {{-- Footer --}}
        <footer class="px-6 py-4 border-t border-slate-800 text-center">
            <p class="text-xs text-slate-600"> &copy; 2025 SerTix, Developed by Edwin Sirait</p>

            {{-- <p class="text-xs text-slate-600">Error Code: 404 | Page Not Found</p> --}}
        </footer>

    </div>

    {{-- Background Decoration --}}
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-900/10 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-900/10 rounded-full filter blur-3xl"></div>
    </div>

    {{-- Grid Pattern Overlay --}}
    <div class="fixed inset-0 -z-10 opacity-[0.02] pointer-events-none" style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 50px 50px;"></div>

    <style>
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        .animate-float-delayed {
            animation: float 3s ease-in-out infinite 1.5s;
        }

        @keyframes glitch {
            0%, 100% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
        }
        .animate-glitch {
            animation: glitch 0.3s ease-in-out infinite;
            animation-iteration-count: 1;
        }
        .animate-glitch:hover {
            animation-iteration-count: infinite;
        }
    </style>

</body>
</html>