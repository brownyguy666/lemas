<?php

namespace App\Filament\Murid\Resources\Assignments\Tables;

use App\Models\Assignment;
use App\Models\Submission;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Tugas')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('classroom.name')
                    ->label('Mata Pelajaran / Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label('Tenggat Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status Pengumpulan')
                    ->badge()
                    ->state(function (Assignment $record): string {
                        $sub = $record->submissions->firstWhere('student_id', auth()->id());

                        if (! $sub) {
                            return $record->isPastDue() ? 'Belum Mengumpulkan (Lewat Tenggat)' : 'Belum Mengumpulkan';
                        }

                        if ($sub->isGraded()) {
                            return 'Sudah Dinilai ('.$sub->points.'/'.$record->max_points.')';
                        }

                        return $sub->is_late ? 'Sudah Dikumpulkan (Terlambat)' : 'Sudah Dikumpulkan';
                    })
                    ->color(function (Assignment $record): string {
                        $sub = $record->submissions->firstWhere('student_id', auth()->id());

                        if (! $sub) {
                            return $record->isPastDue() ? 'danger' : 'gray';
                        }

                        if ($sub->isGraded()) {
                            return 'success';
                        }

                        return $sub->is_late ? 'danger' : 'warning';
                    }),
            ])
            ->filters([
                SelectFilter::make('classroom_id')
                    ->label('Filter Kelas')
                    ->relationship('classroom', 'name', fn ($query) => $query->whereHas('students', fn ($q) => $q->where('users.id', auth()->id()))),
            ])
            ->recordActions([
                // 1. Aksi lihat petunjuk soal
                Action::make('viewInstructions')
                    ->label('Petunjuk Soal')
                    ->icon('heroicon-o-information-circle')
                    ->color('info')
                    ->modalHeading(fn (Assignment $record): string => $record->title)
                    ->modalContent(fn (Assignment $record) => view('filament.murid.assignment-detail', ['assignment' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                // 2. Aksi kumpulkan tugas
                Action::make('submitAssignment')
                    ->label(function (Assignment $record): string {
                        $sub = $record->submissions->firstWhere('student_id', auth()->id());

                        return $sub ? 'Ubah Jawaban' : 'Kumpulkan Tugas';
                    })
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->visible(function (Assignment $record): bool {
                        $sub = $record->submissions->firstWhere('student_id', auth()->id());

                        return ! $sub || ! $sub->isGraded();
                    })
                    ->modalHeading(fn (Assignment $record): string => 'Kumpulkan: '.$record->title)
                    ->fillForm(function (Assignment $record): array {
                        $sub = $record->submissions->firstWhere('student_id', auth()->id());

                        return [
                            'text_answer' => $sub?->text_answer,
                            'file_path' => $sub?->file_path,
                        ];
                    })
                    ->form([
                        Textarea::make('text_answer')
                            ->label('Jawaban / Penjelasan Teks')
                            ->placeholder('Ketikkan jawaban atau ringkasan penjelasan Anda di sini...')
                            ->rows(4),

                        FileUpload::make('file_path')
                            ->label('Unggah Berkas Tugas (Opsional)')
                            ->disk('public')
                            ->directory('submissions')
                            ->storeFileNamesIn('file_name')
                            ->maxSize(10240) // 10 MB
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'image/jpeg',
                                'image/png',
                            ])
                            ->helperText('Format didukung: PDF, Word, PowerPoint, Excel, Gambar. Maksimal 10 MB.'),
                    ])
                    ->action(function (Assignment $record, array $data): void {
                        $isLate = now()->gt($record->due_date);

                        Submission::updateOrCreate(
                            [
                                'assignment_id' => $record->id,
                                'student_id' => auth()->id(),
                            ],
                            [
                                'text_answer' => $data['text_answer'] ?? null,
                                'file_path' => $data['file_path'] ?? null,
                                'file_name' => isset($data['file_path']) ? basename($data['file_path']) : null,
                                'submitted_at' => now(),
                                'is_late' => $isLate,
                            ]
                        );

                        Notification::make()
                            ->success()
                            ->title('Tugas Berhasil Dikumpulkan!')
                            ->body($isLate ? 'Tugas Anda telah tersimpan dengan catatan: Dikumpulkan Terlambat.' : 'Tugas Anda telah tersimpan tepat waktu.')
                            ->send();
                    }),

                // 3. Aksi lihat nilai & catatan guru
                Action::make('viewGrade')
                    ->label('Lihat Nilai')
                    ->icon('heroicon-o-star')
                    ->color('success')
                    ->visible(function (Assignment $record): bool {
                        $sub = $record->submissions->firstWhere('student_id', auth()->id());

                        return $sub && $sub->isGraded();
                    })
                    ->modalHeading(fn (Assignment $record): string => 'Nilai: '.$record->title)
                    ->modalContent(function (Assignment $record) {
                        $sub = $record->submissions->firstWhere('student_id', auth()->id());

                        return view('filament.murid.grade-detail', ['submission' => $sub]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
            ])
            ->emptyStateHeading('Belum Ada Tugas')
            ->emptyStateDescription('Belum ada tugas baru yang diberikan oleh guru Anda.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }
}
