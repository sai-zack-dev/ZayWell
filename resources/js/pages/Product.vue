<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ChevronLeft, ShoppingCart } from 'lucide-vue-next';
const props = defineProps<{
  product: {
    id: number;
    name: string;
    description: string;
    thumbnail: string;
    currency: string;
    price: number;
    stock: number;
    images?: string[];
  };
}>();

const { product } = props;

interface PageProps {
  auth: {
    user: {
      id: number;
      name: string;
      email: string;
    } | null;
  };
  [key: string]: any; // Add index signature to satisfy the constraint
}

const page = usePage<PageProps>(); // Ensure proper typing for usePage
const counter = ref(1); // Initialize counter with 1

// Function to handle incrementing counter
const incrementCounter = () => {
  if (counter.value < (product?.stock || 0)) {
    counter.value++;
  }
};

// Function to handle decrementing counter
const decrementCounter = () => {
  if (counter.value > 1) {
    counter.value--;
  }
};

const addToCart = () => {
  router.post('/cart', {
    product_id: product.id,
    quantity: counter.value,
  });
};

</script>

<template>

  <Head :title="product?.name" />
  <header class="w-full p-5 fixed flex items-center justify-center top-0 z-10 
            bg-gray-200/20 dark:bg-gray-800/20 backdrop-blur-md shadow-xs">
    <div class="w-full max-w-7xl flex items-center justify-between">
      <!-- Logo + Title -->
      <div class="flex items-center gap-2">
        <h1 class="text-xl font-bold">Zay Well</h1>
      </div>

      <!-- Auth Buttons -->
      <div class="flex items-center gap-4">
        <Link v-if="page.props.auth?.user" :href="route('dashboard')"
          class="px-3 py-2 border rounded text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-80 flex gap-3">
        <ShoppingCart class="w-5 h-5" /> Cart
        </Link>
        <template v-else>
          <Link :href="route('login')" class="px-4 py-2 text-sm hover:underline">Login</Link>
          <Link :href="route('register')"
            class="px-4 py-2 border rounded text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-800">
          Register
          </Link>
        </template>
      </div>
    </div>
  </header>

  <div class="w-full max-w-7xl mx-auto py-4 sm:py-6 mt-[78px]">
    <!-- Back -->
    <Link :href="route('home')" class="inline-flex items-center text-sm text-gray-800 hover:underline mb-4">
    <ChevronLeft /> Back
    </Link>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <!-- Gallery -->
      <div>
        <img :src="product?.thumbnail" alt="Thumbnail" class="w-full aspect-square object-cover rounded-lg shadow" />
      </div>

      <!-- Info -->
      <div class="p-5">
        <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">
          {{ product?.name }}
        </h1>

        <p class="mt-2 text-gray-500 dark:text-gray-300 text-sm">
          {{ product?.description || 'No description provided.' }}
        </p>

        <div class="flex justify-between">
          <div>
            <div class="mt-6">
              <span class="text-2xl font-bold text-black dark:text-white">
                {{ product?.currency }}
                {{ product?.price }}
              </span>
            </div>

            <div class="mt-2">
              <span class="inline-block px-3 py-1 text-sm rounded-full"
                :class="product?.stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                {{ product?.stock > 0 ? `${product?.stock} in-stock` : 'Out of stock' }}
              </span>
            </div>
          </div>
        </div>

        <div class="mt-5 grid grid-cols-3 gap-2" v-if="product?.images && product?.images.length">
          <img v-for="(img, index) in product?.images" :key="index" :src="img"
            class="w-full h-24 object-cover rounded-md border" :alt="`Image ${index + 1}`" />
        </div>

        <!-- Counter + Add to Cart Section -->
        <div class="mt-10 flex justify-between items-center gap-5">
            <!-- Counter -->
            <div class="flex items-center gap-5">
              <button @click="decrementCounter" :disabled="counter <= 1"
                class="px-4 py-2 border rounded bg-gray-100 dark:bg-gray-900 cursor-pointer disabled:opacity-50 text-xl">
                -
              </button>
              <span class="text-xl">{{ counter }}</span>
              <button @click="incrementCounter" :disabled="counter >= (product?.stock || 0)"
                class="px-4 py-2 border rounded bg-gray-100 dark:bg-gray-900 cursor-pointer disabled:opacity-50 text-xl">
                +
              </button>
            </div>

            <!-- Add to Cart Button -->
              <button @click="addToCart" :disabled="product?.stock <= 0"
                class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 disabled:bg-gray-400 cursor-pointer">
                Add to Cart
              </button>
          </div>
      </div>
    </div>
  </div>
</template>
