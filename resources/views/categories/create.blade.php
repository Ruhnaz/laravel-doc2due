<x-app-layout>
    <div class="flex min-h-[70vh] items-center justify-center px-4 py-8 sm:py-16">
        <div class="w-full max-w-lg max-w-[calc(100vw-2rem)] rounded-2xl bg-white p-6 sm:p-8 shadow-dialog">
            <h1 class="text-xl font-semibold text-ink">Add category</h1>
            <p class="mt-2 text-sm text-muted">Categories group your documents, for example insurance or permit.</p>
            <form method="POST" action="{{ route('categories.store') }}" class="mt-6">
                @csrf
                <x-input name="name" label="Category name" required autofocus />
                <div class="mt-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-end">
                    <x-button :href="route('categories.index')" variant="secondary" class="w-full md:w-auto">Cancel</x-button>
                    <x-button class="w-full md:w-auto">Save category</x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
