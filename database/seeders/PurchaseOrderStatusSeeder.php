<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PurchaseOrderStatus;
use Illuminate\Support\Facades\DB;

class PurchaseOrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {

            $statuses = ['Draft', 'Approved', 'Received', 'Cancelled'];

            DB::beginTransaction();


            foreach ($statuses as $status) {
                PurchaseOrderStatus::updateOrCreate(['status_name' => $status]);
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            echo $e->getMessage();
        }
    }
}
