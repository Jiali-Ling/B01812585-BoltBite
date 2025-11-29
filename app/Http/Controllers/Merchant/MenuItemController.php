<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItemRequest;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(MenuItemRequest $request, Restaurant $restaurant)
    {
        $this->authorize('manage', $restaurant);

        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['restaurant_id'] = $restaurant->id;

        if ($request->hasFile('image')) {
            $imageService = new ImageUploadService();
            $errors = $imageService->validate($request->file('image'));
            
            if (!empty($errors)) {
                return back()->withErrors(['image' => $errors])->withInput();
            }

            $data['image_path'] = $imageService->uploadAndResize(
                $request->file('image'),
                'menu-items',
                800,
                800,
                85
            );
        }

        try {
            MenuItem::create($data);
            return redirect()->route('merchant.menu.index', $restaurant)
                ->with('success', 'Menu item created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create menu item: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(MenuItemRequest $request, Restaurant $restaurant, MenuItem $menuItem)
    {
        $this->authorize('manage', $restaurant);
        $this->authorize('update', $menuItem);
        abort_unless($menuItem->restaurant_id === $restaurant->id, 404);

        $data = $request->validated();
        $oldImagePath = $menuItem->image_path;

        if ($request->hasFile('image')) {
            $imageService = new ImageUploadService();
            $errors = $imageService->validate($request->file('image'));
            
            if (!empty($errors)) {
                return back()->withErrors(['image' => $errors])->withInput();
            }

            $data['image_path'] = $imageService->uploadAndResize(
                $request->file('image'),
                'menu-items',
                800,
                800,
                85
            );

            if ($oldImagePath) {
                $imageService->delete($oldImagePath);
            }
        }

        try {
            $menuItem->update($data);
            return redirect()->route('merchant.menu.index', $restaurant)
                ->with('success', 'Menu item updated successfully.');
        } catch (\Exception $e) {
            if (isset($data['image_path']) && $data['image_path'] !== $oldImagePath) {
                (new ImageUploadService())->delete($data['image_path']);
            }
            return back()->withErrors(['error' => 'Failed to update menu item: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Restaurant $restaurant, MenuItem $menuItem)
    {
        $this->authorize('manage', $restaurant);
        $this->authorize('delete', $menuItem);
        abort_unless($menuItem->restaurant_id === $restaurant->id, 404);

        try {
            $imagePath = $menuItem->image_path;
            $menuItem->delete();

            if ($imagePath) {
                (new ImageUploadService())->delete($imagePath);
            }

            return redirect()->route('merchant.menu.index', $restaurant)
                ->with('success', 'Menu item deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete menu item: ' . $e->getMessage()]);
        }
    }

    public function toggleStatus(Request $request, Restaurant $restaurant, MenuItem $menuItem)
    {
        $this->authorize('manage', $restaurant);
        $this->authorize('update', $menuItem);
        abort_unless($menuItem->restaurant_id === $restaurant->id, 404);

        $menuItem->update([
            'status' => $menuItem->status === 'on_shelf' ? 'off_shelf' : 'on_shelf',
        ]);

        return redirect()->route('merchant.menu.index', $restaurant)->with('status', 'Shelf status updated.');
    }
}

