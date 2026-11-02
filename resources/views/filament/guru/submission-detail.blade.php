<div class="space-y-4 text-sm text-gray-800 dark:text-gray-200">
    <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 space-y-1">
        <div><strong>Nama Murid:</strong> {{ $submission->student->name }}</div>
        <div><strong>Waktu Dikumpulkan:</strong> {{ $submission->submitted_at?->translatedFormat('d F Y, H:i') }} WIB</div>
        <div>
            <strong>Status:</strong> 
            @if($submission->is_late)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Terlambat</span>
            @else
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Tepat Waktu</span>
            @endif
        </div>
    </div>

    @if($submission->text_answer)
        <div>
            <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-1">Jawaban Teks:</h4>
            <div class="p-3 bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700 whitespace-pre-wrap leading-relaxed">
                {{ $submission->text_answer }}
            </div>
        </div>
    @endif

    @if($submission->file_path)
        <div>
            <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-1">Lampiran Berkas:</h4>
            <a href="{{ route('submissions.download', $submission) }}" class="inline-flex items-center gap-2 px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded font-medium text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Unduh {{ $submission->file_name ?: 'Berkas Jawaban' }}
            </a>
        </div>
    @endif

    @if($submission->isGraded())
        <div class="p-3 bg-blue-50 dark:bg-blue-950/40 rounded border border-blue-200 dark:border-blue-900">
            <div><strong>Nilai Saat Ini:</strong> {{ $submission->points }} / {{ $submission->assignment->max_points }}</div>
            @if($submission->teacher_feedback)
                <div class="mt-1"><strong>Catatan Guru:</strong> {{ $submission->teacher_feedback }}</div>
            @endif
        </div>
    @endif
</div>
