<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { DollarSign } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

// Props from parent (auth()->user()->currency_id or session guest_currency)
const props = defineProps<{
  initialCurrencyId: number | null;
}>();

// Currency Lists
const defaultCurrencies = [
  { id: 1, code: 'USD', flag: '🇺🇸' },
  { id: 4, code: 'SGD', flag: '🇸🇬' },
  { id: 2, code: 'MMK', flag: '🇲🇲' },
];

const otherCurrencies = [
  { id: 3, code: 'THB', flag: '🇹🇭' },
  { id: 5, code: 'VND', flag: '🇻🇳' },
];

// Form
const form = useForm({
  currency_id: null,
});

// Reactive state
const selected = ref<number | 'other' | null>(null);
const customCurrency = ref<number | null>(null);

// Determine default vs. other on mount
const defaultCurrencyIds = defaultCurrencies.map(c => c.id);
const otherCurrencyIds = otherCurrencies.map(c => c.id);

if (defaultCurrencyIds.includes(props.initialCurrencyId ?? -1)) {
  selected.value = props.initialCurrencyId;
} else if (otherCurrencyIds.includes(props.initialCurrencyId ?? -1)) {
  selected.value = 'other';
  customCurrency.value = props.initialCurrencyId;
}

// Event Handlers
const handleSelect = (id: number | 'other') => {
  selected.value = id;
  if (typeof id === 'number') {
    form.currency_id = id;
    form.patch(route('profile.update.currency'));
  }
};

const handleCustomSelect = () => {
  if (customCurrency.value) {
    form.currency_id = customCurrency.value;
    form.patch(route('profile.update.currency'));
  }
};
</script>

<template>
  <div class="space-y-4 max-w-md">
    <!-- Currency Tabs -->
    <div class="rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800 w-full flex justify-between">
      <button
        v-for="currency in defaultCurrencies"
        :key="currency.id"
        @click="handleSelect(currency.id)"
        :class="[
          'flex items-center rounded-md px-3.5 py-1.5 transition-colors w-full justify-center',
          selected === currency.id
            ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
            : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
        ]"
      >
        <span class="text-xl">{{ currency.flag }}</span>
        <span class="ml-1.5 text-sm">{{ currency.code }}</span>
      </button>

      <!-- "Other" Tab -->
      <button
        @click="handleSelect('other')"
        :class="[
          'flex items-center rounded-md px-3.5 py-1.5 transition-colors w-full justify-center',
          selected === 'other'
            ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
            : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
        ]"
      >
        <DollarSign class="w-4 h-4" />
        <span class="ml-1.5 text-sm">Other</span>
      </button>
    </div>

    <!-- Dropdown for "Other" currencies -->
    <div v-if="selected === 'other'" class="pt-2">
      <label class="block mb-1 text-sm font-medium dark:text-white">Choose Other Currency</label>
      <select
        v-model="customCurrency"
        class="w-full border rounded px-3 py-2 text-sm dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
      >
        <option :value="null" disabled>Select a currency</option>
        <option
          v-for="currency in otherCurrencies"
          :key="currency.id"
          :value="currency.id"
        >
          {{ currency.flag }} {{ currency.code }}
        </option>
      </select>

      <Button
        @click="handleCustomSelect"
        class="mt-6"
        :disabled="form.processing || !customCurrency"
      >
        Save
      </Button>
    </div>

    <!-- Feedback -->
    <p v-if="form.recentlySuccessful" class="text-green-600 text-sm">
      ✅ Currency updated successfully!
    </p>
    <p v-if="form.errors.currency_id" class="text-red-600 text-sm">
      {{ form.errors.currency_id }}
    </p>
  </div>
</template>
