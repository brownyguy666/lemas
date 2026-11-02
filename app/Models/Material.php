<?php

namespace App\Models;

use Database\Factories\MaterialFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['classroom_id', 'title', 'type', 'content', 'file_path', 'file_name', 'link_url', 'order', 'is_published'])]
class Material extends Model
{
    /** @use HasFactory<MaterialFactory> */
    use HasFactory;

    public const TYPE_TEXT = 'text';
    public const TYPE_FILE = 'file';
    public const TYPE_VIDEO = 'video';

    /**
     * Daftar tipe materi beserta label Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_TEXT  => 'Teks / Artikel',
            self::TYPE_FILE  => 'Unggah Berkas (PDF/Dokumen)',
            self::TYPE_VIDEO => 'Tautan Video (YouTube)',
        ];
    }

    /**
     * Kelas tempat materi ini dibagikan.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Label tipe materi untuk tampilan tabel/antarmuka.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::getTypes()[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'order' => 'integer',
        ];
    }
}
