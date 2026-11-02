<?php

namespace App\Filament\Guru\Resources\Classrooms\Pages;

use App\Filament\Guru\Resources\Classrooms\ClassroomResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClassroom extends CreateRecord
{
    protected static string $resource = ClassroomResource::class;

    /**
     * Otomatis menetapkan guru pengampu adalah guru yang sedang login.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['teacher_id'] = auth()->id();

        return $data;
    }
}
