<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $shops = Shop::all();
        $shop_id = $request->input('shop_id');
        $shop = Shop::find($shop_id);
        return view('write_review', compact('shops', 'shop'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $existingReview = Review::where('shop_id', $request->shop_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->back()->withErrors(['error' => 'あなたはすでにこのお店にレビューを投稿しています。']);
        }

        $review = new Review([
            'shop_id' => $request->shop_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $review->save();
        return redirect()->route('reviews.create')->with('success', 'レビューが正常に投稿されました。');
    }
}
