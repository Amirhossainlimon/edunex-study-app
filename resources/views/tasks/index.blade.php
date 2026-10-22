@extends('layouts.app')
@section('title', 'Tasks')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"><div><p class="text-sm font-semibold uppercase tracking-[0.18em] text-indigo-600">Stay on track</p><h1 class="mt-1 text-3xl font-bold text-slate-950">Task planner</h1><p class="mt-2 text-slate-500">Capture your next steps and make progress visible.</p></div><a href="{{ route('tasks.create') }}" class="rounded-xl bg-indigo-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-indigo-700">+ New task</a></div>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="divide-y divide-slate-100">
            @forelse($tasks as $task)
                <div class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:p-5">
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">@csrf @method('PATCH')<button class="flex h-6 w-6 items-center justify-center rounded-full border {{ $task->status === 'completed' ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 hover:border-indigo-500' }}" aria-label="Toggle {{ $task->title }}">@if($task->status === 'completed')✓@endif</button></form>
                    <div class="min-w-0 flex-1"><p class="font-semibold {{ $task->status === 'completed' ? 'text-slate-400 line-through' : 'text-slate-900' }}">{{ $task->title }}</p>@if($task->description)<p class="mt-1 whitespace-pre-line text-sm text-slate-500">{{ $task->description }}</p>@endif<p class="mt-1 text-xs text-slate-500">{{ $task->due_date ? 'Due '.$task->due_date->format('M j, Y · g:i A') : 'No due date' }}</p></div>
                    <span class="w-fit rounded-full px-2.5 py-1 text-xs font-semibold {{ $task->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : ($task->status === 'in_progress' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600') }}">{{ str_replace('_', ' ', ucfirst($task->status)) }}</span>
                    <div class="flex items-center gap-3 text-sm"><a class="font-semibold text-indigo-700 hover:underline" href="{{ route('tasks.edit', $task) }}">Edit</a><form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">@csrf @method('DELETE')<button class="font-semibold text-rose-600 hover:underline">Delete</button></form></div>
                </div>
            @empty
                <div class="p-10 text-center"><p class="font-semibold text-slate-900">No tasks yet</p><p class="mt-1 text-sm text-slate-500">Start small. Add the next thing you need to do.</p><a href="{{ route('tasks.create') }}" class="mt-4 inline-block rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white">Create a task</a></div>
            @endforelse
        </div>
        @if($tasks->hasPages())<div class="border-t border-slate-100 px-5 py-4">{{ $tasks->links() }}</div>@endif
    </div>
</div>
@endsection
