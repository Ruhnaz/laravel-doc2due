<x-guest-layout>
    <h1 class="text-3xl font-bold text-ink">Create your account</h1>
    <p class="mt-2 text-sm text-muted">Start tracking expiry dates in minutes.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-8">
        @csrf

        <div>
            <x-input-label for="company_name" :value="__('Company name')" />
            <x-text-input id="company_name" class="mt-1 block h-11 w-full rounded-lg bg-surface" type="text" name="company_name" :value="old('company_name')" required autofocus autocomplete="organization" />
            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="mt-1 block h-11 w-full rounded-lg bg-surface" type="text" name="name" :value="old('name')" required autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block h-11 w-full rounded-lg bg-surface" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1 block h-11 w-full rounded-lg bg-surface" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="mt-1 block h-11 w-full rounded-lg bg-surface" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6 flex items-center justify-end gap-4">
            <a class="text-sm text-muted hover:text-ink" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="rounded-lg px-6 py-3 text-xs font-semibold uppercase tracking-wider">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
