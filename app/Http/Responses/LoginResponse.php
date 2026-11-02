<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements Responsable
{
    /**
     * Mengarahkan pengguna setelah berhasil login sesuai dengan perannya:
     * - Admin -> /admin
     * - Guru  -> /guru
     * - Murid -> /murid
     */
    public function toResponse($request): RedirectResponse | Redirector
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        if ($user?->isAdmin()) {
            return redirect()->intended('/admin');
        }

        if ($user?->isGuru()) {
            return redirect()->intended('/guru');
        }

        if ($user?->isMurid()) {
            return redirect()->intended('/murid');
        }

        return redirect()->intended('/');
    }
}

