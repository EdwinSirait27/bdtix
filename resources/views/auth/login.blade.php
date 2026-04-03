<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>BD Departments Ticketing</title>
    <link rel="icon" type="image/png" href="{{ asset('img/AsianBay logomark.ico') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="theme-color" content="#0F172A">
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-slate-950 text-gray-100">
    <div
        class="min-h-screen bg-slate-900 flex flex-col 
        mx-auto w-full 
        max-w-md md:max-w-full
        px-4 md:px-0">
        <div
            class="relative px-6 pt-12 pb-8 bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 border-b border-slate-800">

            <div class="absolute top-3 right-3 flex items-center space-x-2">
                {{-- <a href="{{ route('about') }}"
                    class="text-slate-300 hover:text-white text-sm font-medium transition-colors">
                    About
                </a> --}}
                <a href="{{ route('lang.switch', 'id') }}"
                    class="text-slate-300 hover:text-white text-sm font-medium transition-colors">
                    Indonesia
                </a>
                <a href="{{ route('lang.switch', 'en') }}"
                    class="text-slate-300 hover:text-white text-sm font-medium transition-colors">
                    English
                </a>
            </div>
            <div class="flex flex-col items-center space-y--1">
                <div class="relative">
                    <img src="{{ asset('img/AsianBay.png') }}" class="w-40 h-40 select-none pointer-events-none"
                        draggable="false" alt="icon">
                </div>

                <div class="text-center">
                    <h1 class="text-2xl font-bold text-white tracking-tight">{{ __('auth.it') }}</h1>
                    <p class="text-sm text-slate-400 font-medium mt-1">{{ __('auth.tiket') }}</p>
                </div>
            </div>

            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-cyan-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="flex-1 px-6 py-8 md:flex md:items-center md:justify-center">
            <div class="w-full md:max-w-md">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-white mb-2">{{ __('auth.welcome') }}</h2>
                    <p class="text-sm text-slate-400">{{ __('auth.login_desc') }}</p>
                </div>

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="block text-sm font-medium text-slate-300 mb-2">
                            Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" id="username" name="username" required
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="Insert your NIP or Username">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                            Password
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>

                            <input type="password" id="password" name="password" required
                                class="w-full pl-12 pr-12 py-3.5 bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                placeholder="********">

                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500">
                                <svg id="iconShow" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                                </svg>

                                <svg id="iconHide" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.971 9.971 0 012.241-3.592M6.1 6.1A9.97 9.97 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.98 9.98 0 01-1.228 2.592M15 12a3 3 0 00-4.243-2.829M9.88 9.88A3 3 0 0012 15c.395 0 .77-.077 1.118-.218M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-2">
                        <span>Login</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-800"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <footer class="bg-dark dark:bg-slate-900 mt-auto">
            <div class="px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('img/AsianBay.png') }}"
                                class="w-10 h-10 select-none pointer-events-none" draggable="false" alt="icon">
                            <h3 class="text-sm font-bold text-slate-900 text-white">{{ __('auth.departemen') }}
                            </h3>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400">
                            {{ __('auth.departemennote') }}
                        </p>
                    </div>


                </div>

                <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800">
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            © {{ date('Y') }} BDSerTix. Developed by Edwin Sirait
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-900/20 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-900/20 rounded-full filter blur-3xl"></div>
    </div>
    <div class="fixed inset-0 -z-10 opacity-[0.02] pointer-events-none"
        style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 50px 50px;">
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const input = document.getElementById('password');
            const iconShow = document.getElementById('iconShow');
            const iconHide = document.getElementById('iconHide');
            if (input.type === "password") {
                input.type = "text";
                iconShow.classList.add('hidden');
                iconHide.classList.remove('hidden');
            } else {
                input.type = "password";
                iconShow.classList.remove('hidden');
                iconHide.classList.add('hidden');
            }
        });
    </script>
    <style>
        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
</body>

</html>