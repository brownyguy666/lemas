<?php

namespace App\Providers\Filament;

use App\Filament\Guru\Widgets\GuruStatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class GuruPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('guru')
            ->path('guru')
            ->brandName('LEMAS - Ruang Guru')
            ->brandLogo(fn () => view('filament.components.brand-logo', [
                'role' => 'GURU',
                'badgeColor' => 'emerald',
            ]))
            ->brandLogoHeight('2.25rem')
            ->font('Plus Jakarta Sans')
            ->darkMode(true)
            ->colors([
                'primary' => Color::Teal,
                'gray' => Color::Slate,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => view('filament.components.custom-styles'),
            )
            ->discoverResources(in: app_path('Filament/Guru/Resources'), for: 'App\Filament\Guru\Resources')
            ->discoverPages(in: app_path('Filament/Guru/Pages'), for: 'App\Filament\Guru\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Guru/Widgets'), for: 'App\Filament\Guru\Widgets')
            ->widgets([
                GuruStatsOverview::class,
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
