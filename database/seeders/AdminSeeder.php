<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {


            DB::beginTransaction();


            User::updateOrCreate(['name' => 'App Master', 'email' => 'adm@mpa.in', 'password' => Hash::make('Password*2024#'), 'user_type_id' => 1]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            echo $e->getMessage();
        }
    }
}
