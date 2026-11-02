<?php

namespace App\Filament\Guru\Resources\Materials\Tables;

use App\Models\Material;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
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
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Material::getTypes()[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        Material::TYPE_TEXT => 'gray',
                        Material::TYPE_FILE => 'warning',
                        Material::TYPE_VIDEO => 'danger',
                        default => 'primary',
                    })
                    ->sortable(),

                TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                IconColumn::make('is_published')
                    ->label('Status Terbit')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('classroom_id')
                    ->label('Filter Kelas')
                    ->relationship('classroom', 'name', fn ($query) => $query->where('teacher_id', auth()->id())),

                SelectFilter::make('type')
                    ->label('Filter Jenis')
                    ->options(Material::getTypes()),

                TernaryFilter::make('is_published')
                    ->label('Status Terbit'),
            ])
            ->recordActions([
                EditAction::make()->label('Kelola Materi'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ]);
    }
}
