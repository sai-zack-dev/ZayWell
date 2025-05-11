<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Product {
    id: number;
    name: string;
    thumbnail: string;
}

interface CartItem {
    cart_id: number;
    product: Product;
    price: number;
    currency: string;
    quantity: number;
}

const props = defineProps<{
    cartItems: CartItem[];
}>();

const breadcrumbs = [
    { title: 'Cart', href: '/cart' },
];

// Use refs for quantity updates
const quantities = ref<Record<number, number>>(
    Object.fromEntries(props.cartItems.map(item => [item.product.id, item.quantity]))
);

const total = computed(() =>
    props.cartItems.reduce((acc, item) => acc + item.price * quantities.value[item.product.id], 0)
);

// Update quantity
const updateQuantity = (productId: number, delta: number) => {
    const current = quantities.value[productId] || 1;
    if (current + delta >= 1) {
        quantities.value[productId] = current + delta;
    }
};

// Save changes
const saveQuantity = (productId: number) => {
    router.post('/cart/update', {
        product_id: productId,
        quantity: quantities.value[productId],
    }, { preserveScroll: true });
};

// Delete item
const removeFromCart = (cartId: number) => {
    router.delete(route('cart.destroy', cartId), {
        preserveScroll: true,
    });
};

</script>

<template>

    <Head title="Cart" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-8">
            <h1 class="text-2xl font-bold">Your Cart</h1>

            <div v-if="cartItems.length === 0" class="text-center text-gray-500">
                Your cart is empty.
            </div>

            <div v-else class="space-y-4">
                <div v-for="(item, index) in cartItems" :key="index"
                    class="flex items-center gap-4 border rounded-lg p-4 bg-white dark:bg-gray-800">

                    <!-- Thumbnail -->
                    <img :src="item.product.thumbnail" class="w-20 h-20 object-cover rounded" alt="Product thumbnail" />

                    <!-- Product Info -->
                    <div class="flex-1 space-y-1">
                        <h2 class="text-lg font-semibold">{{ item.product.name }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Price: {{ item.currency }} {{ item.price }}
                        </p>
                    </div>

                    <!-- Quantity Controls -->
                    <div class="flex items-center gap-2">
                        <button @click="updateQuantity(item.product.id, -1)"
                            class="px-3 py-1 border rounded text-xl bg-gray-100 dark:bg-gray-700">−</button>
                        <span class="min-w-[24px] text-center">{{ quantities[item.product.id] }}</span>
                        <button @click="updateQuantity(item.product.id, 1)"
                            class="px-3 py-1 border rounded text-xl bg-gray-100 dark:bg-gray-700">+</button>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-1 ml-4">
                        <button @click="saveQuantity(item.product.id)"
                            class="text-sm text-blue-600 hover:underline">Save</button>
                        <button @click="removeFromCart(item.cart_id)"
                            class="text-sm text-red-600 hover:underline">Delete</button>

                    </div>
                </div>
            </div>

            <!-- Total Section -->
            <div v-if="cartItems.length" class="border-t pt-4 mt-8 text-right">
                <p class="text-lg font-semibold">
                    Total: {{ cartItems[0].currency }} {{ total.toFixed(2) }}
                </p>
                <button
                    class="mt-4 inline-block bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition">
                    Checkout
                </button>
            </div>
        </div>
    </AppLayout>
</template>
