<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminPermissionsSeeder extends Seeder
{
    public function run()
    {
        // සියලු pages IDs ගන්න
        $pages = DB::table('pages')->pluck('id');

        foreach ($pages as $pageId) {
            $exists = DB::table('permissions')
                ->where('user_type_id', 1)
                ->where('page_id', $pageId)
                ->exists();

            if (!$exists) {
                DB::table('permissions')->insert([
                    'user_type_id' => 1,   // Admin
                    'page_id'      => $pageId,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }
    }
}
