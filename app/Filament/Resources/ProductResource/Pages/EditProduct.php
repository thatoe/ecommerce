<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    if ($this->record->orders()->exists()) {
                        Notification::make()
                            ->title('Cannot Delete Product')
                            ->body('This product has existing orders and cannot be deleted.')
                            ->danger()
                            ->send();

                        throw new Halt();
                    }
                }),
        ];
    }
}
