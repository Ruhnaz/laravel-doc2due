<x-guest-layout>
    <h1 class="text-3xl font-bold text-ink">Sign in</h1>
    <p class="mt-2 text-sm text-muted">Welcome back. Pick up your document register.</p>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block h-11 w-full rounded-lg bg-surface" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1 block h-11 w-full rounded-lg bg-surface" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-line text-brand shadow-sm focus:ring-brand/30" name="remember">
                <span class="ms-2 text-sm text-muted">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6 flex items-center justify-end gap-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-muted hover:text-ink" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <x-primary-button class="rounded-lg px-6 py-3 text-xs font-semibold uppercase tracking-wider">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
