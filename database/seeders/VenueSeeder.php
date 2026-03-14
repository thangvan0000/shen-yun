<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Venue::query()->updateOrCreate(
            ['name' => 'NX-LP 96 vinhomes grand park'],
            ['address' => 'TP. Thủ Đức, TP.HCM', 'timezone' => 'Asia/Ho_Chi_Minh'],
        );
    }
}
