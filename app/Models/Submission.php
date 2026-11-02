<?php

namespace App\Models;

use Database\Factories\SubmissionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'assignment_id',
    'student_id',
    'text_answer',
    'file_path',
    'file_name',
    'submitted_at',
    'is_late',
    'points',
    'teacher_feedback',
    'graded_at',
])]
class Submission extends Model
{
    /** @use HasFactory<SubmissionFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (Submission $submission) {
            if ($submission->assignment && $submission->submitted_at) {
                $submission->is_late = $submission->submitted_at->gt($submission->assignment->due_date);
            }
        });
    }

    /**
     * Tugas yang dikerjakan.
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Murid yang mengumpulkan tugas.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Memeriksa apakah tugas sudah dinilai oleh guru.
     */
    public function isGraded(): bool
    {
        return ! is_null($this->points);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'is_late' => 'boolean',
            'points' => 'integer',
            'graded_at' => 'datetime',
        ];
    }
}
