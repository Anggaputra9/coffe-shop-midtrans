<script>
function coffeeShop() {
    return {
        cart: [],
        cartOpen: false,
        customizeOpen: false,
        checkoutOpen: false,
        checkoutLoading: false,
        checkoutError: '',
        currentProduct: null,
        activeCategory: 'espresso',
        categories: [
            { key: 'espresso', label: 'Espresso' },
            { key: 'manual_brew', label: 'Manual Brew' },
            { key: 'non_coffee', label: 'Non-Coffee' },
            { key: 'pastry', label: 'Pastry' },
        ],
        customize: { size: 'regular', bean: 'house_blend', milk: 'dairy', sweetness: 'normal', ice: 'normal' },
        checkout: { customer_name: '', order_type: 'dine_in', table_or_notes: '' },

        get computedUnitPrice() {
            if (!this.currentProduct) return 0;
            let p = this.currentProduct.base_price;
            if (this.customize.size === 'large') p += 6000;
            if (this.customize.bean === 'single_origin') p += 5000;
            if (['oat_milk', 'almond_milk'].includes(this.customize.milk)) p += 8000;
            return p;
        },
        get cartCount() { return this.cart.reduce((s, i) => s + i.quantity, 0); },
        get cartSubtotal() { return this.cart.reduce((s, i) => s + i.unitPrice * i.quantity, 0); },
        get cartTax() { return Math.round(this.cartSubtotal * 0.11); },
        get cartTotal() { return this.cartSubtotal + this.cartTax + 2000; },

        formatRupiah(n) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(n); },
        scrollToCategory(k) { this.activeCategory = k; document.getElementById('cat-' + k)?.scrollIntoView({ behavior: 'smooth' }); },

        openCustomize(product) {
            this.currentProduct = product;
            this.customize = { size: 'regular', bean: 'house_blend', milk: 'dairy', sweetness: 'normal', ice: 'normal' };
            this.customizeOpen = true;
        },

        addToCart() {
            const labels = [];
            if (this.customize.size === 'large') labels.push('Large');
            if (this.customize.bean === 'single_origin') labels.push('Single Origin');
            if (this.customize.milk !== 'dairy') labels.push(this.customize.milk.replace('_', ' '));
            labels.push('Sweet: ' + this.customize.sweetness);
            labels.push('Ice: ' + this.customize.ice);
            this.cart.push({
                product_id: this.currentProduct.id,
                name: this.currentProduct.name,
                image_url: this.currentProduct.image_url,
                category: this.currentProduct.category,
                unitPrice: this.computedUnitPrice,
                quantity: 1,
                options: { ...this.customize },
                optionsLabel: labels.join(' · '),
            });
            this.customizeOpen = false;
            this.cartOpen = true;
        },

        updateQty(index, delta) {
            this.cart[index].quantity += delta;
            if (this.cart[index].quantity < 1) this.cart.splice(index, 1);
        },
        removeFromCart(index) { this.cart.splice(index, 1); },

        async processCheckout() {
            this.checkoutError = '';
            this.checkoutLoading = true;
            const payload = {
                ...this.checkout,
                items: this.cart.map(i => ({ product_id: i.product_id, quantity: i.quantity, options: i.options })),
            };
            try {
                const res = await fetch('/checkout', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();
                if (!res.ok || !data.success) {
                    this.checkoutError = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Terjadi kesalahan.');
                    this.checkoutLoading = false;
                    return;
                }
                this.checkoutOpen = false;
                const uuid = data.order_uuid;
                window.snap.pay(data.snap_token, {
                    onSuccess: () => { window.location.href = '/orders/' + uuid; },
                    onPending: () => { window.location.href = '/orders/' + uuid; },
                    onError: () => { window.location.href = '/orders/' + uuid; },
                    onClose: () => { window.location.href = '/orders/' + uuid; },
                });
            } catch (e) {
                this.checkoutError = 'Gagal menghubungi server. Periksa koneksi Anda.';
            }
            this.checkoutLoading = false;
        },
    };
}
</script>
