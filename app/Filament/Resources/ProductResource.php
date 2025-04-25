<?php

namespace App\Filament\Resources;

use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Forms\Components\Select;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            Textarea::make('description'),
            TextInput::make('price')
                ->required()
                ->numeric(),
            FileUpload::make('image')
                ->image()
                ->directory('products'),
                Select::make('category_id')
                ->label('Category')
                ->options(
                    \App\Models\Category::whereNotNull('parent_id')
                        ->with('parent')
                        ->get()
                        ->mapWithKeys(fn ($category) => [
                            $category->id => "{$category->parent->name} > {$category->name}"
                        ])
                )
                ->searchable()
                ->required()
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('price')->money('usd')->sortable(),
                ImageColumn::make('image')
                    ->label('Product Image')
                    ->disk('public')
                    ->circular(),
            ])
            ->defaultSort('name', 'desc')
            ->filters([
                // filter by name
                Tables\Filters\Filter::make('name')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->placeholder('Search by name'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query->where('name', 'like', "%{$data['name']}%")),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->before(function ($record) {
                    if ($record->orders()->exists()) {
                        Notification::make()
                            ->title('Cannot Delete Product')
                            ->body('This products has orders and cannot be deleted.')
                            ->danger()
                            ->send();

                        throw new Halt();
                    }
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
