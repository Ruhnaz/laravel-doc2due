<x-guest-layout>
    <h1 class="text-3xl font-bold text-ink">Forgot your password?</h1>
    <p class="mt-2 text-sm text-muted">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-8">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block h-11 w-full rounded-lg bg-surface" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6 flex items-center justify-end gap-4">
            <a class="text-sm text-muted hover:text-ink" href="{{ route('login') }}">{{ __('Sign in') }}</a>
            <x-primary-button class="rounded-lg px-6 py-3 text-xs font-semibold uppercase tracking-wider">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
