<div class="prose dark:prose-invert max-w-none py-2 text-gray-800 dark:text-gray-200">
    <div class="mb-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
        Kelas: {{ $material->classroom->name }} | Guru: {{ $material->classroom->teacher->name }}
    </div>
    
    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 leading-relaxed">
        {!! $material->content !!}
    </div>
</div>
