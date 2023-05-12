<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favorites;
        return response()->json($favorites);
    }

    public function store(Request $request, $shopId)
    {
        $user = Auth::user();
        $shop = Shop::findOrFail($shopId);

        $isFavorite = $user->favorites()->where('shop_id', $shopId)->exists();
        
        if ($isFavorite) {
            $user->favorites()->detach($shop);
        } else {
            $user->favorites()->attach($shop);
        }

        return response()->json([]);
    }
}
