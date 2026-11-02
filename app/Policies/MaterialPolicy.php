<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat daftar materi.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasValidRole();
    }

    /**
     * Tentukan apakah pengguna dapat melihat materi tertentu:
     * - Admin: Boleh melihat semua materi.
     * - Guru: Hanya boleh jika ia guru pengampu kelas materi ini.
     * - Murid: Hanya boleh jika materi terbit DAN murid terdaftar di kelas.
     */
    public function view(User $user, Material $material): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $material->classroom->teacher_id;
        }

        if ($user->isMurid()) {
            return $material->is_published &&
                $material->classroom->students()->where('student_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Tentukan apakah pengguna dapat membuat materi baru.
     * Hanya Admin dan Guru yang berhak.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isGuru();
    }

    /**
     * Tentukan apakah pengguna dapat mengubah materi:
     * - Admin: Boleh mengubah materi mana pun.
     * - Guru: Hanya boleh mengubah materi di kelas miliknya.
     */
    public function update(User $user, Material $material): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $material->classroom->teacher_id;
        }

        return false;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus materi:
     * - Admin: Boleh menghapus materi mana pun.
     * - Guru: Hanya boleh menghapus materi di kelas miliknya.
     */
    public function delete(User $user, Material $material): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $material->classroom->teacher_id;
        }

        return false;
    }
}
