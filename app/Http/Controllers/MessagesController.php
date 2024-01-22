<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function index(){
        // Retrieve the authenticated user
        $user = Auth::user();

        // Pass the user's information to the profile.blade.php view
        return view('messages')->with('user', $user);
    }
}
