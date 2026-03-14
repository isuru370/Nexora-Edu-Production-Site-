<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassCategoryTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'category_name' => 'Theory',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Revision',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Paper Class',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('class_categories')->insert($categories);

        $this->command->info('Class categories seeded successfully!');
    }
}
