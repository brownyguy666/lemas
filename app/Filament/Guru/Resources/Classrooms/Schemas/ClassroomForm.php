<?php

namespace App\Filament\Guru\Resources\Classrooms\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClassroomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kelas')
                    ->placeholder('Contoh: IPA Terpadu Kelas 7A')
                    ->required()
                    ->maxLength(255),

                TextInput::make('academic_year')
                    ->label('Tahun Ajaran')
                    ->default('2026/2027')
                    ->required()
                    ->maxLength(20),

                Textarea::make('description')
                    ->label('Deskripsi Kelas')
                    ->placeholder('Keterangan singkat materi atau capaian belajar kelas')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('students')
                    ->label('Daftar Murid')
                    ->relationship('students', 'name', fn ($query) => $query->where('role', User::ROLE_MURID))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->helperText('Pilih murid yang terdaftar di kelas ini.'),

                Toggle::make('is_active')
                    ->label('Kelas Aktif')
                    ->default(true)
                    ->helperText('Jika dinonaktifkan, murid tidak dapat mengakses materi dan tugas kelas.'),
            ]);
    }
}
