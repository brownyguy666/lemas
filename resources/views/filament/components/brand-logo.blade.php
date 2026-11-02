@props([
    'role' => 'ADMIN',
    'badgeColor' => 'indigo',
])

@php
    $gradColors = match($badgeColor) {
        'emerald' => ['start' => '#10b981', 'stop' => '#06b6d4', 'text' => '#34d399', 'bg' => 'rgba(16, 185, 129, 0.12)', 'border' => 'rgba(16, 185, 129, 0.3)'],
        'cyan' => ['start' => '#06b6d4', 'stop' => '#3b82f6', 'text' => '#38bdf8', 'bg' => 'rgba(6, 182, 212, 0.12)', 'border' => 'rgba(6, 182, 212, 0.3)'],
        default => ['start' => '#6366f1', 'stop' => '#a855f7', 'text' => '#818cf8', 'bg' => 'rgba(99, 102, 241, 0.12)', 'border' => 'rgba(99, 102, 241, 0.3)'],
    };
@endphp

<div style="display: inline-flex; align-items: center; gap: 10px; height: 36px; max-height: 36px; user-select: none; text-align: left;">
    {{-- Futuristic Geometric Hex Icon (Strictly 32x32px) --}}
    <div style="width: 32px; height: 32px; min-width: 32px; max-width: 32px; min-height: 32px; max-height: 32px; border-radius: 8px; background: #0f172a; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.12); flex-shrink: 0; position: relative;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px; min-width: 18px; max-width: 18px; min-height: 18px; max-height: 18px; display: block;">
            <defs>
                <linearGradient id="lemasGrad-{{ $badgeColor }}" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="{{ $gradColors['start'] }}" />
                    <stop offset="100%" stop-color="{{ $gradColors['stop'] }}" />
                </linearGradient>
            </defs>
            <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="url(#lemasGrad-{{ $badgeColor }})" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 17L12 22L22 17" stroke="url(#lemasGrad-{{ $badgeColor }})" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 12L12 17L22 12" stroke="url(#lemasGrad-{{ $badgeColor }})" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>

    {{-- Brand Typography --}}
    <div style="display: flex; flex-direction: column; justify-content: center; line-height: 1.15;">
        <div style="display: flex; align-items: center; gap: 6px;">
            <span style="font-size: 15px; font-weight: 800; letter-spacing: 0.05em; color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif;">
                LEMAS
            </span>
            <span style="font-size: 9px; font-weight: 700; letter-spacing: 0.1em; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; color: {{ $gradColors['text'] }}; background: {{ $gradColors['bg'] }}; border: 1px solid {{ $gradColors['border'] }}; line-height: 1;">
                {{ $role }}
            </span>
        </div>
        <span style="font-size: 9px; font-weight: 500; letter-spacing: 0.15em; color: #94a3b8; text-transform: uppercase; margin-top: 2px; line-height: 1;">
            Learning Space
        </span>
    </div>
</div>
