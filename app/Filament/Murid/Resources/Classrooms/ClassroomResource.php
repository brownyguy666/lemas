<?php

namespace App\Filament\Murid\Resources\Classrooms;

use App\Filament\Murid\Resources\Classrooms\Pages\ListClassrooms;
use App\Filament\Murid\Resources\Classrooms\Tables\ClassroomsTable;
use App\Models\Classroom;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Kelas Saya';

    protected static ?string $modelLabel = 'Kelas';

    protected static ?string $pluralModelLabel = 'Daftar Kelas Saya';

    /**
     * Membatasi agar murid HANYA melihat kelas yang ia ikuti dan masih aktif.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_active', true)
            ->whereHas('students', fn ($query) => $query->where('users.id', auth()->id()));
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
        return ClassroomsTable::configure($table);
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
            'index' => ListClassrooms::route('/'),
        ];
    }
}
