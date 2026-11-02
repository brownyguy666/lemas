<?php

namespace App\Filament\Guru\Resources\Assignments;

use App\Filament\Guru\Resources\Assignments\Pages\CreateAssignment;
use App\Filament\Guru\Resources\Assignments\Pages\EditAssignment;
use App\Filament\Guru\Resources\Assignments\Pages\ListAssignments;
use App\Filament\Guru\Resources\Assignments\RelationManagers\SubmissionsRelationManager;
use App\Filament\Guru\Resources\Assignments\Schemas\AssignmentForm;
use App\Filament\Guru\Resources\Assignments\Tables\AssignmentsTable;
use App\Models\Assignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AssignmentResource extends Resource
{
    protected static ?string $model = Assignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Tugas & Nilai';

    protected static ?string $modelLabel = 'Tugas';

    protected static ?string $pluralModelLabel = 'Daftar Tugas & Penilaian';

    /**
     * Membatasi tugas HANYA pada kelas-kelas yang diajar oleh guru ini.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('classroom', fn ($query) => $query->where('teacher_id', auth()->id()));
    }

    public static function form(Schema $schema): Schema
    {
        return AssignmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssignmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SubmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssignments::route('/'),
            'create' => CreateAssignment::route('/create'),
            'edit' => EditAssignment::route('/{record}/edit'),
        ];
    }
}
