@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="space-y-7">
    <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div><p class="text-sm font-semibold uppercase tracking-[0.18em] text-indigo-600">Your study space</p><h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Hello, {{ auth()->user()->name }} 👋</h1><p class="mt-2 text-slate-500">A little progress each day adds up. Here’s your overview.</p></div>
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">+ Add a task</a>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Study statistics">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm font-medium text-slate-500">Tasks completed</p><div class="mt-3 flex items-end justify-between"><p class="text-3xl font-bold text-slate-950">{{ $completedTasks }}<span class="text-lg font-medium text-slate-400"> / {{ $totalTasks }}</span></p><span class="rounded-lg bg-emerald-50 px-2.5 py-1 text-sm font-bold text-emerald-700">{{ $completionPercent }}%</span></div><div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-emerald-500" style="width: {{ $completionPercent }}%"></div></div></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm font-medium text-slate-500">Study time · last 7 days</p><p class="mt-3 text-3xl font-bold text-slate-950">{{ intdiv($totalStudyMinutesThisWeek, 60) }}<span class="text-lg font-medium text-slate-400">h</span> {{ $totalStudyMinutesThisWeek % 60 }}<span class="text-lg font-medium text-slate-400">m</span></p><p class="mt-2 text-sm text-slate-500">Build a routine, one session at a time.</p></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm font-medium text-slate-500">Exams in your planner</p><p class="mt-3 text-3xl font-bold text-slate-950">{{ $totalExams }}</p><a href="{{ route('exams.index') }}" class="mt-2 inline-block text-sm font-semibold text-indigo-700 hover:underline">Manage exams →</a></div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm font-medium text-slate-500">Study sessions logged</p><p class="mt-3 text-3xl font-bold text-slate-950">{{ $totalSessions }}</p><a href="{{ route('study-sessions.index') }}" class="mt-2 inline-block text-sm font-semibold text-indigo-700 hover:underline">View study log →</a></div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-center justify-between gap-3"><div><h2 class="text-lg font-bold text-slate-950">Your tasks</h2><p class="mt-1 text-sm text-slate-500">Keep the next step small and achievable.</p></div><a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-indigo-700 hover:underline">View all</a></div>
            <div class="mt-5 divide-y divide-slate-100">
                @forelse ($tasks as $task)
                    <div class="flex items-start gap-3 py-4 first:pt-0 last:pb-0">
                        <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="pt-0.5">@csrf @method('PATCH')<button aria-label="{{ $task->status === 'completed' ? 'Reopen' : 'Complete' }} {{ $task->title }}" class="flex h-5 w-5 items-center justify-center rounded-full border {{ $task->status === 'completed' ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 hover:border-indigo-500' }}">@if($task->status === 'completed')<span class="text-xs">✓</span>@endif</button></form>
                        <div class="min-w-0 flex-1"><p class="font-semibold {{ $task->status === 'completed' ? 'text-slate-400 line-through' : 'text-slate-800' }}">{{ $task->title }}</p>@if($task->due_date)<p class="mt-1 text-xs text-slate-500">Due {{ $task->due_date->format('M j, Y · g:i A') }}</p>@endif</div>
                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $task->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : ($task->status === 'in_progress' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600') }}">{{ str_replace('_', ' ', ucfirst($task->status)) }}</span>
                    </div>
                @empty
                    <div class="rounded-xl bg-slate-50 px-4 py-8 text-center"><p class="font-semibold text-slate-800">Your task list is clear.</p><p class="mt-1 text-sm text-slate-500">Add a task to give your next study session direction.</p><a href="{{ route('tasks.create') }}" class="mt-3 inline-block text-sm font-semibold text-indigo-700 hover:underline">Create your first task →</a></div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-center justify-between"><div><p class="text-sm font-semibold text-indigo-600">Countdown</p><h2 class="mt-1 text-lg font-bold text-slate-950">Next exam</h2></div><span class="text-2xl">📚</span></div>
                @if($upcomingExam)
                    <p class="mt-4 text-xl font-bold text-slate-900">{{ $upcomingExam->subject }}</p><p class="mt-1 text-sm text-slate-500">{{ $upcomingExam->exam_date->format('D, M j, Y · g:i A') }}</p>
                    <div x-data="examCountdown(@js($upcomingExam->exam_date->toIso8601String()))" class="mt-5 grid grid-cols-4 gap-2 text-center" aria-live="polite">
                        <div class="rounded-xl bg-indigo-50 p-3"><p x-text="days" class="text-xl font-bold text-indigo-800">—</p><p class="mt-1 text-[11px] uppercase tracking-wide text-indigo-500">Days</p></div>
                        <div class="rounded-xl bg-indigo-50 p-3"><p x-text="hours" class="text-xl font-bold text-indigo-800">—</p><p class="mt-1 text-[11px] uppercase tracking-wide text-indigo-500">Hours</p></div>
                        <div class="rounded-xl bg-indigo-50 p-3"><p x-text="minutes" class="text-xl font-bold text-indigo-800">—</p><p class="mt-1 text-[11px] uppercase tracking-wide text-indigo-500">Mins</p></div>
                        <div class="rounded-xl bg-indigo-50 p-3"><p x-text="seconds" class="text-xl font-bold text-indigo-800">—</p><p class="mt-1 text-[11px] uppercase tracking-wide text-indigo-500">Secs</p></div>
                    </div>
                    <a href="{{ route('exams.index') }}" class="mt-4 inline-block text-sm font-semibold text-indigo-700 hover:underline">Manage exam dates →</a>
                @else
                    <p class="mt-4 text-sm leading-6 text-slate-500">No upcoming exams yet. Add the next date and EduNex will count it down for you.</p><a href="{{ route('exams.create') }}" class="mt-3 inline-block text-sm font-semibold text-indigo-700 hover:underline">Add an exam →</a>
                @endif
            </div>

            <div x-data="pomodoroTimer" class="rounded-2xl bg-slate-950 p-5 text-white shadow-sm sm:p-6">
                <div class="flex items-start justify-between"><div><p class="text-sm font-semibold text-indigo-300">Focus mode</p><h2 class="mt-1 text-lg font-bold">Pomodoro timer</h2></div><span class="text-2xl">⏱️</span></div>
                <div class="mt-5 flex justify-center"><div class="flex h-40 w-40 items-center justify-center rounded-full border-[7px] border-indigo-500/70 bg-slate-900 shadow-[0_0_40px_rgba(99,102,241,0.18)]"><span x-text="formattedTime" class="font-mono text-4xl font-bold tracking-tight" aria-live="polite">25:00</span></div></div>
                <div class="mt-5 flex justify-center gap-2"><button @click="chooseDuration(25)" :disabled="running || saving" :class="selectedMinutes === 25 ? 'bg-indigo-500 text-white' : 'bg-slate-800 text-slate-300'" class="rounded-lg px-3 py-2 text-xs font-semibold disabled:opacity-50">25 min</button><button @click="chooseDuration(5)" :disabled="running || saving" :class="selectedMinutes === 5 ? 'bg-indigo-500 text-white' : 'bg-slate-800 text-slate-300'" class="rounded-lg px-3 py-2 text-xs font-semibold disabled:opacity-50">5 min test</button></div>
                <div class="mt-4 flex gap-2"><button @click="start()" :disabled="running || saving" class="flex-1 rounded-xl bg-indigo-500 px-3 py-3 text-sm font-bold hover:bg-indigo-400 disabled:opacity-40">Start focus</button><button @click="pause()" :disabled="!running" class="rounded-xl bg-slate-800 px-3 py-3 text-sm font-semibold hover:bg-slate-700 disabled:opacity-40">Pause</button><button @click="reset()" :disabled="saving" class="rounded-xl bg-slate-800 px-3 py-3 text-sm font-semibold hover:bg-slate-700 disabled:opacity-40">Reset</button></div>
                <p x-show="message" x-text="message" class="mt-3 text-sm text-indigo-200" role="status"></p><button x-show="canRetrySave" @click="saveCompletedSession()" class="mt-2 text-sm font-semibold text-white underline">Retry saving session</button>
                <p class="mt-3 text-xs leading-5 text-slate-400">A session is recorded when the countdown reaches zero. Keep this tab open while the timer runs.</p>
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between"><div><h2 class="text-lg font-bold text-slate-950">Study activity</h2><p class="mt-1 text-sm text-slate-500">Minutes recorded over the last seven days.</p></div><span class="text-sm font-semibold text-slate-700">{{ $totalStudyMinutesThisWeek }} min total</span></div>
        <div class="mt-6 grid h-44 grid-cols-7 items-end gap-3 sm:gap-5">
            @foreach($weeklyStudy as $day)
                <div class="flex h-full flex-col items-center justify-end gap-2"><span class="text-xs font-semibold text-slate-500">{{ $day['minutes'] ? $day['minutes'].'m' : '—' }}</span><div class="flex h-32 w-full items-end justify-center rounded-t-lg bg-slate-100"><div title="{{ $day['minutes'] }} study minutes" class="w-full max-w-12 rounded-t-lg bg-indigo-500 transition-all" style="height: {{ $day['minutes'] > 0 ? max(8, ($day['minutes'] / $maxWeeklyMinutes) * 100) : 0 }}%"></div></div><span class="text-xs font-medium text-slate-500">{{ $day['label'] }}</span></div>
            @endforeach
        </div>
    </section>
</div>
@endsection
