@php
    $editing = isset($employee);
@endphp

<form method="POST" action="{{ $editing ? route('employees.update', $employee) : route('employees.store') }}">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <div class="space-y-5 p-4 sm:p-6 md:p-8">
        <x-input name="name" label="Full name" :value="$editing ? $employee->name : null" required autofocus />
        <div class="grid gap-5 md:grid-cols-2">
            <x-input name="email" label="Email" type="email" :value="$editing ? $employee->email : null" required autocomplete="username" />
            <div>
                <label for="role" class="block text-sm font-medium text-ink">Role <span class="text-danger">*</span></label>
                <select
                    id="role"
                    name="role"
                    @disabled($editing && $employee->is(auth()->user()))
                    class="mt-1.5 block w-full min-h-[44px] rounded-xl border-line text-sm text-ink shadow-sm focus:border-brand focus:ring-brand/20"
                >
                    @foreach (\App\Enums\UserRole::cases() as $role)
                        <option value="{{ $role->value }}" @selected(old('role', $editing ? $employee->role->value : 'employee') === $role->value)>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
                @if ($editing && $employee->is(auth()->user()))
                    <input type="hidden" name="role" value="admin">
                    <p class="mt-1.5 text-sm text-muted">Your role stays admin so the company always has an owner.</p>
                @endif
                @error('role')
                    <p class="mt-1.5 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="grid gap-5 md:grid-cols-2">
            <x-input
                name="password"
                :label="$editing ? 'New password' : 'Password'"
                type="password"
                autocomplete="new-password"
                :required="! $editing"
            />
            <x-input
                name="password_confirmation"
                label="Confirm password"
                type="password"
                autocomplete="new-password"
                :required="! $editing"
            />
        </div>
        <p class="text-sm text-muted">At least 8 characters. Your teammate can change it after signing in.</p>
    </div>

    <div class="flex flex-col gap-3 border-t border-line px-4 py-4 sm:px-6 md:flex-row md:items-center md:justify-between md:px-8">
        @if ($editing && auth()->user()->isNot($employee))
            <button type="submit" form="delete-employee" class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl border border-danger-line px-4 py-2.5 text-sm font-semibold text-danger hover:bg-danger-soft md:w-auto">Remove employee</button>
        @else
            <span></span>
        @endif
        <div class="flex flex-col gap-3 w-full md:w-auto md:flex-row md:items-center md:justify-end">
            <x-button :href="route('employees.index')" variant="secondary" class="w-full md:w-auto">Cancel</x-button>
            <x-button class="w-full md:w-auto">{{ $editing ? 'Save changes' : 'Add employee' }}</x-button>
        </div>
    </div>
</form>
@if ($editing && auth()->user()->isNot($employee))
    <form id="delete-employee" method="POST" action="{{ route('employees.destroy', $employee) }}" class="hidden" onsubmit="return confirm('Remove this employee?')">
        @csrf
        @method('DELETE')
    </form>
@endif
