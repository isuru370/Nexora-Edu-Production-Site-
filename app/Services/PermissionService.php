<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    /**
     * Assign all pages to a given user_type_id
     */
    public function assignAllPagesToUserType($userTypeId)
    {
        $pages = DB::table('pages')->pluck('id');

        foreach ($pages as $pageId) {
            Permission::firstOrCreate([
                'user_type_id' => $userTypeId,
                'page_id'      => $pageId,
            ]);
        }
    }

    /**
     * Assign specific pages to a user_type_id
     */
    public function assignPagesToUserType($userTypeId, array $pageIds)
    {
        foreach ($pageIds as $pageId) {
            Permission::firstOrCreate([
                'user_type_id' => $userTypeId,
                'page_id'      => $pageId,
            ]);
        }
    }
}
