@extends('layouts.app')
@section('title', 'Log study session')
@section('content')
<div class="mx-auto max-w-2xl"><a href="{{ route('study-sessions.index') }}" class="text-sm font-semibold text-indigo-700 hover:underline">← Back to study log</a><h1 class="mt-3 text-3xl font-bold text-slate-950">Log a study session</h1><p class="mt-2 text-slate-500">Record focused time completed outside the timer.</p>@include('study-sessions._form')</div>
@endsection
