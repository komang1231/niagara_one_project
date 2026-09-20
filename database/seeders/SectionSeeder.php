<?php

namespace Database\Seeders;

use App\Models\Divisi as DivisiModel;
use App\Models\Section as SectionModel;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisiMap = DivisiModel::whereIn('kode', ['DIV-2600010920', 'DIV-2600020920', 'DIV-2600030920', 'DIV-2600040920', 'DIV-2600050920', 'DIV-2600010921', 'DIV-2600010922'])
            ->pluck('id', 'kode');

        $Section = [
            ['kode' => 'SEC-2600010920', 'nama' => 'Backend', 'divisi_id' => $divisiMap['DIV-2600010920'] ?? null],
            ['kode' => 'SEC-2600020920', 'nama' => 'Frontend', 'divisi_id' => $divisiMap['DIV-2600020920'] ?? null],
            ['kode' => 'SEC-2600030920', 'nama' => 'Quality Assurance', 'divisi_id' => $divisiMap['DIV-2600030920'] ?? null],
            ['kode' => 'SEC-2600040920', 'nama' => 'Talent Acquisition', 'divisi_id' => $divisiMap['DIV-2600040920'] ?? null],
            ['kode' => 'SEC-2600050920', 'nama' => 'SEO', 'divisi_id' => $divisiMap['DIV-2600050920'] ?? null],
            ['kode' => 'SEC-2600060920', 'nama' => 'Social Media', 'divisi_id' => $divisiMap['DIV-2600060920'] ?? null],
            ['kode' => 'SEC-2600070920', 'nama' => 'Front Office', 'divisi_id' => $divisiMap['DIV-2600070920'] ?? null],
            ['kode' => 'SEC-2600080920', 'nama' => 'Housekeeping', 'divisi_id' => $divisiMap['DIV-2600080920'] ?? null],
        ];

        foreach ($Section as $item) {
            if (empty($item['divisi_id'])) {
                continue;
            }

            SectionModel::firstOrCreate(
                ['kode' => $item['kode']],
                $item
            );
        }
    }
}
