<?php

namespace App\Filament\Guru\Resources\Materials;

use App\Filament\Guru\Resources\Materials\Pages\CreateMaterial;
use App\Filament\Guru\Resources\Materials\Pages\EditMaterial;
use App\Filament\Guru\Resources\Materials\Pages\ListMaterials;
use App\Filament\Guru\Resources\Materials\Schemas\MaterialForm;
use App\Filament\Guru\Resources\Materials\Tables\MaterialsTable;
use App\Models\Material;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Materi Pelajaran';

    protected static ?string $modelLabel = 'Materi';

    protected static ?string $pluralModelLabel = 'Materi Pelajaran';

    /**
     * Membatasi materi HANYA pada kelas-kelas yang diajar oleh guru ini.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('classroom', fn ($query) => $query->where('teacher_id', auth()->id()));
    }

    public static function form(Schema $schema): Schema
    {
        return MaterialForm::configure($schema);
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
            'create' => CreateMaterial::route('/create'),
            'edit' => EditMaterial::route('/{record}/edit'),
        ];
    }
}
