<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat daftar seluruh akun.
     * Hanya Admin yang berhak.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Tentukan apakah pengguna dapat melihat detail akun tertentu.
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Tentukan apakah pengguna dapat membuat akun baru.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Tentukan apakah pengguna dapat memperbarui akun.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus akun.
     * Admin tidak boleh menghapus akunnya sendiri agar sistem tidak terkunci.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Tentukan apakah pengguna dapat memulihkan akun yang dihapus.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Tentukan apakah pengguna dapat menghapus permanen akun.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
