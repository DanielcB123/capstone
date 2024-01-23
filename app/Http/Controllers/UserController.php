<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function searchFriends(Request $request)
    {
        $query = $request->query('query');
        $friends = User::where('name', 'LIKE', "%{$query}%")
                        ->orWhere('email', 'LIKE', "%{$query}%")
                        ->get(['id', 'name', 'email', 'profile_image_path']);
        return response()->json($friends);
    }

    public function showProfile(){
        // Retrieve the authenticated user
        $user = Auth::user();

        // Pass the user's information to the profile.blade.php view
        return view('profile')->with('user', $user);
    }

    public function updateAddress(Request $request)
    {
        $user = Auth::user();
        $user->address = $request->address;
        $user->save();

        return response()->json(['message' => 'Address updated successfully']);
    }


    // // Edit User Profile
    // public function edit()
    // {
    //     $user = auth()->user();
    //     return view('user.edit', compact('user'));
    // }

    // Update User Profile
    public function update(Request $request)
    {

        $user = auth()->user();
        $user->update($request->all());

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    // Change Password View
    public function changePassword()
    {
        return view('user.changePassword');
    }

    // Update Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'newpassword' => 'required|string|min:8',
            'confirmpassword' => 'required_with:newpassword|same:newpassword',
        ]);
    
        $user = auth()->user();
    
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect']);
        }
    
        $user->update(['password' => Hash::make($request->newpassword)]);
    
        return redirect()->route('profile.show')->with('success', 'Password changed successfully.');
    }
    

    // Upload User Image
    public function uploadImage(Request $request)
    {
        $request->validate([
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:100000'],
        ]);

        $user = auth()->user();
        $image = $request->file('profile_picture');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('profileimages'), $imageName);

        // Update user's image path in database if needed
        $user->profile_image_path = '/profileimages/'.$imageName;
        $user->save();

        return back()->with('success','Image uploaded successfully.');
    }

}
