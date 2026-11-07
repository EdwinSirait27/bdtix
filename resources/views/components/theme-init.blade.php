{{-- Hallmark · colour mode: the saved choice, else the system setting. Runs before paint. --}}
<meta name="theme-color" media="(prefers-color-scheme: light)" content="#f7f6f3">
<meta name="theme-color" media="(prefers-color-scheme: dark)" content="#1a1c1f">
<script>
    (function () {
        var saved = null;
        try { saved = localStorage.getItem('bdtix-theme'); } catch (e) {}
        var dark = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.classList.toggle('dark', dark);
        document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
        window.bdtixToggleTheme = function () {
            var next = !document.documentElement.classList.contains('dark');
            document.documentElement.classList.toggle('dark', next);
            document.documentElement.style.colorScheme = next ? 'dark' : 'light';
            try { localStorage.setItem('bdtix-theme', next ? 'dark' : 'light'); } catch (e) {}
            document.querySelectorAll('[data-theme-toggle]').forEach(function (b) {
                b.setAttribute('aria-pressed', next ? 'true' : 'false');
            });
        };
    })();
</script>
