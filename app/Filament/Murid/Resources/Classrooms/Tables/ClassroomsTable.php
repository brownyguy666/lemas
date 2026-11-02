<?php

namespace App\Filament\Murid\Resources\Classrooms\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClassroomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('teacher.name')
                    ->label('Guru Pengampu')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('academic_year')
                    ->label('Tahun Ajaran')
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(60)
                    ->placeholder('Tidak ada keterangan'),
            ])
            ->emptyStateHeading('Belum Ada Kelas')
            ->emptyStateDescription('Anda belum bergabung ke kelas mana pun. Gunakan tombol "Gabung Kelas dengan Kode" di atas untuk mulai belajar!')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}
