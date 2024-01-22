<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        \Log::info($request);
        $favorite = new Favorite;
        $favorite->name = $request->name;
        $favorite->address = $request->address;
        $favorite->latitude = $request->latitude;
        $favorite->longitude = $request->longitude;
        $favorite->type = $request->type;
        $favorite->friend_id = $request->friendId;
        $favorite->user_id = auth()->id(); 

        $favorite->save();

        return response()->json(['message' => 'Favorite added successfully', 'favorite' => $favorite]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $favorites = Favorite::where('user_id', auth()->id())->get();
            return response()->json($favorites);
        } else {
            // Handle non-AJAX request here, e.g., return an error or redirect
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
    }
    
}
