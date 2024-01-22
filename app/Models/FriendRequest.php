<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;

    // Mass assignable attributes
    protected $fillable = ['sender_id', 'receiver_id', 'status'];

    // Relationship with the User model for the sender
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relationship with the User model for the receiver
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
