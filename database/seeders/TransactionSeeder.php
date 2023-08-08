<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::create([
            'product_id' => 1,
            'user_id' => 2,
            'quantity' => 1,
            'total' => '10000',
            'status' => 'SUKSES',
            'payment_url' => 'https://app.sandbox.midtrans.com/snap/v3/redirection/e3148ce4-4730-430d-970f-66cf7eec8649',
        ]);
    }
}
