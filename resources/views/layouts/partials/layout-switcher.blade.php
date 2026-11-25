<div class="hidden items-center rounded-xl border border-line p-0.5 lg:flex" role="group" aria-label="Navigation layout">
    <button type="button" class="rounded-lg px-2 py-1 text-xs font-semibold" :class="$store.layout.mode === 'top' ? 'bg-brand-soft text-brand' : 'text-muted'" @click="$store.layout.set('top')">Top</button>
    <button type="button" class="rounded-lg px-2 py-1 text-xs font-semibold" :class="$store.layout.mode === 'side' ? 'bg-brand-soft text-brand' : 'text-muted'" @click="$store.layout.set('side')">Side</button>
    <button type="button" class="rounded-lg px-2 py-1 text-xs font-semibold" :class="$store.layout.mode === 'compact' ? 'bg-brand-soft text-brand' : 'text-muted'" @click="$store.layout.set('compact')">Compact</button>
</div>
