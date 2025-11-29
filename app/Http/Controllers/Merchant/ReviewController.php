<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewReplyRequest;
use App\Models\Restaurant;
use App\Models\Review;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Restaurant $restaurant)
    {
        $this->authorize('manage', $restaurant);

        $reviews = $restaurant->menuItems()
            ->with(['reviews.user', 'reviews.replies.user'])
            ->get()
            ->flatMap->reviews
            ->sortByDesc('created_at');

        return view('merchant.reviews.index', compact('restaurant', 'reviews'));
    }

    public function reply(ReviewReplyRequest $request, Restaurant $restaurant, Review $review)
    {
        $this->authorize('manage', $restaurant);
        abort_unless($review->menuItem->restaurant_id === $restaurant->id, 404);

        $review->replies()->create([
            'body' => $request->validated()['body'],
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('merchant.reviews.index', $restaurant)->with('status', 'Reply sent.');
    }
}

