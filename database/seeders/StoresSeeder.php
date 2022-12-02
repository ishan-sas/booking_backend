<?php
namespace Database\Seeders;

use App\Models\Stores;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $storeList = [
            [
                'user_id' => 1,
                'store_name' => 'Avondale',
                'slug' => 'avondale', 
                'no_of_ftrooms' => 3,
            ],
            [
                'user_id' => 1,
                'store_name' => 'Manukau',
                'slug' => 'manukau',
                'no_of_ftrooms' => 2, 
            ],
            [
                'user_id' => 1,
                'store_name' => 'Papakura',
                'slug' => 'papakura',
                'no_of_ftrooms' => 3, 
            ],
            [
                'user_id' => 1,
                'store_name' => 'Long Bay',
                'slug' => 'long-bay',
                'no_of_ftrooms' => 4, 
            ],
            [
                'user_id' => 1,
                'store_name' => 'Pakuranga',
                'slug' => 'pakuranga',
                'no_of_ftrooms' => 3, 
            ],
            [
                'user_id' => 1,
                'store_name' => 'Cambridge',
                'slug' => 'cambridge',
                'no_of_ftrooms' => 4, 
            ],
            [
                'user_id' => 1,
                'store_name' => 'Helensville',
                'slug' => 'helensville',
                'no_of_ftrooms' => 3, 
            ]
        ];

        Stores::insert($storeList);
    }
}
