<?php

namespace App\Filament\Resources\Assignments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('classroom_id')
                    ->label('Kelas')
                    ->relationship('classroom', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('title')
                    ->label('Judul Tugas')
                    ->placeholder('Contoh: Tugas 1: Menyelesaikan Persamaan Linear')
                    ->required()
                    ->maxLength(255),

                DateTimePicker::make('due_date')
                    ->label('Batas Tenggat Waktu (Deadline)')
                    ->required()
                    ->native(false)
                    ->helperText('Pengumpulan setelah waktu ini akan ditandai Terlambat otomatis.'),

                TextInput::make('max_points')
                    ->label('Nilai Maksimal')
                    ->numeric()
                    ->default(100)
                    ->minValue(1)
                    ->maxValue(1000)
                    ->required(),

                RichEditor::make('instructions')
                    ->label('Petunjuk & Soal Tugas')
                    ->columnSpanFull()
                    ->placeholder('Tuliskan petunjuk pengerjaan dan detail soal tugas di sini...'),

                Toggle::make('is_published')
                    ->label('Terbitkan Tugas Sekarang')
                    ->default(true)
                    ->helperText('Jika dinonaktifkan, tugas berstatus draf dan belum tampil di akun murid.'),
            ]);
    }
}
