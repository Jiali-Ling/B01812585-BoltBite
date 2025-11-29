<?php

namespace App\Http\Controllers\BoltBite;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Storage;

class MenuItemFileController extends Controller
{
    public function file(MenuItem $menuItem, $filename)
    {
        if (!$menuItem->image_path) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($menuItem->image_path)) {
            abort(404);
        }

        return Storage::disk('public')->response($menuItem->image_path);
    }
}
