@php($editing = isset($document))
<div @if($editing) x-data="{ deleting: false }" @endif>
    <form method="POST" action="{{ $editing ? route('documents.update', $document) : route('documents.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif
        <div class="space-y-5 p-4 sm:p-6 md:p-8">
            <x-input name="title" label="Document title" :value="$editing ? $document->title : null" required autofocus />
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-ink">Category <span class="text-danger">*</span></label>
                    <select id="category_id" name="category_id" required class="mt-1.5 block w-full min-h-[44px] rounded-xl border-line text-sm text-ink shadow-sm focus:border-brand focus:ring-brand/20">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id', $editing ? $document->category_id : '') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-1.5 text-sm text-danger">{{ $message }}</p>@enderror
                </div>
                <x-input name="expiry_date" label="Expiry date" type="date" :value="$editing ? $document->expiry_date->toDateString() : null" required />
            </div>
            <div>
                <label for="contractor_id" class="block text-sm font-medium text-ink">Contractor / vendor (optional)</label>
                <select id="contractor_id" name="contractor_id" class="mt-1.5 block w-full min-h-[44px] rounded-xl border-line text-sm text-ink shadow-sm focus:border-brand focus:ring-brand/20">
                    <option value="">None</option>
                    @foreach ($contractors as $contractor)
                        <option value="{{ $contractor->id }}" @selected((string) old('contractor_id', $editing ? $document->contractor_id : '') === (string) $contractor->id)>{{ $contractor->name }}</option>
                    @endforeach
                </select>
                @error('contractor_id')<p class="mt-1.5 text-sm text-danger">{{ $message }}</p>@enderror
                <a href="{{ route('contractors.create') }}" class="mt-2 inline-flex min-h-[44px] items-center text-sm font-semibold text-brand">+ Add a new contractor</a>
            </div>
            <div>
                <label for="file" class="block text-sm font-medium text-ink">File @unless($editing)<span class="text-danger">*</span>@endunless</label>
                <label for="file" class="mt-1.5 flex cursor-pointer flex-col items-center rounded-2xl border border-dashed border-brand/30 bg-brand-tint px-4 py-8 sm:px-6 sm:py-10 text-center">
                    <span class="text-brand">
                        <svg class="mx-auto h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V6m0 0l-4 4m4-4l4 4"/></svg>
                    </span>
                    <span class="mt-3 text-sm text-body">Drag and drop a file here, or</span>
                    <span class="mt-3 inline-flex min-h-[44px] items-center justify-center rounded-xl border border-line bg-white px-4 py-2 text-sm font-semibold text-ink">Browse files</span>
                    <span class="mt-3 text-xs text-muted">PDF, JPG or JPEG · maximum 2 MB</span>
                </label>
                <input id="file" name="file" type="file" accept=".pdf,.jpg,.jpeg" @required(! $editing) class="sr-only" onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : ''" />
                <p id="file-name" class="mt-2 break-all text-sm font-medium text-ink">{{ $editing ? $document->file_name : '' }}</p>
                @error('file')<p class="mt-1.5 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-ink">Notes (optional)</label>
                <textarea id="notes" name="notes" rows="4" placeholder="Policy number, renewal contact, anything your team should know" class="mt-1.5 block w-full rounded-xl border-line text-sm text-ink shadow-sm focus:border-brand focus:ring-brand/20">{{ old('notes', $editing ? $document->notes : null) }}</textarea>
                @error('notes')<p class="mt-1.5 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="flex flex-col gap-3 border-t border-line px-4 py-4 sm:px-6 md:flex-row md:items-center md:justify-between md:px-8">
            @if ($editing)
                <button type="button" form="delete-document-form" @click="deleting = true" class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl border border-danger-line px-4 py-2.5 text-sm font-semibold text-danger hover:bg-danger-soft md:w-auto">Delete document</button>
            @else
                <span></span>
            @endif
            <div class="flex flex-col gap-3 w-full md:w-auto md:flex-row md:items-center md:justify-end">
                <x-button :href="route('documents.index')" variant="secondary" class="w-full md:w-auto">Cancel</x-button>
                <x-button class="w-full md:w-auto">{{ $editing ? 'Save changes' : 'Save document' }}</x-button>
            </div>
        </div>
    </form>
    @if ($editing)
        <x-delete-modal
            show="deleting"
            form-id="delete-document-form"
            :action="route('documents.destroy', $document)"
            title="Delete this document?"
            :description="$document->title . ' and its uploaded file will be permanently removed. This cannot be undone.'"
            submit-label="Delete document"
        />
    @endif
</div>
