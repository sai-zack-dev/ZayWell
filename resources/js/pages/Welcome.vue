<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Card from '@/components/ui/card/Card.vue'
import CardTitle from '@/components/ui/card/CardTitle.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import CardDescription from '@/components/ui/card/CardDescription.vue'
import { ShoppingCart } from 'lucide-vue-next';
defineProps<{
    products: Array<{
        id: number
        name: string
        thumbnail: string
        price: number
        stock: number
        currency: string
    }>
}>()
</script>


<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <header class="w-full p-5 fixed flex items-center justify-center top-0 z-10 
            bg-gray-200/20 dark:bg-gray-800/20 backdrop-blur-md shadow-xs">
        <div class="w-full max-w-7xl flex items-center justify-between">
            <!-- Logo + Title -->
            <div class="flex items-center gap-2">
                <!-- <img src="/logo.svg" alt="Brand Logo" class="w-8 h-8" /> -->
                <h1 class="text-xl font-bold">Zay Well</h1>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-4">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')"
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
    <div
        class="flex min-h-screen flex-col items-center bg-[#FAFAFA] p-4 sm:p-6 text-[#1b1b18] dark:bg-[#121212] mt-[78px]">
        <main class="w-full max-w-7xl grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">

            <Link v-for="product in products" :key="product.id" :href="route('products.show', product.id)" class="block transition-transform hover:scale-[1.05] focus:outline-none">
                <Card class="pt-0 pb-4 sm:pb-6 rounded-lg sm:rounded-xl gap-3 cursor-pointer">
                    <img :src="product.thumbnail" alt="Product" class="aspect-square object-cover rounded-t-lg" />
                    <CardContent class="px-3 sm:px-6">
                        <CardDescription class="text-sm sm:text-lg truncate text-gray-900 dark:text-gray-100">
                            {{ product.name }}
                        </CardDescription>
                        <CardDescription class="text-xs text-red-500 py-2">
                            {{ product.stock }} items left
                        </CardDescription>
                        <CardTitle class="sm:text-lg md:text-xl font-bold">
                            {{ product.currency }}{{ product.price }}
                        </CardTitle>
                    </CardContent>
                </Card>
            </Link>

        </main>
        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>
