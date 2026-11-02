@props([
    'role' => 'SYSTEM',
    'badgeColor' => 'indigo',
])

@php
    $colorClasses = match($badgeColor) {
        'emerald' => 'from-emerald-400 via-teal-500 to-cyan-500 text-emerald-400 border-emerald-500/30 bg-emerald-500/10',
        'cyan' => 'from-cyan-400 via-sky-500 to-blue-500 text-cyan-400 border-cyan-500/30 bg-cyan-500/10',
        default => 'from-indigo-400 via-violet-500 to-purple-500 text-indigo-400 border-indigo-500/30 bg-indigo-500/10',
    };

    $svgGradient = match($badgeColor) {
        'emerald' => ['start' => '#10b981', 'stop' => '#06b6d4'],
        'cyan' => ['start' => '#06b6d4', 'stop' => '#3b82f6'],
        default => ['start' => '#6366f1', 'stop' => '#a855f7'],
    };
@endphp

<div class="flex items-center gap-3 select-none">
    {{-- Futuristic Geometric Hex-Icon --}}
    <div class="relative flex items-center justify-center w-9 h-9 rounded-xl bg-slate-900 shadow-md ring-1 ring-white/10 overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-tr from-slate-900 via-slate-800 to-slate-950"></div>
        <svg class="relative w-5 h-5 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="lemasGrad-{{ $badgeColor }}" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="{{ $svgGradient['start'] }}" />
                    <stop offset="100%" stop-color="{{ $svgGradient['stop'] }}" />
                </linearGradient>
            </defs>
            <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="url(#lemasGrad-{{ $badgeColor }})" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 17L12 22L22 17" stroke="url(#lemasGrad-{{ $badgeColor }})" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 12L12 17L22 12" stroke="url(#lemasGrad-{{ $badgeColor }})" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{-- Subtle Neon Accent Dot --}}
        <div class="absolute -top-1 -right-1 w-2.5 h-2.5 rounded-full bg-{{ $badgeColor }}-400 blur-[2px] opacity-70"></div>
    </div>

    {{-- Brand Typography --}}
    <div class="flex flex-col leading-none">
        <div class="flex items-center gap-1.5">
            <span class="text-base font-extrabold tracking-wider bg-clip-text text-transparent bg-gradient-to-r {{ $colorClasses }}">
                LEMAS
            </span>
            <span class="text-xs font-semibold px-1.5 py-0.5 rounded-md border text-[10px] tracking-widest uppercase {{ $colorClasses }}">
                {{ $role }}
            </span>
        </div>
        <span class="text-[10px] tracking-widest text-slate-400 font-medium uppercase mt-0.5">
            Learning Space
        </span>
    </div>
</div>
