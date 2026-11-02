<?php

namespace App\Filament\Murid\Resources\Materials\Tables;

use App\Models\Material;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Materi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('classroom.name')
                    ->label('Mata Pelajaran / Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Format')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Material::getTypes()[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        Material::TYPE_TEXT => 'gray',
                        Material::TYPE_FILE => 'warning',
                        Material::TYPE_VIDEO => 'danger',
                        default => 'primary',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibagikan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('classroom_id')
                    ->label('Filter Kelas')
                    ->relationship('classroom', 'name', fn ($query) => $query->whereHas('students', fn ($q) => $q->where('users.id', auth()->id()))),
            ])
            ->recordActions([
                // Aksi untuk Tipe Teks: Baca langsung di modal pop-up
                Action::make('read')
                    ->label('Baca Materi')
                    ->icon('heroicon-o-book-open')
                    ->color('primary')
                    ->visible(fn (Material $record): bool => $record->type === Material::TYPE_TEXT)
                    ->modalHeading(fn (Material $record): string => $record->title)
                    ->modalContent(fn (Material $record) => view('filament.murid.material-reader', ['material' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                // Aksi untuk Tipe Berkas: Unduh langsung
                Action::make('download')
                    ->label('Unduh Berkas')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('warning')
                    ->visible(fn (Material $record): bool => $record->type === Material::TYPE_FILE && filled($record->file_path))
                    ->url(fn (Material $record): string => route('materials.download', $record)),

                // Aksi untuk Tipe Video: Buka tautan YouTube
                Action::make('watch')
                    ->label('Tonton Video')
                    ->icon('heroicon-o-play')
                    ->color('danger')
                    ->visible(fn (Material $record): bool => $record->type === Material::TYPE_VIDEO && filled($record->link_url))
                    ->url(fn (Material $record): string => $record->link_url)
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('Belum Ada Materi Pelajaran')
            ->emptyStateDescription('Guru belum membagikan materi baru di kelas yang Anda ikuti.')
            ->emptyStateIcon('heroicon-o-book-open');
    }
}
