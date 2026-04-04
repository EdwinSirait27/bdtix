@extends('layouts.app')
@section('company', 'BD Departments')
@section('header', 'Profile')
@section('subtitle', 'your account information')
@section('content')
    <div class="px-4 space-y-6">
        <div
            class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-cyan-600/10 rounded-full blur-2xl"></div>

            <div class="relative flex items-center space-x-4">
                <div class="relative">
                    <div
                        class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-600 shadow-lg shadow-blue-500/30 flex items-center justify-center text-2xl font-bold text-white">
                        {{ strtoupper(substr(Auth::user()->employee->employee_name ?? 'U', 0, 2)) }}
                    </div>
                    <div
                        class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-3 border-slate-900 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-white">{{ Auth::user()->Employee->employee_name ?? 'User Name' }}</h2>
                    <p class="text-sm text-slate-400 mt-0.5">Position :
                        {{ Auth::user()->Employee->position->name ?? 'Edwgans' }}</p>
                    <div class="flex items-center space-x-2 mt-2">
                        <span
                            class="px-3 py-1 bg-blue-500/20 text-blue-400 text-xs font-semibold rounded-lg border border-blue-500/30">
                            {{ Auth::user()->getRoleNames()->join(', ') }}
                        </span>
                        <span
                            class="px-3 py-1 bg-green-500/20 text-green-400 text-xs font-semibold rounded-lg border border-green-500/30">
                            Active
                        </span>
                    </div>
                </div>
                </a>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            @role('executor')
                <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-4 border border-slate-700">
                    <div class="text-2xl font-bold text-white">{{ $handled ?? 0 }}</div>
                    <div class="text-xs text-slate-400 mt-1">Tickets handled by you</div>
                </div>
            @endrole
            @role('human')
                <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-4 border border-slate-700">
                    <div class="text-2xl font-bold text-white">{{ $allticket ?? 0 }}</div>
                    <div class="text-xs text-slate-400 mt-1">Total Tickets</div>
                </div>
            @endrole
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-4 border border-slate-700">
                <div class="text-2xl font-bold text-blue-400">{{ $overdueticket ?? 0 }}</div>
                <div class="text-xs text-slate-400 mt-1">Overdue's Ticket</div>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-4 border border-slate-700">
                <div class="text-2xl font-bold text-green-400">{{ $openticket ?? 0 }}</div>
                <div class="text-xs text-slate-400 mt-1">Unchecked Tickets</div>
            </div>
        </div>
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
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
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
            </div>
        </div>
      <form method="POST" action="{{ route('profile.update-active-role') }}">
    @csrf
    <label for="active_role_bdtix" class="text-sm text-white">Select Active Role</label>
    <select name="role" id="active_role_bdtix" class="mt-1 block w-full rounded-lg bg-slate-800 text-white p-2 border border-slate-700">
        @foreach ($user->all_roles_bdtix ?? [] as $role)
            <option value="{{ $role }}" {{ $user->active_role_bdtix == $role ? 'selected' : '' }}>
                {{ ucfirst($role) }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
        Update Active Role
    </button>
</form>
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
    </div>
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
@endsection
