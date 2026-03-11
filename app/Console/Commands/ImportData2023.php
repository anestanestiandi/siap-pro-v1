<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PelayananKeprotokolan;
use App\Models\Persidangan;
use Illuminate\Support\Facades\DB;

class ImportData2023 extends Command
{
    protected $signature = 'import:siappro-2023';
    protected $description = 'Import data Pelayanan Keprotokolan dan Persidangan tahun 2023 dari CSV';

    public function handle()
    {
        $this->info('Starting Import Data 2023...');

        // 1. Import Pelayanan Keprotokolan
        $this->importProtokol();

        // 2. Import Persidangan
        $this->importPersidangan();

        $this->info('Import success dan data sudah bersih!');
    }

    private function importProtokol()
    {
        $path = storage_path('app/protokol_2023.csv');
        if (!file_exists($path)) {
            $this->warn('File protokol_2023.csv tidak ditemukan. Melewati...');
            return;
        }

        $this->info('Importing Pelayanan Keprotokolan...');
        $file = fopen($path, 'r');
        $header = fgetcsv($file); 

        while (($row = fgetcsv($file)) !== FALSE) {
            try {
                // Konversi encoding ke UTF-8 untuk menangani karakter spesial (seperti é)
                $row = array_map(function($item) {
                    return mb_convert_encoding($item, 'UTF-8', 'ISO-8859-1, UTF-8, Windows-1252');
                }, $row);

                if (count($header) !== count($row)) {
                    $this->warn('Jumlah kolom tidak cocok pada baris. Melewati...');
                    continue;
                }

                $data = array_combine($header, $row);

                // Skip jika tanggal kosong (mencegah error SQL)
                if (empty($data['tanggal_kegiatan']) || trim($data['tanggal_kegiatan']) == '') {
                    $this->warn('Melewati baris "' . ($data['nama_kegiatan'] ?? 'Tanpa Nama') . '" karena tanggal kosong.');
                    continue;
                }

                $waktuRaw = $data['waktu'] ?? '';
                if (preg_match('/(\d{1,2})[:.](\d{2})/', $waktuRaw, $matches)) {
                    $hh = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                    $mm = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                    $waktuClean = "$hh:$mm:00";
                } else {
                    $waktuClean = '00:00:00';
                }

                $anggotaIds = $this->parseJsonArray($data['id_anggota'] ?? '[]');
                $petugasIds = $this->parseJsonArray($data['id_petuga'] ?? $data['id_petugas'] ?? '[]');

                PelayananKeprotokolan::create([
                    'tanggal_kegiatan' => $data['tanggal_kegiatan'] ?? $data['tanggal_p'] ?? null,
                    'waktu' => $waktuClean,
                    'nama_kegiatan' => $data['nama_kegiatan'] ?? $data['nama_persidangan'] ?? null,
                    'tempat' => $data['tempat'] ?? null,
                    'id_jenis_pelayanan' => $data['id_jenis_pelayanan'] ?? $data['id_jenis_p'] ?? 1,
                    'id_anggota' => $anggotaIds, 
                    'id_petugas' => $petugasIds,
                    'created_by' => $data['created_by'] ?? 1,
                    'created_at' => now(),
                ]);
            } catch (\Exception $e) {
                $this->error('Gagal import baris: ' . ($data['nama_kegiatan'] ?? 'Tanpa Nama') . '. Error: ' . $e->getMessage());
            }
        }
        fclose($file);
    }

    private function importPersidangan()
    {
        $path = storage_path('app/persidangan_2023.csv');
        if (!file_exists($path)) {
            $this->warn('File persidangan_2023.csv tidak ditemukan. Melewati...');
            return;
        }

        $this->info('Importing Persidangan...');
        $file = fopen($path, 'r');
        $header = fgetcsv($file); 

        while (($row = fgetcsv($file)) !== FALSE) {
            try {
                $row = array_map(function($item) {
                    return mb_convert_encoding($item, 'UTF-8', 'ISO-8859-1, UTF-8, Windows-1252');
                }, $row);

                if (count($header) !== count($row)) continue;

                $data = array_combine($header, $row);

                $waktuRaw = $data['waktu'] ?? '';
                if (preg_match('/(\d{1,2})[:.](\d{2})/', $waktuRaw, $matches)) {
                    $hh = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                    $mm = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                    $waktuClean = "$hh:$mm:00";
                } else {
                    $waktuClean = '00:00:00';
                }

                $anggotaIds = $this->parseJsonArray($data['id_anggota'] ?? '[]');
                $petugasIds = $this->parseJsonArray($data['id_petuga'] ?? $data['id_petugas'] ?? '[]');

                Persidangan::create([
                    'tanggal_persidangan' => $data['tanggal_persidangan'] ?? $data['tanggal_p'] ?? null,
                    'waktu' => $waktuClean,
                    'nama_persidangan' => $data['nama_persidangan'] ?? null,
                    'tempat' => $data['tempat'] ?? null,
                    'id_jenis_persidangan' => $data['id_jenis_persidangan'] ?? $data['id_jenis_p'] ?? 1,
                    'id_anggota' => $anggotaIds,
                    'id_petugas' => $petugasIds,
                    'created_by' => $data['created_by'] ?? 1,
                    'created_at' => now(),
                ]);
            } catch (\Exception $e) {
                $this->error('Gagal import baris persidangan: ' . $e->getMessage());
            }
        }
        fclose($file);
    }

    private function parseJsonArray($string)
    {
        // Membersihkan format [1, 5, 6] menjadi array PHP
        $string = str_replace(['[', ']', ' '], '', $string);
        if (empty($string)) return [];
        return explode(',', $string);
    }
}
