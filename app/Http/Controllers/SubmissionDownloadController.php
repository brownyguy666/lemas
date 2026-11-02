<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionDownloadController extends Controller
{
    /**
     * Mengunduh berkas jawaban tugas murid dengan verifikasi hak akses ketat.
     */
    public function __invoke(Request $request, Submission $submission): StreamedResponse
    {
        // 1. Verifikasi hak akses via policy
        if (! $request->user() || ! $request->user()->can('view', $submission)) {
            abort(403, 'Anda tidak memiliki hak untuk mengunduh berkas ini.');
        }

        // 2. Pastikan file ada
        if (blank($submission->file_path)) {
            abort(404, 'Berkas jawaban tidak ditemukan.');
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($submission->file_path)) {
            abort(404, 'Berkas tidak ditemukan di server.');
        }

        $fileName = $submission->file_name ?: ('Jawaban_' . ($submission->student->name ?? 'Murid') . '_' . basename($submission->file_path));

        return $disk->download($submission->file_path, $fileName);
    }
}
