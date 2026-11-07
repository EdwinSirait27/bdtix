<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>403 - Leave</title>
    <link rel="icon" type="image/png" href="https://cloud.asianbay.co.id/index.php/s/CHRczpYqozMHjoD/download">

    {{-- Tailwind --}}
    @include('components.theme-init')
    @vite('resources/css/app.css')

    {{-- PWA --}}
</head>

<body class="bg-slate-950 text-gray-100">

    {{-- APP CONTAINER --}}
    {{-- <div class="max-w-md mx-auto min-h-screen bg-slate-900 flex flex-col"> --}}
        {{-- <div class="w-full max-w-3xl mx-auto min-h-screen bg-slate-900 flex flex-col"> --}}
<div class="min-h-screen bg-slate-900 flex flex-col 
        mx-auto w-full 
        max-w-md md:max-w-full
        px-4 md:px-0">

        {{-- Header --}}
        <header class="px-6 py-4 border-b border-slate-800">
            <div class="flex items-center space-x-3">
                {{-- <div class="w-10 h-10 rounded-[10px] flex items-center justify-center bg-slate-950 border border-slate-700"> --}}
                    {{-- <svg class="w-6 h-6 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg> --}}
                     <img src="https://cloud.asianbay.co.id/index.php/s/CHRczpYqozMHjoD/download"
                        class="w-15 h-15 select-none pointer-events-none" draggable="false" alt="icon">
                {{-- </div> --}}
                <div>
                    <h2 class="text-sm font-medium text-ink">BD Departments</h2>
                    <p class="text-xs text-slate-400 font-medium">Ticketing</p>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 flex items-center justify-center px-6 py-12">
            <div class="text-center space-y-6 w-full max-w-md mx-auto">

                {{-- Status code --}}
                <p class="font-mono tabular-nums text-7xl md:text-8xl leading-none text-ink" aria-hidden="true">403</p>

                {{-- Error Message --}}
                <div class="space-y-3 pt-6">
                    <h1 class="text-2xl font-medium text-ink">Why you visit this site?</h1>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm mx-auto">
                        Sorry, the page you are looking for is not for you. Make sure the URL you entered is correct.
                    </p>
                </div>

                {{-- Suggestions Card --}}
                <div class="bg-slate-800/50 border border-slate-700 rounded-[10px] p-5 text-left space-y-3">
                    <div class="flex items-center space-x-2 text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <h3 class="font-medium">Suggest for you</h3>
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
                    <a href="{{ route('dashboard') }}" class="block w-full py-3.5 font-medium rounded-[10px] transition-colors duration-300 bg-accent hover:bg-accent-2 text-accent-ink">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Back to Dashboard</span>
                        </div>
                    </a>

                    <button onclick="history.back()" class="block w-full py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 font-medium rounded-[10px] transition-colors duration-200">
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
                        <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

            </div>
        </main>

        {{-- Footer --}}
        <footer class="px-6 py-4 border-t border-slate-800 text-center">
            <p class="text-xs text-slate-500"> &copy; 2025 BD SerTix, Developed by Edwin Sirait</p>
            {{-- <p class="text-xs text-slate-500">Error Code: 404 | Page Not Found</p> --}}
        </footer>

    </div>

</body>
</html>