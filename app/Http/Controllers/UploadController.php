<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function checkMerchantOrAdmin()
    {
        if (!auth()->check() || (!auth()->user()->isMerchant() && !auth()->user()->isAdmin())) {
            abort(403, 'Only merchants and administrators can access this page.');
        }
    }

    private function ensureOwnUpload(Upload $upload): void
    {
        if (auth()->user()->isAdmin()) {
            return;
        }

        if ($upload->user_id !== auth()->id()) {
            abort(403, 'You can only manage your own uploads.');
        }
    }

    public function index()
    {
        $this->checkMerchantOrAdmin();
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        $allFiles = collect();

        $uploads = Upload::with('user')
            ->when(!$isAdmin, fn ($query) => $query->where('user_id', $user->id))
            ->get();
        foreach ($uploads as $upload) {
            $allFiles->push([
                'id' => 'upload_' . $upload->id,
                'type' => 'upload',
                'origName' => $upload->origName,
                'path' => $upload->path,
                'title' => $upload->title,
                'content' => $upload->content,
                'user' => $upload->user,
                'user_id' => $upload->user_id,
                'created_at' => $upload->created_at,
                'model' => $upload,
            ]);
        }
        
        $menuItems = MenuItem::with('merchant')
            ->whereNotNull('image_path')
            ->when(!$isAdmin, fn ($query) => $query->where('user_id', $user->id))
            ->get();
        foreach ($menuItems as $item) {
            $allFiles->push([
                'id' => 'menuitem_' . $item->id,
                'type' => 'menuitem',
                'origName' => basename($item->image_path),
                'path' => $item->image_path,
                'title' => $item->name,
                'content' => $item->description,
                'user' => $item->merchant,
                'user_id' => $item->user_id,
                'created_at' => $item->created_at,
                'model' => $item,
            ]);
        }
        
        $restaurants = Restaurant::with('owner')
            ->whereNotNull('image')
            ->when(!$isAdmin, fn ($query) => $query->where('user_id', $user->id))
            ->get();
        foreach ($restaurants as $restaurant) {
            $allFiles->push([
                'id' => 'restaurant_' . $restaurant->id,
                'type' => 'restaurant',
                'origName' => basename($restaurant->image),
                'path' => $restaurant->image,
                'title' => $restaurant->name,
                'content' => $restaurant->description,
                'user' => $restaurant->owner,
                'user_id' => $restaurant->user_id,
                'created_at' => $restaurant->created_at,
                'model' => $restaurant,
            ]);
        }
        
        $allFiles = $allFiles->sortByDesc('created_at')->values();
        
        return view('uploads.index', ['uploads' => $allFiles]);
    }

    public function create()
    {
        $this->checkMerchantOrAdmin();
        return view('uploads.create');
    }

    public function store(Request $request)
    {
        $this->checkMerchantOrAdmin();
        
        $request->validate([
            'upload' => 'required|file',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $file = $request->file('upload');
        $path = $file->store('uploads', 'public');
        $origName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();

        $upload = Upload::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'path' => $path,
            'origName' => $origName,
            'mimeType' => $mimeType,
        ]);

        return redirect()->route('uploads.index')
            ->with('status', 'File uploaded successfully!');
    }

    public function show(Upload $upload)
    {
        $this->checkMerchantOrAdmin();
        $upload->load('user');
        return view('uploads.show', compact('upload'));
    }

    public function edit(Upload $upload)
    {
        $this->checkMerchantOrAdmin();
        $this->ensureOwnUpload($upload);
        return view('uploads.edit', [
            'id' => $upload->id,
            'title' => $upload->title,
            'content' => $upload->content,
            'origName' => $upload->origName,
            'mimeType' => $upload->mimeType,
            'path' => $upload->path,
        ]);
    }

    public function update(Request $request, Upload $upload)
    {
        $this->checkMerchantOrAdmin();
        $this->ensureOwnUpload($upload);
        
        $request->validate([
            'upload' => 'nullable|file',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $upload->title = $request->title;
        $upload->content = $request->content;

        if ($request->hasFile('upload')) {
            Storage::disk('public')->delete($upload->path);
            $file = $request->file('upload');
            $upload->path = $file->store('uploads', 'public');
            $upload->origName = $file->getClientOriginalName();
            $upload->mimeType = $file->getMimeType();
        }

        $upload->save();

        return redirect()->route('uploads.index')
            ->with('status', 'Upload updated successfully!');
    }

    public function destroy(Upload $upload)
    {
        $this->checkMerchantOrAdmin();
        $this->ensureOwnUpload($upload);
        
        if ($upload->path) {
            Storage::disk('public')->delete($upload->path);
        }
        $upload->delete();

        return redirect()->route('uploads.index')
            ->with('status', 'Upload deleted successfully!');
    }

    public function file($id, $filename)
    {
        $upload = Upload::findOrFail($id);
        
        if (!Storage::disk('public')->exists($upload->path)) {
            abort(404);
        }

        return Storage::disk('public')->response($upload->path);
    }

    public function editRestaurantImage($id)
    {
        $this->checkMerchantOrAdmin();
        
        $restaurant = Restaurant::findOrFail($id);
        
        if (auth()->id() !== $restaurant->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('uploads.edit-restaurant', [
            'restaurant' => $restaurant,
            'id' => $restaurant->id,
            'title' => $restaurant->name,
            'content' => $restaurant->description,
            'origName' => $restaurant->image ? basename($restaurant->image) : '',
            'path' => $restaurant->image,
        ]);
    }

    public function updateRestaurantImage(Request $request, $id)
    {
        $this->checkMerchantOrAdmin();
        
        $restaurant = Restaurant::findOrFail($id);
        
        if (auth()->id() !== $restaurant->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'upload' => 'nullable|file|image',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        if ($request->has('title')) {
            $restaurant->name = $request->title;
        }
        if ($request->has('content')) {
            $restaurant->description = $request->content;
        }

        if ($request->hasFile('upload')) {
            if ($restaurant->image) {
                $oldImagePath = public_path($restaurant->image);
                if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                    try {
                        @unlink($oldImagePath);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete old restaurant image: ' . $oldImagePath);
                    }
                }
            }
            
            $file = $request->file('upload');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
            $directory = 'images/restaurants';
            $fullPath = $directory . '/' . $filename;
            
            $directoryPath = public_path($directory);
            if (!is_dir($directoryPath)) {
                try {
                    if (!mkdir($directoryPath, 0775, true) && !is_dir($directoryPath)) {
                        throw new \RuntimeException('Directory creation failed: ' . $directoryPath);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to create directory: ' . $e->getMessage());
                    return redirect()->back()->withErrors(['upload' => 'Failed to create directory. Please contact administrator.'])->withInput();
                }
            }
            
            try {
                $destination = $directoryPath . '/' . $filename;
                if (move_uploaded_file($file->getRealPath(), $destination)) {
                    chmod($destination, 0644);
                    $restaurant->image = $fullPath;
                } else {
                    \Log::error('Failed to move uploaded file from: ' . $file->getRealPath() . ' to: ' . $destination);
                    return redirect()->back()->withErrors(['upload' => 'Failed to upload image. Please try again.'])->withInput();
                }
            } catch (\Exception $e) {
                \Log::error('Failed to move uploaded file: ' . $e->getMessage());
                return redirect()->back()->withErrors(['upload' => 'Failed to save image: ' . $e->getMessage()])->withInput();
            }
        }

        $restaurant->save();

        return redirect()->route('uploads.index')
            ->with('status', 'Restaurant image updated successfully!');
    }
}
