<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\GuruPanelProvider;
use App\Providers\Filament\MuridPanelProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    GuruPanelProvider::class,
    MuridPanelProvider::class,
];
