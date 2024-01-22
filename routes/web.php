<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MessagesController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/search-friends', [UserController::class, 'searchFriends']);
Route::post('/friend-request/send', [FriendRequestController::class, 'sendRequest']);
Route::post('/friend-request/accept/{id}', [FriendRequestController::class, 'acceptRequest']);
Route::post('/friend-request/reject/{id}', [FriendRequestController::class, 'rejectRequest']);
Route::get('/pending-friends-requests',  [FriendRequestController::class, 'loadFriendRequests']);
Route::get('/friend-requests/count', [FriendRequestController::class, 'getFriendRequestsCount']);
Route::post('/friend-requests/accept/{id}', [FriendRequestController::class, 'acceptRequest']);
Route::post('/friend-requests/reject/{id}', [FriendRequestController::class, 'rejectRequest']);
Route::get('/friends', [FriendRequestController::class, 'getFriends']);

Route::post('/update-address', [UserController::class, 'updateAddress']);
Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');

Route::post('/save-favorite', [FavoriteController::class, 'store']);
Route::get('/favorites', [FavoriteController::class, 'index']);

// User Profile Edit
// Route::get('/user/edit', [UserController::class , 'edit'])->name('profile.edit');
Route::post('/user/update', [UserController::class , 'update'])->name('profile.update');

// User Password Change
Route::get('/user/password', [UserController::class , 'changePassword'])->name('profile.changePassword');
Route::post('/user/password/update',  [UserController::class , 'updatePassword'])->name('profile.updatePassword');

// User Image Upload
Route::post('/user/image/upload',  [UserController::class , 'uploadImage'])->name('profile.uploadImage');

// Messages Routes
Route::get('/messages', [MessagesController::class, 'index'])->name('messages');