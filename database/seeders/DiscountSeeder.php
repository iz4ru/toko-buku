<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::table('discounts')->insert([
            [
                'id' => 1,
                'code' => 'DISC10',
                'percentage' => 10,
                'status' => 1,
                'created_at' => '2025-10-01 08:00:00',
                'updated_at' => '2025-10-01 08:00:00',
            ],
            [
                'id' => 2,
                'code' => 'DISC20',
                'percentage' => 20,
                'status' => 1,
                'created_at' => '2025-10-05 10:30:00',
                'updated_at' => '2025-10-05 10:30:00',
            ],
            [
                'id' => 3,
                'code' => 'YEAR-END30',
                'percentage' => 30,
                'status' => 0,
                'created_at' => '2024-12-20 09:00:00',
                'updated_at' => '2024-12-31 23:59:00',
            ],
            [
                'id' => 4,
                'code' => 'MEMBER15',
                'percentage' => 15,
                'status' => 1,
                'created_at' => '2025-09-10 11:45:00',
                'updated_at' => '2025-09-10 11:45:00',
            ],
            [
                'id' => 5,
                'code' => 'FLASH50',
                'percentage' => 50,
                'status' => 0,
                'created_at' => '2025-08-01 14:00:00',
                'updated_at' => '2025-08-01 16:00:00',
            ],
        ]);
    }
}
