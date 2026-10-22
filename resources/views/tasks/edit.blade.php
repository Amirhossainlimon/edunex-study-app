@extends('layouts.app')
@section('title', 'Edit task')
@section('content')
<div class="mx-auto max-w-2xl"><a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-indigo-700 hover:underline">← Back to tasks</a><h1 class="mt-3 text-3xl font-bold text-slate-950">Edit task</h1><p class="mt-2 text-slate-500">Update the details or change the status.</p>@include('tasks._form')</div>
@endsection
