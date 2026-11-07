<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>BD Departments Ticketing</title>
    {{-- <link rel="icon" type="image/x-icon" href="{{ asset('img/AsianBaylogomark.ico') }}"> --}}
        <link rel="icon" type="image/png" href="{{ Storage::disk('s3')->url('logos/asianbay_logomark.ico') }}">
    @include('components.theme-init')
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        :where(.card *:not(.theme-toggle)) { margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background: var(--color-paper-3);
            color: var(--color-ink);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }

        .card {
            width: 100%;
            max-width: 380px;
        }

        .top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2.5rem;
        }

        .logo {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--color-paper-2);
            border: 1px solid var(--color-rule);
            flex-shrink: 0;
        }

        .logo img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            pointer-events: none;
            user-select: none;
        }

        .brand-name {
            font-size: .95rem;
            font-weight: 500;
            color: var(--color-ink);
        }

        .brand-sub {
            font-size: .72rem;
            color: var(--color-muted);
            margin-top: 1px;
        }

        .heading {
            font-size: 1.4rem;
            font-weight: 500;
            color: var(--color-ink);
            letter-spacing: -.02em;
            margin-bottom: .4rem;
        }

        .subheading {
            font-size: .9rem;
            color: var(--color-muted);
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .label {
            display: block;
            font-size: .8rem;
            font-weight: 500;
            color: var(--color-ink-2);
            margin-bottom: .45rem;
        }

        .input-wrap {
            position: relative;
            margin-bottom: 1.1rem;
        }

        .input {
            width: 100%;
            padding: .7rem .875rem .7rem 2.75rem;
            background: var(--color-paper-2);
            border: 1px solid var(--color-rule);
            border-radius: var(--radius-pill);
            color: var(--color-ink);
            font-size: 1rem;
            font-family: var(--font-body);
            min-height: 2.75rem;
            transition: border-color var(--dur-short) var(--ease-out);
        }

        .input:hover { border-color: var(--color-rule-2); }
        .input:focus { border-color: var(--color-accent); outline: none; }
        .input:focus-visible { outline: 2px solid var(--color-focus); outline-offset: 1px; }

        .input::placeholder {
            color: var(--color-muted-2);
        }

        .field-icon {
            position: absolute;
            left: .8rem;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            stroke: var(--color-muted);
            fill: none;
            pointer-events: none;
        }

        .eye-btn {
            position: absolute;
            right: .8rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            padding: 0.25rem;
            border-radius: 4px;
        }
        .eye-btn:hover svg { stroke: var(--color-ink); }
        .eye-btn:focus-visible { outline: 2px solid var(--color-focus); outline-offset: 1px; }

        .eye-btn svg {
            width: 16px;
            height: 16px;
            stroke: var(--color-muted);
            fill: none;
        }

        .btn {
            width: 100%;
            margin-top: .25rem;
            padding: .75rem;
            background: var(--color-accent);
            border: none;
            border-radius: var(--radius-pill);
            color: var(--color-accent-ink);
            font-family: var(--font-body);
            font-size: .875rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 2.75rem;
            transition: background-color var(--dur-short) var(--ease-out);
        }

        .btn:hover { background: var(--color-accent-2); }
        .btn:active { background: var(--color-accent-2); transform: translateY(1px); }
        .btn:focus-visible { outline: 2px solid var(--color-focus); outline-offset: 2px; }

        .btn svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
        }

        .footer-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--color-rule);
        }

        .flink {
            font-size: .8rem;
            color: var(--color-muted);
            text-decoration: underline;
            text-decoration-color: var(--color-rule-2);
            text-underline-offset: .2em;
            transition: color var(--dur-short) var(--ease-out);
        }

        .flink:hover { color: var(--color-accent-text); }
        .flink:focus-visible { outline: 2px solid var(--color-focus); outline-offset: 2px; }

        .lang { display: flex; gap: 12px; align-items: center; }
    </style>
</head>
<body>

<div class="card">
    <div class="top">
        <div class="logo">
            <img src="{{ Storage::disk('s3')->url('logos/asianbay.png') }}" draggable="false" alt="AsianBay">
        </div>
        <div>
            <div class="brand-name">{{ __('auth.it') }}</div>
            <div class="brand-sub">{{ __('auth.tiket') }}</div>
        </div>
    </div>

    <h1 class="heading">{{ __('auth.welcome') }}</h1>
    <p class="subheading">{{ __('auth.login_desc') }}</p>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <label class="label" for="username">Username</label>
        <div class="input-wrap">
            <svg class="field-icon" viewBox="0 0 24 24" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <input class="input" type="text" id="username" name="username" required placeholder="NIP atau username">
        </div>

        <label class="label" for="password">Password</label>
        <div class="input-wrap">
            <svg class="field-icon" viewBox="0 0 24 24" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <input class="input" type="password" id="password" name="password" required placeholder="••••••••" style="padding-right:2.5rem;">
            <button type="button" id="togglePassword" class="eye-btn">
                <svg id="iconShow" viewBox="0 0 24 24" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg id="iconHide" viewBox="0 0 24 24" stroke-width="1.75" style="display:none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.971 9.971 0 012.241-3.592M6.1 6.1A9.97 9.97 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.98 9.98 0 01-1.228 2.592M15 12a3 3 0 00-4.243-2.829M3 3l18 18"/>
                </svg>
            </button>
        </div>

        <button type="submit" class="btn">
            Login
            <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </button>
    </form>

    <div class="footer-links">
        {{-- <a href="{{ route('about') }}" class="flink">About</a> --}}
        @include('components.theme-toggle')
        <div class="lang">
            <a href="{{ route('lang.switch', 'id') }}" class="flink">Indonesia</a>
            <a href="{{ route('lang.switch', 'en') }}" class="flink">English</a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        var input = document.getElementById('password');
        var show  = document.getElementById('iconShow');
        var hide  = document.getElementById('iconHide');
        if (input.type === 'password') {
            input.type = 'text';
            show.style.display = 'none';
            hide.style.display = 'block';
        } else {
            input.type = 'password';
            show.style.display = 'block';
            hide.style.display = 'none';
        }
    });

    toastr.options = { closeButton: true, progressBar: true, positionClass: "toast-top-right", timeOut: "3000" };
    @if (session('success'))
        toastr.success(@json(session('success')));
    @endif
    @if (session('error'))
        toastr.error(@json(session('error')));
    @endif
</script>
</body>
</html>