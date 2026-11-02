<?php

namespace App\Filament\Resources\Classrooms\Schemas;

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
                    ->placeholder('Contoh: Matematika Kelas 7A')
                    ->required()
                    ->maxLength(255),

                Select::make('teacher_id')
                    ->label('Guru Pengampu')
                    ->relationship('teacher', 'name', fn ($query) => $query->where('role', User::ROLE_GURU))
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('academic_year')
                    ->label('Tahun Ajaran')
                    ->default('2026/2027')
                    ->required()
                    ->maxLength(20),

                Textarea::make('description')
                    ->label('Deskripsi Kelas')
                    ->placeholder('Keterangan singkat mengenai mata pelajaran atau kelas ini')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('students')
                    ->label('Murid Terdaftar')
                    ->relationship('students', 'name', fn ($query) => $query->where('role', User::ROLE_MURID))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->helperText('Pilih murid yang ingin dimasukkan ke dalam kelas ini.'),

                Toggle::make('is_active')
                    ->label('Kelas Aktif')
                    ->default(true)
                    ->helperText('Kelas yang aktif dapat diakses oleh guru dan murid.'),
            ]);
    }
}
