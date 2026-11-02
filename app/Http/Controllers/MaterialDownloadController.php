<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialDownloadController extends Controller
{
    /**
     * Mengunduh berkas materi secara aman dengan verifikasi hak akses.
     */
    public function __invoke(Request $request, Material $material): StreamedResponse
    {
        // 1. Verifikasi apakah pengguna berhak melihat materi ini sesuai policy
        if (! $request->user() || ! $request->user()->can('view', $material)) {
            abort(403, 'Anda tidak memiliki hak untuk mengunduh materi ini.');
        }

        // 2. Pastikan materi bertipe berkas dan path-nya ada
        if ($material->type !== Material::TYPE_FILE || blank($material->file_path)) {
            abort(404, 'Berkas materi tidak ditemukan.');
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($material->file_path)) {
            abort(404, 'Berkas tidak ditemukan pada server.');
        }

        $fileName = $material->file_name ?: basename($material->file_path);

        return $disk->download($material->file_path, $fileName);
    }
}
