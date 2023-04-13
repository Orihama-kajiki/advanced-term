<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Author;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;

class ShopController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shops = Shop::with(['area', 'genre'])->get();
        $favorites = [];
        if (Auth::check()) {
            $user = Auth::user();
            $favorites = $user->favorites;
        }

        return view('index', compact('areas', 'genres', 'shops','favorites'));
    }

    public function detail($shop_id)
    {
        $shop = Shop::find($shop_id);
        return view('detail', ['shop' => $shop]);
    }


}
