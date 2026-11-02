<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;

class ClassroomPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat daftar kelas.
     * Admin, Guru, dan Murid yang valid diperbolehkan membuka halaman kelas.
     * (Data di masing-masing panel akan disaring otomatis sesuai perannya).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasValidRole();
    }

    /**
     * Tentukan apakah pengguna dapat melihat detail kelas tertentu:
     * - Admin: Boleh melihat kelas mana pun.
     * - Guru: Hanya boleh jika ia adalah guru pengampu kelas tersebut.
     * - Murid: Hanya boleh jika ia terdaftar sebagai murid di kelas tersebut.
     */
    public function view(User $user, Classroom $classroom): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $classroom->teacher_id;
        }

        if ($user->isMurid()) {
            return $classroom->students()->where('student_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Tentukan apakah pengguna dapat membuat kelas baru.
     * Hanya Admin dan Guru yang berhak membuat kelas.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isGuru();
    }

    /**
     * Tentukan apakah pengguna dapat mengubah data kelas:
     * - Admin: Boleh mengubah kelas mana pun.
     * - Guru: Hanya boleh mengubah kelas miliknya sendiri.
     */
    public function update(User $user, Classroom $classroom): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $classroom->teacher_id;
        }

        return false;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus kelas:
     * - Admin: Boleh menghapus kelas mana pun.
     * - Guru: Hanya boleh menghapus kelas miliknya sendiri.
     */
    public function delete(User $user, Classroom $classroom): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $classroom->teacher_id;
        }

        return false;
    }
}
