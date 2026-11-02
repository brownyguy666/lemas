<div class="space-y-4 text-sm text-gray-800 dark:text-gray-200">
    <div class="text-center p-6 bg-green-50 dark:bg-green-950/40 rounded-xl border border-green-200 dark:border-green-800">
        <div class="text-xs uppercase tracking-wider text-green-700 dark:text-green-300 font-semibold mb-1">Nilai Akhir Anda</div>
        <div class="text-4xl font-extrabold text-green-600 dark:text-green-400">
            {{ $submission->points }} <span class="text-xl font-normal text-gray-500">/ {{ $submission->assignment->max_points }}</span>
        </div>
        <div class="mt-2 text-xs text-gray-500">
            Dinilai pada: {{ $submission->graded_at?->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>

    @if($submission->teacher_feedback)
        <div class="p-4 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
            <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                <span>💬</span> Catatan & Masukan dari Guru:
            </h4>
            <p class="text-gray-700 dark:text-gray-300 italic leading-relaxed whitespace-pre-wrap">
                "{{ $submission->teacher_feedback }}"
            </p>
        </div>
    @else
        <p class="text-gray-500 text-center italic">Guru tidak menyematkan catatan tambahan.</p>
    @endif
</div>

