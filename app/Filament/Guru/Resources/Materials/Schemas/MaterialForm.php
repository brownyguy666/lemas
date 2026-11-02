<?php

namespace App\Filament\Guru\Resources\Materials\Schemas;

use App\Models\Material;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('classroom_id')
                    ->label('Pilih Kelas')
                    ->relationship('classroom', 'name', fn ($query) => $query->where('teacher_id', auth()->id()))
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('title')
                    ->label('Judul Materi')
                    ->placeholder('Contoh: Bab 1: Bilangan Bulat dan Pecahan')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->label('Jenis Materi')
                    ->options(Material::getTypes())
                    ->default(Material::TYPE_TEXT)
                    ->required()
                    ->live()
                    ->native(false),

                TextInput::make('order')
                    ->label('Urutan Materi')
                    ->numeric()
                    ->default(1)
                    ->helperText('Urutan penampilan materi di ruang belajar murid.'),

                RichEditor::make('content')
                    ->label('Isi Bacaan / Modul Teks')
                    ->columnSpanFull()
                    ->visible(fn ($get): bool => $get('type') === Material::TYPE_TEXT),

                FileUpload::make('file_path')
                    ->label('Unggah Berkas Modul / Dokumen')
                    ->disk('public')
                    ->directory('materials')
                    ->storeFileNamesIn('file_name')
                    ->maxSize(10240) // 10 MB
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'image/jpeg',
                        'image/png',
                    ])
                    ->helperText('Format yang didukung: PDF, Word, PowerPoint, Excel, Gambar. Maksimal 10 MB.')
                    ->columnSpanFull()
                    ->visible(fn ($get): bool => $get('type') === Material::TYPE_FILE),

                TextInput::make('link_url')
                    ->label('Tautan Video Edukasi')
                    ->url()
                    ->placeholder('Contoh: https://www.youtube.com/watch?v=...')
                    ->columnSpanFull()
                    ->visible(fn ($get): bool => $get('type') === Material::TYPE_VIDEO),

                Toggle::make('is_published')
                    ->label('Terbitkan Materi Sekarang')
                    ->default(true)
                    ->helperText('Jika dinonaktifkan, materi berstatus draf dan belum bisa dilihat murid.'),
            ]);
    }
}
