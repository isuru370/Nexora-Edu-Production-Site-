<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Grade/Class Data
        $grades = [
            [
                'grade_name' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_name' => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_name' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_name' => '4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_name' => '5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_name' => '6',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_name' => '7',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_name' => '8',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_name' => '9',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_name' => '10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade_name' => '11',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Check existing registration before entering data.
        $existingGrades = DB::table('grades')->count();

        if ($existingGrades == 0) {
            DB::table('grades')->insert($grades);
            $this->command->info('✅ Grades were successfully created.!');
            $this->command->info('📚 Total grades: ' . count($grades));
        } else {
            $this->command->info('ℹ️ Grades already exist. (' . $existingGrades . ' grades)');
        }
    }
}
