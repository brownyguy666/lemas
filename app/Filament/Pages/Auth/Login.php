<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getTitle(): string|Htmlable
    {
        return 'Masuk - LEMAS LMS Sekolah';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Masuk ke Akun Anda';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Gunakan akun Administrator, Guru, atau Murid Anda';
    }

    /**
     * Memperbolehkan otentikasi di halaman login bersama jika pengguna
     * memiliki peran yang sah di sistem LEMAS (Admin, Guru, atau Murid).
     * Pengalihan ke masing-masing panel ditangani oleh LoginResponse.
     */
    protected function isUserAllowedToAccessPanel(Authenticatable $user): bool
    {
        return method_exists($user, 'hasValidRole') && $user->hasValidRole();
    }
}
