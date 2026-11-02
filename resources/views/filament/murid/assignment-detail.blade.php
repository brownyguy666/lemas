<div class="space-y-4 text-sm text-gray-800 dark:text-gray-200">
    <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 space-y-1">
        <div><strong>Mata Pelajaran:</strong> {{ $assignment->classroom->name }}</div>
        <div><strong>Guru Pengampu:</strong> {{ $assignment->classroom->teacher->name }}</div>
        <div><strong>Tenggat Waktu:</strong> {{ $assignment->due_date->translatedFormat('l, d F Y, H:i') }} WIB</div>
        <div><strong>Nilai Maksimal:</strong> {{ $assignment->max_points }} poin</div>
    </div>

    @if($assignment->instructions)
        <div>
            <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-1">Petunjuk & Soal Tugas:</h4>
            <div class="p-4 bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700 leading-relaxed prose dark:prose-invert max-w-none">
                {!! $assignment->instructions !!}
            </div>
        </div>
    @else
        <p class="text-gray-500 italic">Tidak ada petunjuk khusus untuk tugas ini.</p>
    @endif
</div>
