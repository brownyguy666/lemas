<?php

namespace App\Filament\Guru\Resources\Classrooms;

use App\Filament\Guru\Resources\Classrooms\Pages\CreateClassroom;
use App\Filament\Guru\Resources\Classrooms\Pages\EditClassroom;
use App\Filament\Guru\Resources\Classrooms\Pages\ListClassrooms;
use App\Filament\Guru\Resources\Classrooms\Schemas\ClassroomForm;
use App\Filament\Guru\Resources\Classrooms\Tables\ClassroomsTable;
use App\Models\Classroom;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Kelas Saya';

    protected static ?string $modelLabel = 'Kelas';

    protected static ?string $pluralModelLabel = 'Daftar Kelas Saya';

    /**
     * Membatasi agar guru HANYA bisa melihat kelas miliknya sendiri.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('teacher_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return ClassroomForm::configure($schema);
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
            'create' => CreateClassroom::route('/create'),
            'edit' => EditClassroom::route('/{record}/edit'),
        ];
    }
}
