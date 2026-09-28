<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try{

            DB::beginTransaction();


                Supplier::factory()->count(500)->create();

            DB::commit();


        }

        catch(\Exception $e)
        {
            DB::rollBack();

            echo $e->getMessage();
        }
    }
}
