<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function getUserPermissions($userId)
    {
        try {
            // Example: assuming 'user_permissions' table stores page_id per user
            $permissions = Permission::where('user_type_id', $userId)
                ->pluck('page_id')
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Assign all pages to Admin (user_type_id = 1)
     */
    public function assignAdminPermissions()
    {
        $this->permissionService->assignAllPagesToUserType(1);

        return response()->json([
            'success' => true,
            'message' => 'Admin permissions assigned successfully!'
        ]);
    }

    /**
     * Assign selected pages to a user type
     */
    public function assignPermissions(Request $request)
    {
        $userTypeId = $request->input('user_type_id');
        $pageIds    = $request->input('page_ids', []);

        $this->permissionService->assignPagesToUserType($userTypeId, $pageIds);

        return response()->json([
            'success' => true,
            'message' => 'Permissions assigned successfully!'
        ]);
    }
}
