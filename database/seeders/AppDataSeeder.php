<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserTypeSeeder::class,
            AdminSeeder::class,
            SupplierSeeder::class,
            PurchaseOrderStatusSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
