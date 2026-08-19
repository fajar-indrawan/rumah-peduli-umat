<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $transactions = [
        //     ['tanggal' => '2022-01-01', 'kode_coa' => '401', 'desc' => 'Gaji Di Persuhaan A', 'debit' => 0, 'credit' => 5000000],
        //     ['tanggal' => '2022-01-02', 'kode_coa' => '402', 'desc' => 'Gaji Ketum', 'debit' => 0, 'credit' => 7000000],
        //     ['tanggal' => '2022-01-10', 'kode_coa' => '602', 'desc' => 'Bensin Anak', 'debit' => 25000, 'credit' => 0],
        // ]; 
        
        // foreach ($transactions as $item) {
        //     Transaction::create([
        //         'tanggal' => $item['tanggal'],
        //         'kode_coa' => $item['kode_coa'],
        //         'desc' => $item['desc'],
        //         'debit' => $item['debit'],
        //         'credit' => $item['credit'],
        //     ]);
        // }

        Transaction::truncate();

        $transactions = [
            // Januari 2022
            ['tanggal' => '2022-01-01', 'kode_coa' => '401', 'desc' => 'Gaji Karyawan', 'debit' => 0, 'credit' => 12000000],
            ['tanggal' => '2022-01-05', 'kode_coa' => '403', 'desc' => 'Profit Trading', 'debit' => 0, 'credit' => 5500000],
            ['tanggal' => '2022-01-10', 'kode_coa' => '601', 'desc' => 'Biaya Sekolah', 'debit' => 500000, 'credit' => 0],
            ['tanggal' => '2022-01-15', 'kode_coa' => '602', 'desc' => 'Bensin', 'debit' => 200000, 'credit' => 0],
            ['tanggal' => '2022-01-20', 'kode_coa' => '604', 'desc' => 'Makan Siang', 'debit' => 150000, 'credit' => 0],

            // Februari 2022
            ['tanggal' => '2022-02-01', 'kode_coa' => '401', 'desc' => 'Gaji Karyawan', 'debit' => 0, 'credit' => 12000000],
            ['tanggal' => '2022-02-05', 'kode_coa' => '403', 'desc' => 'Profit Trading', 'debit' => 0, 'credit' => 6000000],
            ['tanggal' => '2022-02-10', 'kode_coa' => '601', 'desc' => 'Biaya Sekolah', 'debit' => 3500000, 'credit' => 0],
            ['tanggal' => '2022-02-15', 'kode_coa' => '602', 'desc' => 'Bensin', 'debit' => 250000, 'credit' => 0],
            ['tanggal' => '2022-02-20', 'kode_coa' => '604', 'desc' => 'Makan Siang', 'debit' => 300000, 'credit' => 0],

            // Maret 2022
            ['tanggal' => '2022-03-01', 'kode_coa' => '401', 'desc' => 'Gaji Karyawan', 'debit' => 0, 'credit' => 12000000],
            ['tanggal' => '2022-03-05', 'kode_coa' => '403', 'desc' => 'Profit Trading', 'debit' => 0, 'credit' => 3500000],
            ['tanggal' => '2022-03-10', 'kode_coa' => '601', 'desc' => 'Biaya Sekolah', 'debit' => 4500000, 'credit' => 0],
            ['tanggal' => '2022-03-15', 'kode_coa' => '602', 'desc' => 'Bensin', 'debit' => 225000, 'credit' => 0],
            ['tanggal' => '2022-03-20', 'kode_coa' => '604', 'desc' => 'Makan Siang', 'debit' => 175000, 'credit' => 0],
        ];

        foreach ($transactions as $trx) {
            Transaction::create($trx);
        }
    }
}
