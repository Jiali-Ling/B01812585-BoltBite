<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\MenuItem;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, MenuItem $menuItem)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $menuItem->reviews()->create($data);

        return back()->with('status', 'Thanks for the feedback. Your review has been recorded.');
    }
}

