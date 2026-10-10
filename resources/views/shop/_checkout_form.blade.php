<div>
    <label class="mb-1 block text-sm font-semibold text-coffee-dark">Atas Nama *</label>
    <input type="text" x-model="checkout.customer_name" required maxlength="100" class="w-full rounded-xl border border-cream-dark bg-cream/50 px-4 py-2.5 text-sm text-coffee-dark outline-none transition focus:border-warm-amber focus:ring-2 focus:ring-warm-amber/20" placeholder="Nama pemesan">
</div>
<div>
    <label class="mb-1 block text-sm font-semibold text-coffee-dark">Tipe Pesanan *</label>
    <div class="grid grid-cols-3 gap-2">
        <template x-for="opt in [{val:'dine_in',label:'Dine In',icon:'🍽️'},{val:'takeaway',label:'Takeaway',icon:'🥤'},{val:'pickup',label:'Pickup',icon:'📦'}]" :key="opt.val">
            <button type="button" @click="checkout.order_type = opt.val"
                :class="checkout.order_type === opt.val ? 'border-warm-amber bg-warm-amber/5 ring-1 ring-warm-amber' : 'border-cream-dark hover:border-warm-gray'"
                class="rounded-xl border-2 px-3 py-3 text-center transition">
                <span class="block text-lg" x-text="opt.icon"></span>
                <span class="mt-1 block text-xs font-semibold text-coffee-dark" x-text="opt.label"></span>
            </button>
        </template>
    </div>
</div>
<div>
    <label class="mb-1 block text-sm font-semibold text-coffee-dark">Catatan / No. Meja</label>
    <textarea x-model="checkout.table_or_notes" rows="2" class="w-full rounded-xl border border-cream-dark bg-cream/50 px-4 py-2.5 text-sm text-coffee-dark outline-none transition focus:border-warm-amber focus:ring-2 focus:ring-warm-amber/20" placeholder="Meja 5 / tanpa sedotan..."></textarea>
</div>
<div class="rounded-xl bg-cream p-4">
    <h4 class="mb-2 text-sm font-bold text-coffee-dark">Ringkasan Pesanan</h4>
    <template x-for="(item, i) in cart" :key="i">
        <div class="flex justify-between border-b border-cream-dark py-1.5 text-sm text-warm-gray last:border-0">
            <span><span x-text="item.quantity"></span>x <span x-text="item.name"></span></span>
            <span x-text="formatRupiah(item.unitPrice * item.quantity)"></span>
        </div>
    </template>
    <div class="mt-2 flex justify-between border-t border-coffee-dark/10 pt-2 text-sm font-bold text-coffee-dark">
        <span>Total</span><span x-text="formatRupiah(cartTotal)"></span>
    </div>
</div>
<div x-show="checkoutError" class="rounded-xl bg-red-50 p-3 text-sm text-red-600" x-text="checkoutError"></div>
<button type="submit" :disabled="checkoutLoading"
    class="w-full rounded-2xl bg-warm-amber py-3.5 text-center text-sm font-bold text-white shadow-lg shadow-warm-amber/25 transition hover:bg-amber-light disabled:cursor-not-allowed disabled:opacity-60 active:scale-[0.98]">
    <span x-show="!checkoutLoading">Bayar Sekarang — <span x-text="formatRupiah(cartTotal)"></span></span>
    <span x-show="checkoutLoading" class="inline-flex items-center gap-2">
        <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
        Memproses...
    </span>
</button>
