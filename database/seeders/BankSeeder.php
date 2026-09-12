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
            ['kode' => '002', 'nama' => 'Bank Rakyat Indonesia (BRI)'],
            ['kode' => '008', 'nama' => 'Bank Mandiri'],
            ['kode' => '009', 'nama' => 'Bank Negara Indonesia (BNI)'],
            ['kode' => '014', 'nama' => 'Bank Central Asia (BCA)'],
            ['kode' => '011', 'nama' => 'Bank Danamon'],
            ['kode' => '013', 'nama' => 'Bank Permata'],
            ['kode' => '022', 'nama' => 'CIMB Niaga'],
            ['kode' => '200', 'nama' => 'Bank Tabungan Negara (BTN)'],
            ['kode' => '451', 'nama' => 'Bank Syariah Indonesia (BSI)'],
            ['kode' => '110', 'nama' => 'Bank BJB'],
            ['kode' => '111', 'nama' => 'Bank DKI'],
            ['kode' => '112', 'nama' => 'BPD DIY'],
            ['kode' => '132', 'nama' => 'Bank Papua'],
            ['kode' => '490', 'nama' => 'Bank Jago'],
            ['kode' => '501', 'nama' => 'Blu by BCA Digital'],
            ['kode' => '947', 'nama' => 'SeaBank'],
            ['kode' => 'LAINNYA', 'nama' => 'Bank Lainnya'],
];
        foreach ($banks as $bank) {
            \App\Models\Bank::create($bank);
        }
    }
}
