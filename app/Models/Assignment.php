<?php

namespace App\Models;

use Database\Factories\AssignmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['classroom_id', 'title', 'instructions', 'due_date', 'max_points', 'is_published'])]
class Assignment extends Model
{
    /** @use HasFactory<AssignmentFactory> */
    use HasFactory;

    /**
     * Kelas tempat tugas ini diberikan.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Seluruh pengumpulan jawaban dari murid.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Pengumpulan jawaban khusus milik murid yang sedang login.
     */
    public function studentSubmission(?int $studentId = null): HasOne
    {
        $id = $studentId ?: auth()->id();

        return $this->hasOne(Submission::class)->where('student_id', $id);
    }

    /**
     * Memeriksa apakah tenggat waktu tugas telah lewat.
     */
    public function isPastDue(): bool
    {
        return now()->gt($this->due_date);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'max_points' => 'integer',
            'is_published' => 'boolean',
        ];
    }
}
