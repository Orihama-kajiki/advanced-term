<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $shop_id = $request->input('shop_id');
        $shop = Shop::findOrFail($shop_id);

        $isFavorite = $user->favorites()->where('shop_id', $shop_id)->exists();
        
        if ($isFavorite) {
            $user->favorites()->detach($shop);
        } else {
            $user->favorites()->attach($shop);
        }

        return response()->json([]);
    }
}
