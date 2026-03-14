<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTypes = [
            ['type' => 'Admin'],
            ['type' => 'User'],
        ];

        // Check if data already exists
        foreach ($userTypes as $userType) {
            $exists = DB::table('user_types')
                ->where('type', $userType['type'])
                ->exists();

            if (!$exists) {
                DB::table('user_types')->insert([
                    'type' => $userType['type'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('User types seeded successfully!');
    }
}
