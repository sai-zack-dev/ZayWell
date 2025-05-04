<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use App\Models\Currency;
use Filament\Notifications\Notification;
use Filament\Forms\Components\{TextInput, Select, FileUpload, Grid};

class Settings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationLabel = 'Settings';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.settings';
    protected static ?string $title = 'Settings';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?int $navigationSort = 99;

    public ?array $data = [];
    public bool $isEditing = false;

    public function mount(): void
    {
        $store = Auth::user();
        $this->form->fill([
            'name' => $store->name,
            'email' => $store->email,
            'avatar' => $store->avatar,
            'currency_id' => $store->currency_id,
        ]);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data'); // ✅ Bind form state to $data
    }

    public function enterEditMode(): void
    {
        $this->isEditing = true;
    }

    public function save(): void
    {
        $store = Auth::user();
        $store->update($this->data); // ✅ Use $this->data instead of $this->form->getState()

        Notification::make()
            ->success()
            ->title('Settings updated')
            ->send();

        $this->isEditing = false;
    }

    protected function getFormSchema(): array
    {
        return [
            FileUpload::make('avatar')
                ->avatar()
                ->image()
                ->directory('avatars')
                ->maxSize(1024),
            Grid::make(2)->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->required()->email(),

                Select::make('currency_id')
                    ->label('Default Currency')
                    ->options(Currency::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(), 
            ]),
        ];
    }

    public function getTitle(): string
    {
        return $this->isEditing ? 'Edit Settings' : 'Settings';
    }
    
    public function getBreadcrumbs(): array
    {
        return $this->isEditing
            ? [
                route(static::getRouteName()) => 'Settings',
                '' => 'Edit',
            ]
            : [
                '' => '',
            ];
    }

}

