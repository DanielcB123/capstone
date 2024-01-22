<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FriendRequest;

class FriendRequestController extends Controller
{
    public function sendRequest(Request $request)
    {
        $friendRequest = new FriendRequest();
        $friendRequest->sender_id = auth()->user()->id;
        $friendRequest->receiver_id = $request->receiver_id;
        $friendRequest->status = 'pending';
        $friendRequest->save();
    
        return response()->json(['message' => 'Friend request sent successfully']);
    }
    
    public function loadFriendRequests(Request $request)
    {
        // Retrieve the pending friend requests for the authenticated user
        $user = $request->user();
    
        $pendingRequests = FriendRequest::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->with('sender') // Eager load sender information
            ->get();
    

            $transformedRequests = $pendingRequests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'senderName' => $request->sender->name, // Get the sender's name from the relationship
                    // Add any other relevant data here
                ];
            });
        return response()->json($transformedRequests);
    }

    public function getFriendRequestsCount(Request $request)
    {
        $count = FriendRequest::where('receiver_id', $request->user()->id)
                              ->where('status', 'pending')
                              ->count();
    
        return response()->json(['count' => $count]);
    }
    
    public function acceptRequest(Request $request, $id)
    {
        $friendRequest = FriendRequest::findOrFail($id);
        $user = auth()->user();
    
        if ($friendRequest->receiver_id == $user->id) {
            // Update the status of the friend request to 'accepted'
            $friendRequest->update(['status' => 'accepted']);
    
            // Add the friendship to the friend_user table
            // This assumes that $user is the receiver and $friendRequest->sender_id is the sender
            $user->friends()->attach($friendRequest->sender_id);
            // Optionally, add the reverse relationship if friendships are bidirectional
            $friendRequest->sender->friends()->attach($user->id);
    
            return response()->json(['message' => 'Friend request accepted']);
        }
    
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    
    
    public function rejectRequest(Request $request, $id)
    {
        $friendRequest = FriendRequest::findOrFail($id);
        if ($friendRequest->receiver_id == auth()->user()->id) {
            $friendRequest->update(['status' => 'rejected']);
            return response()->json(['message' => 'Friend request rejected']);
        }
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    

    public function getFriends()
    {
        $user = auth()->user();
        $friends = $user->friends;
    
        return response()->json($friends);
    }
    




}
