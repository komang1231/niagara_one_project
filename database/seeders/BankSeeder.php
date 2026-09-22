<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['kode' => '', 'nama' => 'Bank Rakyat Indonesia (BRI)'],
            ['kode' => '', 'nama' => 'Bank Mandiri'],
            ['kode' => '', 'nama' => 'Bank Negara Indonesia (BNI)'],
            ['kode' => '', 'nama' => 'Bank Central Asia (BCA)'],
            ['kode' => '', 'nama' => 'Bank Danamon'],
            ['kode' => '', 'nama' => 'Bank Permata'],
            ['kode' => '', 'nama' => 'CIMB Niaga'],
            ['kode' => '', 'nama' => 'Bank Tabungan Negara (BTN)'],
            ['kode' => '', 'nama' => 'Bank Syariah Indonesia (BSI)'],
            ['kode' => '', 'nama' => 'Bank BJB'],
            ['kode' => '', 'nama' => 'Bank DKI'],
            ['kode' => '', 'nama' => 'BPD DIY'],
            ['kode' => '', 'nama' => 'Bank Papua'],
            ['kode' => '', 'nama' => 'Bank Jago'],
            ['kode' => '', 'nama' => 'Blu by BCA Digital'],
            ['kode' => '', 'nama' => 'SeaBank'],
        ];
        foreach ($banks as $bank) {
            \App\Models\Bank::create($bank);
        }
    }
}
