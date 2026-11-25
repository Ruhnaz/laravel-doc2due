<x-app-layout>
    <div class="flex min-h-[70vh] items-center justify-center px-4 py-8 sm:py-16">
        <div class="w-full max-w-lg max-w-[calc(100vw-2rem)] rounded-2xl bg-white p-6 sm:p-8 shadow-dialog">
            <h1 class="text-xl font-semibold text-ink">Edit category</h1>
            <p class="mt-2 text-sm text-muted">Update the name used to group these documents.</p>
            <form method="POST" action="{{ route('categories.update', $category) }}" class="mt-6">
                @csrf
                @method('PUT')
                <x-input name="name" label="Category name" :value="$category->name" required autofocus />
                <div class="mt-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <button type="submit" form="delete-category" class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl border border-danger-line px-4 py-2.5 text-sm font-semibold text-danger hover:bg-danger-soft md:w-auto">Delete category</button>
                    <div class="flex flex-col gap-3 w-full md:w-auto md:flex-row md:items-center">
                        <x-button :href="route('categories.index')" variant="secondary" class="w-full md:w-auto">Cancel</x-button>
                        <x-button class="w-full md:w-auto">Save changes</x-button>
                    </div>
                </div>
            </form>
            <form id="delete-category" method="POST" action="{{ route('categories.destroy', $category) }}" class="hidden" onsubmit="return confirm('Delete this category?')">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-app-layout>
