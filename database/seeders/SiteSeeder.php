<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            ['name' => 'Kantor Pusat Jakarta',   'code' => 'JKT-HQ',  'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta',     'address' => 'Jl. Sudirman No. 1'],
            ['name' => 'Pabrik Cikarang',        'code' => 'CKR-01',  'city' => 'Cikarang',        'province' => 'Jawa Barat',      'address' => 'Kawasan Industri Jababeka Blok A1'],
            ['name' => 'Warehouse Cibitung',     'code' => 'CBT-WH',  'city' => 'Cibitung',        'province' => 'Jawa Barat',      'address' => 'Jl. Raya Imam Bonjol No. 5'],
            ['name' => 'Cabang Bandung',         'code' => 'BDG-01',  'city' => 'Bandung',         'province' => 'Jawa Barat',      'address' => 'Jl. Asia Afrika No. 10'],
            ['name' => 'Cabang Surabaya',        'code' => 'SBY-01',  'city' => 'Surabaya',        'province' => 'Jawa Timur',      'address' => 'Jl. Tunjungan No. 20'],
        ];

        foreach ($sites as $site) {
            Site::updateOrCreate(['code' => $site['code']], $site);
        }
    }
}
