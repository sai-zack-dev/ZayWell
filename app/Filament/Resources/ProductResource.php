<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Currency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{TextInput, Textarea, FileUpload, Hidden, Grid, Section, Select};
use Filament\Tables\Columns\{TextColumn, ImageColumn, BadgeColumn};
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('stock')->numeric()->required(),
                    TextInput::make('price')->numeric()->required(),
                    Select::make('currency_id')
                        ->label('Default Currency')
                        ->options(Currency::all()->pluck('name', 'id'))
                        ->default(fn () => request()->routeIs('filament.system.resources.products.create')
                            ? Auth::user()?->currency_id
                            : null)
                        ->disabled(fn (Get $get): bool => request()->routeIs('filament.system.resources.products.create')) // ✅ disables only on create
                        ->dehydrated(true)
                        ->preload(),
                    Textarea::make('description')->rows(4),
                ]),
                FileUpload::make('thumbnail')
                    ->label('Main Thumbnail')
                    ->directory('products/thumbnails') 
                    ->disk('public')        
                    ->image()
                    ->imagePreviewHeight('100')
                    ->required(),

                FileUpload::make('images')
                    ->label('Gallery Images')
                    ->directory('/products/images')    
                    ->disk('public')
                    ->image()
                    ->imagePreviewHeight('100')
                    ->multiple(),

                Hidden::make('store_id')->default(auth()->user()->id),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')->label('Thumbnail')->width(50),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('converted_price')
                    ->label('Price')
                    ->tooltip(fn ($record) => "Original: {$record->currency?->symbol} {$record->price}"),
                TextColumn::make('stock')->sortable(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
