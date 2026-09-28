<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UserType;


class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {


            $user_types = ['Super Admin', 'Customer'];


            DB::beginTransaction();


            foreach ($user_types as $user_type) {
                UserType::updateOrCreate(['user_type_name' => $user_type]);
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            echo $e->getMessage();
        }
    }
}
