<?php

namespace App\Filament\Guru\Resources\Assignments\RelationManagers;

use App\Models\Submission;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    protected static ?string $title = 'Pengumpulan Jawaban & Penilaian Murid';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nama Murid')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('submitted_at')
                    ->label('Waktu Kumpul')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('is_late')
                    ->label('Status Kumpul')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Terlambat' : 'Tepat Waktu')
                    ->color(fn (bool $state): string => $state ? 'danger' : 'success')
                    ->sortable(),

                TextColumn::make('points')
                    ->label('Nilai')
                    ->badge()
                    ->formatStateUsing(fn (Submission $record): string => is_null($record->points)
                        ? 'Belum Dinilai'
                        : ($record->points.' / '.$record->assignment->max_points)
                    )
                    ->color(fn (Submission $record): string => is_null($record->points) ? 'warning' : 'success')
                    ->sortable(),

                TextColumn::make('teacher_feedback')
                    ->label('Catatan Guru')
                    ->limit(25)
                    ->placeholder('Belum ada catatan'),
            ])
            ->headerActions([])
            ->recordActions([
                // Aksi lihat detail jawaban murid
                Action::make('viewSubmission')
                    ->label('Lihat Jawaban')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn (Submission $record): string => 'Jawaban: '.$record->student->name)
                    ->modalContent(fn (Submission $record) => view('filament.guru.submission-detail', ['submission' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                // Aksi beri nilai dan evaluasi
                Action::make('gradeSubmission')
                    ->label('Beri Nilai')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->modalHeading(fn (Submission $record): string => 'Beri Nilai: '.$record->student->name)
                    ->fillForm(fn (Submission $record): array => [
                        'points' => $record->points,
                        'teacher_feedback' => $record->teacher_feedback,
                    ])
                    ->form([
                        TextInput::make('points')
                            ->label('Nilai Angka')
                            ->helperText(fn (Submission $record): string => 'Nilai maksimal tugas ini adalah '.$record->assignment->max_points)
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(fn (Submission $record): int => $record->assignment->max_points),

                        Textarea::make('teacher_feedback')
                            ->label('Catatan / Evaluasi untuk Murid')
                            ->placeholder('Contoh: Bagus sekali! Analisis sudah lengkap, namun perhatikan rumus di nomor 3.')
                            ->rows(3),
                    ])
                    ->action(function (Submission $record, array $data): void {
                        $record->update([
                            'points' => $data['points'],
                            'teacher_feedback' => $data['teacher_feedback'],
                            'graded_at' => now(),
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Nilai Berhasil Disimpan!')
                            ->body('Nilai untuk '.$record->student->name.' telah diperbarui.')
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Belum Ada Pengumpulan')
            ->emptyStateDescription('Belum ada murid yang mengumpulkan jawaban untuk tugas ini.')
            ->emptyStateIcon('heroicon-o-inbox');
    }
}
