<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{


    public function index($userId)
    {
        return view('permission.index', compact('userId'));
    }

    public function allPages()
    {
        try {
            $pages = Page::get()->map(function ($item) {
                return [
                    'page_id'   => $item->id,
                    'page_name' => $item->page,
                    'route_name' =>$item->route_name,
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => $pages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
