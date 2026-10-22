<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EduNex') · EduNex</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="flex items-center gap-3" aria-label="EduNex home">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-600 text-lg font-black text-white">E</span>
                <span><span class="block text-lg font-bold tracking-tight text-slate-950">EduNex</span><span class="hidden text-xs text-slate-500 sm:block">Smart companion for academic success</span></span>
            </a>
            @auth
                <div class="flex items-center gap-3">
                    <span class="hidden max-w-32 truncate text-sm font-semibold text-slate-700 sm:block">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">@csrf<button class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold hover:bg-slate-50">Log out</button></form>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">Create account</a>
                </div>
            @endauth
        </div>
        @auth
            <nav class="flex gap-4 overflow-x-auto border-t border-slate-100 px-4 py-2 text-sm font-medium text-slate-600 md:hidden" aria-label="Mobile navigation">
                <a class="whitespace-nowrap hover:text-indigo-700" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="whitespace-nowrap hover:text-indigo-700" href="{{ route('tasks.index') }}">Tasks</a>
                <a class="whitespace-nowrap hover:text-indigo-700" href="{{ route('exams.index') }}">Exams</a>
                <a class="whitespace-nowrap hover:text-indigo-700" href="{{ route('study-sessions.index') }}">Study log</a>
            </nav>
        @endauth
    </header>

    <main class="mx-auto min-h-[calc(100vh-140px)] max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @if (session('success'))
            <div role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
        @endif
        @if (session('status'))
            <div role="status" class="mb-5 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-medium text-sky-800">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="mb-1 font-semibold">Please check the form:</p>
                <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        @auth
            <div class="flex items-start gap-8">
                <aside class="sticky top-28 hidden w-56 shrink-0 lg:block" aria-label="Sidebar navigation">
                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                        <p class="px-3 pb-3 pt-2 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Your workspace</p>
                        <nav class="space-y-1 text-sm font-semibold" aria-label="Sidebar">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}"><span>▦</span> Dashboard</a>
                            <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('tasks.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}"><span>✓</span> Tasks</a>
                            <a href="{{ route('exams.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('exams.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}"><span>▤</span> Exams</a>
                            <a href="{{ route('study-sessions.index')}}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 {{ request()->routeIs('study-sessions.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}"><span>◷</span> Study log</a>
                        </nav>
                        <div class="mt-4 border-t border-slate-100 px-3 pb-2 pt-4">
                            <p class="text-xs leading-5 text-slate-500">Progress comes from small, consistent steps.</p>
                            <a href="{{ route('tasks.create') }}" class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-3 py-2.5 text-xs font-bold text-white hover:bg-indigo-700">+ Add a task</a>
                        </div>
                    </div>
                </aside>
                <section class="min-w-0 flex-1">@yield('content')</section>
            </div>
        @else
            @yield('content')
        @endauth
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-1 px-4 py-5 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p>© {{ now()->year }} EduNex · Plan with purpose. Study with focus.</p>
            <p>Your tasks, exams, and study history stay private to your account.</p>
        </div>
    </footer>
</body>
</html>
