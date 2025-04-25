<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use Filament\Support\Exceptions\Halt;
use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    if ($this->record->products()->exists()) {
                        Notification::make()
                            ->title('Cannot Delete Category')
                            ->body('This category has existing products and cannot be deleted.')
                            ->danger()
                            ->send();

                        throw new Halt();
                    }
                }),
        ];
    }
}
