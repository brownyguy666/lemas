<?php

namespace App\Filament\Murid\Resources\Assignments;

use App\Filament\Murid\Resources\Assignments\Pages\ListAssignments;
use App\Filament\Murid\Resources\Assignments\Tables\AssignmentsTable;
use App\Models\Assignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AssignmentResource extends Resource
{
    protected static ?string $model = Assignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Tugas Saya';

    protected static ?string $modelLabel = 'Tugas';

    protected static ?string $pluralModelLabel = 'Daftar Tugas Saya';

    /**
     * Membatasi agar murid HANYA melihat tugas yang terbit dari kelas yang diikutinya.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_published', true)
            ->whereHas('classroom', fn ($query) => $query->where('is_active', true)
                ->whereHas('students', fn ($studentQuery) => $studentQuery->where('users.id', auth()->id()))
            )
            ->with(['submissions' => fn ($query) => $query->where('student_id', auth()->id())])
            ->orderBy('due_date', 'asc');
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
        return AssignmentsTable::configure($table);
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
            'index' => ListAssignments::route('/'),
        ];
    }
}
