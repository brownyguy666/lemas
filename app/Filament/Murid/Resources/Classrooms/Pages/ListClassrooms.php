<?php

namespace App\Filament\Murid\Resources\Classrooms\Pages;

use App\Filament\Murid\Resources\Classrooms\ClassroomResource;
use App\Models\Classroom;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListClassrooms extends ListRecords
{
    protected static string $resource = ClassroomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('joinClassroom')
                ->label('Gabung Kelas dengan Kode')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->modalHeading('Gabung ke Kelas Belajar')
                ->modalDescription('Masukkan kode gabung 6 karakter yang diberikan oleh guru Anda.')
                ->modalSubmitActionLabel('Gabung Sekarang')
                ->form([
                    TextInput::make('join_code')
                        ->label('Kode Gabung Kelas')
                        ->placeholder('Contoh: MTK7A1')
                        ->required()
                        ->maxLength(10)
                        ->autofocus(),
                ])
                ->action(function (array $data): void {
                    $code = strtoupper(trim($data['join_code']));

                    $classroom = Classroom::where('join_code', $code)
                        ->where('is_active', true)
                        ->first();

                    if (! $classroom) {
                        Notification::make()
                            ->danger()
                            ->title('Kelas Tidak Ditemukan')
                            ->body('Kode kelas yang Anda masukkan tidak valid atau kelas sudah tidak aktif.')
                            ->send();

                        return;
                    }

                    if ($classroom->students()->where('student_id', auth()->id())->exists()) {
                        Notification::make()
                            ->warning()
                            ->title('Sudah Terdaftar')
                            ->body('Anda sudah terdaftar di kelas ' . $classroom->name)
                            ->send();

                        return;
                    }

                    $classroom->students()->attach(auth()->id());

                    Notification::make()
                        ->success()
                        ->title('Berhasil Bergabung!')
                        ->body('Selamat, Anda telah bergabung ke dalam kelas ' . $classroom->name)
                        ->send();
                }),
        ];
    }
}
