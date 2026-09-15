{{-- Size --}}
<div>
    <label class="mb-2 block text-sm font-semibold text-coffee-dark">Cup Size</label>
    <div class="grid grid-cols-2 gap-2">
        <template x-for="opt in [{val:'regular',label:'Regular',extra:0},{val:'large',label:'Large',extra:6000}]" :key="opt.val">
            <button @click="customize.size = opt.val" type="button"
                :class="customize.size === opt.val ? 'border-warm-amber bg-warm-amber/5 ring-1 ring-warm-amber' : 'border-cream-dark hover:border-warm-gray'"
                class="rounded-xl border-2 px-4 py-3 text-left transition">
                <span class="block text-sm font-semibold text-coffee-dark" x-text="opt.label"></span>
                <span class="text-xs text-warm-gray" x-text="opt.extra ? '+' + formatRupiah(opt.extra) : 'Standard'"></span>
            </button>
        </template>
    </div>
</div>
{{-- Bean --}}
<div x-show="currentProduct?.category !== 'pastry'">
    <label class="mb-2 block text-sm font-semibold text-coffee-dark">Bean / Blend</label>
    <div class="grid grid-cols-2 gap-2">
        <template x-for="opt in [{val:'house_blend',label:'House Blend',extra:0},{val:'single_origin',label:'Single Origin',extra:5000}]" :key="opt.val">
            <button @click="customize.bean = opt.val" type="button"
                :class="customize.bean === opt.val ? 'border-warm-amber bg-warm-amber/5 ring-1 ring-warm-amber' : 'border-cream-dark hover:border-warm-gray'"
                class="rounded-xl border-2 px-4 py-3 text-left transition">
                <span class="block text-sm font-semibold text-coffee-dark" x-text="opt.label"></span>
                <span class="text-xs text-warm-gray" x-text="opt.extra ? '+' + formatRupiah(opt.extra) : 'Standard'"></span>
            </button>
        </template>
    </div>
</div>
{{-- Milk --}}
<div x-show="currentProduct?.category !== 'pastry'">
    <label class="mb-2 block text-sm font-semibold text-coffee-dark">Milk</label>
    <div class="grid grid-cols-3 gap-2">
        <template x-for="opt in [{val:'dairy',label:'Dairy',extra:0},{val:'oat_milk',label:'Oat Milk',extra:8000},{val:'almond_milk',label:'Almond',extra:8000}]" :key="opt.val">
            <button @click="customize.milk = opt.val" type="button"
                :class="customize.milk === opt.val ? 'border-warm-amber bg-warm-amber/5 ring-1 ring-warm-amber' : 'border-cream-dark hover:border-warm-gray'"
                class="rounded-xl border-2 px-3 py-3 text-center transition">
                <span class="block text-sm font-semibold text-coffee-dark" x-text="opt.label"></span>
                <span class="text-[11px] text-warm-gray" x-text="opt.extra ? '+' + formatRupiah(opt.extra) : 'Free'"></span>
            </button>
        </template>
    </div>
</div>
{{-- Sweetness --}}
<div x-show="currentProduct?.category !== 'pastry'">
    <label class="mb-2 block text-sm font-semibold text-coffee-dark">Sweetness Level</label>
    <div class="flex gap-2">
        <template x-for="opt in ['normal','less','none']" :key="opt">
            <button @click="customize.sweetness = opt" type="button"
                :class="customize.sweetness === opt ? 'border-warm-amber bg-warm-amber/5 text-warm-amber' : 'border-cream-dark text-warm-gray'"
                class="flex-1 rounded-xl border-2 px-3 py-2.5 text-sm font-medium capitalize transition" x-text="opt"></button>
        </template>
    </div>
</div>
{{-- Ice Level --}}
<div x-show="currentProduct?.category !== 'pastry'">
    <label class="mb-2 block text-sm font-semibold text-coffee-dark">Ice Level</label>
    <div class="flex gap-2">
        <template x-for="opt in ['normal','less','none']" :key="opt">
            <button @click="customize.ice = opt" type="button"
                :class="customize.ice === opt ? 'border-warm-amber bg-warm-amber/5 text-warm-amber' : 'border-cream-dark text-warm-gray'"
                class="flex-1 rounded-xl border-2 px-3 py-2.5 text-sm font-medium capitalize transition" x-text="opt"></button>
        </template>
    </div>
</div>
