@extends('layouts.app')
@section('content')
<section class="mx-auto max-w-md px-4 py-16"><h1 class="font-serif text-4xl">Forgot password</h1><div class="mt-8 rounded-2xl border border-white/10 bg-neutral-900/60 p-6 text-stone-300">Password reset is currently handled by restaurant support. Please contact {{ $settings['phone_no'] ?? 'the restaurant' }}.</div></section>
@endsection
