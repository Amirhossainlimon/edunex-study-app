@extends('layouts.app')
@section('title', 'Log in')
@section('content')
<div class="mx-auto max-w-md py-8 sm:py-14">
    <div class="mb-8 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-600 text-2xl font-black text-white shadow-lg shadow-indigo-200">E</div>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950">Welcome back</h1>
        <p class="mt-2 text-sm text-slate-500">Sign in to keep your study plan moving.</p>
    </div>
    <form method="POST" action="{{ route('login') }}" class="space-y-5 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        @csrf
        <div><label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email address</label><input id="email" name="email" type="email" autocomplete="email" required autofocus value="{{ old('email') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"><x-field-error field="email" /></div>
        <div><label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"><x-field-error field="password" /></div>
        <label class="flex items-center gap-2 text-sm text-slate-600"><input name="remember" type="checkbox" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> Remember me</label>
        <button type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-3 font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">Log in</button>
        <p class="text-center text-sm text-slate-500">New to EduNex? <a href="{{ route('register') }}" class="font-semibold text-indigo-700 hover:underline">Create an account</a></p>
    </form>
</div>
@endsection
