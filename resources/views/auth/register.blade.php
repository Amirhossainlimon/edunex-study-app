@extends('layouts.app')
@section('title', 'Create account')
@section('content')
<div class="mx-auto max-w-md py-6 sm:py-10">
    <div class="mb-7 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-600 text-2xl font-black text-white shadow-lg shadow-indigo-200">E</div>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950">Create your account</h1>
        <p class="mt-2 text-sm text-slate-500">Make space for better habits and calmer study days.</p>
    </div>
    <form method="POST" action="{{ route('register') }}" class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        @csrf
        <div><label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Full name</label><input id="name" name="name" type="text" autocomplete="name" maxlength="120" required value="{{ old('name') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"></div>
        <div><label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email address</label><input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"></div>
        <div><label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label><input id="password" name="password" type="password" autocomplete="new-password" required minlength="8" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"><p class="mt-1 text-xs text-slate-500">Use at least 8 characters.</p></div>
        <div><label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Confirm password</label><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="8" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"></div>
        <button type="submit" class="mt-2 w-full rounded-xl bg-indigo-600 px-4 py-3 font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">Create account</button>
        <p class="text-center text-sm text-slate-500">Already have an account? <a href="{{ route('login') }}" class="font-semibold text-indigo-700 hover:underline">Log in</a></p>
    </form>
</div>
@endsection
