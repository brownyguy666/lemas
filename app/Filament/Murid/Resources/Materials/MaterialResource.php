<?php

namespace App\Filament\Murid\Resources\Materials;

use App\Filament\Murid\Resources\Materials\Pages\ListMaterials;
use App\Filament\Murid\Resources\Materials\Tables\MaterialsTable;
use App\Models\Material;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Materi Pelajaran';

    protected static ?string $modelLabel = 'Materi';

    protected static ?string $pluralModelLabel = 'Materi Pelajaran';

    /**
     * Membatasi agar murid HANYA melihat materi yang sudah terbit di kelas yang diikutinya.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_published', true)
            ->whereHas('classroom', fn ($query) => $query->where('is_active', true)
                ->whereHas('students', fn ($studentQuery) => $studentQuery->where('users.id', auth()->id()))
            )
            ->orderBy('order', 'asc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return MaterialsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMaterials::route('/'),
        ];
    }
}
