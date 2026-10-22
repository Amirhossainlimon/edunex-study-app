@extends('layouts.app')
@section('title', 'Add exam')
@section('content')
<div class="mx-auto max-w-2xl"><a href="{{ route('exams.index') }}" class="text-sm font-semibold text-indigo-700 hover:underline">← Back to exams</a><h1 class="mt-3 text-3xl font-bold text-slate-950">Add an exam</h1><p class="mt-2 text-slate-500">Add a subject and its scheduled date.</p>@include('exams._form')</div>
@endsection
