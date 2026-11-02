<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_GURU = 'guru';
    public const ROLE_MURID = 'murid';

    /**
     * Dapatkan daftar peran yang tersedia beserta label bahasa Indonesianya.
     *
     * @return array<string, string>
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_GURU => 'Guru',
            self::ROLE_MURID => 'Murid',
        ];
    }

    /**
     * Memeriksa apakah pengguna adalah Administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Memeriksa apakah pengguna adalah Guru.
     */
    public function isGuru(): bool
    {
        return $this->role === self::ROLE_GURU;
    }

    /**
     * Memeriksa apakah pengguna adalah Murid.
     */
    public function isMurid(): bool
    {
        return $this->role === self::ROLE_MURID;
    }

    /**
     * Memeriksa apakah pengguna memiliki salah satu peran yang valid di sistem.
     */
    public function hasValidRole(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_GURU, self::ROLE_MURID], true);
    }

    /**
     * Label peran dalam Bahasa Indonesia untuk ditampilkan di tabel/antarmuka.
     */
    public function getRoleLabelAttribute(): string
    {
        return self::getRoles()[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Menentukan apakah pengguna berhak mengakses panel tertentu.
     * Setiap panel terkunci ketat sesuai perannya.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->isAdmin(),
            'guru'  => $this->isGuru(),
            'murid' => $this->isMurid(),
            default => false,
        };
    }

    /**
     * Kelas-kelas yang diajar oleh guru ini.
     */
    public function teachingClassrooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    /**
     * Kelas-kelas yang diikuti oleh murid ini.
     */
    public function enrolledClassrooms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_student', 'student_id', 'classroom_id')
            ->withTimestamps();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
