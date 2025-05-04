<x-filament::page>
    @if (!$isEditing)
        <div class="space-y-4">
            <div class="flex items-center gap-6">
                <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="w-32 h-32 rounded-full border" />
                <div class="flex flex-col gap-3">
                    <div class="text-xl font-semibold">{{ Auth::user()->name }}</div><hr>
                    <div class="text-base text-gray-600">{{ Auth::user()->email }}</div>
                    <div class="text-base text-gray-600">Currency: {{ Auth::user()->currency?->name }} ({{ Auth::user()->currency?->symbol }})</div>
                </div>
            </div>

            <x-filament::button wire:click="enterEditMode" icon="heroicon-o-pencil-square">
                Edit
            </x-filament::button>
        </div>
    @else
        {{ $this->form }}
        <div class="space-y-4">

            <x-filament::button wire:click="save" class="mt-4">
                Save
            </x-filament::button>
        </div>

    @endif
</x-filament::page>
