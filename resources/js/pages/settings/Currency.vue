<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import CurrencyTabs from '@/components/CurrencyTabs.vue'; // 👈 import this
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

import type { BreadcrumbItem } from '@/types';

defineProps<{
  initialCurrencyId: number | null;
}>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Currency settings',
        href: '/settings/currency',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Currency settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    title="Currency settings"
                    description="Choose your default display currency for prices."
                />

                <!-- Main UI -->
                <CurrencyTabs :initial-currency-id="initialCurrencyId" />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
