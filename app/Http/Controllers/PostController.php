<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Post::class);

        $query = Post::with('user')
            ->where(function ($q) {
                $q->where('status', 'published');
                if (auth()->check()) {
                    $q->orWhere('user_id', auth()->id());
                }
            });

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $posts = $query->latest()->paginate(10);
        
        if ($request->has('q')) {
            $posts->appends(['q' => $request->get('q')]);
        }

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $this->authorize('create', Post::class);
        return view('posts.create');
    }

    public function store(StorePostRequest $request)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $validated['path'] = $request->file('file')->store('posts', 'public');
        }

        $validated['user_id'] = auth()->id();

        $post = Post::create($validated);

        return redirect()->route('posts.show', $post)
            ->with('status', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(StorePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validated();

        if ($request->hasFile('file')) {
            if ($post->path) {
                Storage::disk('public')->delete($post->path);
            }
            $validated['path'] = $request->file('file')->store('posts', 'public');
        }

        $post->update($validated);

        return redirect()->route('posts.show', $post)
            ->with('status', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->path) {
            Storage::disk('public')->delete($post->path);
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('status', 'Post deleted successfully!');
    }

    public function trashed()
    {
        $this->authorize('viewAny', Post::class);
        $posts = Post::onlyTrashed()->with('user')->latest()->paginate(10);
        return view('posts.trashed', compact('posts'));
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $this->authorize('delete', $post);
        $post->restore();
        return redirect()->route('posts.trashed')->with('status', 'Post restored.');
    }

    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $this->authorize('delete', $post);
        if ($post->path) {
            Storage::disk('public')->delete($post->path);
        }
        $post->forceDelete();
        return redirect()->route('posts.trashed')->with('status', 'Post permanently deleted.');
    }
}
