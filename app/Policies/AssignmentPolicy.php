<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat daftar tugas.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasValidRole();
    }

    /**
     * Tentukan apakah pengguna dapat melihat tugas tertentu:
     * - Admin: Boleh melihat semua tugas.
     * - Guru: Hanya boleh jika ia guru pengampu kelas tugas ini.
     * - Murid: Hanya boleh jika tugas terbit DAN murid terdaftar di kelas.
     */
    public function view(User $user, Assignment $assignment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $assignment->classroom->teacher_id;
        }

        if ($user->isMurid()) {
            return $assignment->is_published &&
                $assignment->classroom->students()->where('student_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Tentukan apakah pengguna dapat membuat tugas baru.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isGuru();
    }

    /**
     * Tentukan apakah pengguna dapat memperbarui tugas:
     * - Admin: Boleh.
     * - Guru: Hanya tugas di kelas miliknya.
     */
    public function update(User $user, Assignment $assignment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $assignment->classroom->teacher_id;
        }

        return false;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus tugas:
     * - Admin: Boleh.
     * - Guru: Hanya tugas di kelas miliknya.
     */
    public function delete(User $user, Assignment $assignment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $assignment->classroom->teacher_id;
        }

        return false;
    }
}
