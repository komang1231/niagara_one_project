<?php

namespace App\Console\Commands;

use App\Models\HistoryKaryawan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TerapkanPerubahanKaryawan extends Command
{
    protected $signature = 'karyawan:terapkan-perubahan';

    protected $description = 'Menerapkan perubahan karyawan yang sudah mencapai tanggal efektif';

    public function handle(): int
    {
        $histories = HistoryKaryawan::query()
            ->whereNull('diterapkan_at')
            ->whereDate('tanggal_efektif', '<=', today())
            ->orderBy('tanggal_efektif')
            ->get();

        foreach ($histories as $history) {
            DB::transaction(function () use ($history) {
                $karyawan = $history->karyawan;

                if (!$karyawan) {
                    return;
                }

                $karyawan->update([
                    'cabang_kantor_id' => $history->cabang_baru,
                    'departemen_id' => $history->departemen_baru,
                    'divisi_id' => $history->divisi_baru,
                    'section_id' => $history->section_baru,
                    'job_position_id' => $history->posisi_baru,
                    'job_level_id' => $history->level_baru,
                ]);

                $history->update([
                    'diterapkan_at' => now(),
                ]);
            });
        }

        $this->info(
            "{$histories->count()} perubahan karyawan berhasil diproses."
        );

        return self::SUCCESS;
    }
}