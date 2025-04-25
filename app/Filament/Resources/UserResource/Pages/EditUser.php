<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    if ($this->record->orders()->exists()) {
                        Notification::make()
                            ->title('Cannot Delete User')
                            ->body('This user has existing orders and cannot be deleted.')
                            ->danger()
                            ->send();

                        throw new Halt();
                    }
                }),
        ];
    }
}