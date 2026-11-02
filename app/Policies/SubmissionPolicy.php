<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;

class SubmissionPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat daftar pengumpulan.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasValidRole();
    }

    /**
     * Tentukan apakah pengguna dapat melihat pengumpulan tertentu:
     * - Admin: Boleh melihat.
     * - Guru: Boleh melihat jika ia guru pengampu kelas tugas tersebut.
     * - Murid: HANYA boleh melihat pengumpulan miliknya sendiri (privat).
     */
    public function view(User $user, Submission $submission): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $submission->assignment->classroom->teacher_id;
        }

        if ($user->isMurid()) {
            return $user->id === $submission->student_id;
        }

        return false;
    }

    /**
     * Tentukan apakah pengguna dapat mengumpulkan tugas.
     * Hanya Murid yang berhak mengumpulkan.
     */
    public function create(User $user): bool
    {
        return $user->isMurid();
    }

    /**
     * Tentukan apakah pengguna dapat memperbarui pengumpulan:
     * - Admin: Boleh.
     * - Guru: Boleh memberi nilai/catatan untuk kelasnya.
     * - Murid: Boleh mengedit jawabannya jika belum dinilai.
     */
    public function update(User $user, Submission $submission): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $submission->assignment->classroom->teacher_id;
        }

        if ($user->isMurid()) {
            return $user->id === $submission->student_id && ! $submission->isGraded();
        }

        return false;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus pengumpulan.
     */
    public function delete(User $user, Submission $submission): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isGuru()) {
            return $user->id === $submission->assignment->classroom->teacher_id;
        }

        return false;
    }
}
