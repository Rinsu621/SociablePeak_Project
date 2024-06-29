<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserEngagementController;
use App\Http\Controllers\SearchController;



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

Route::get('clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});

Route::get('/', [HomeController::class, 'home'])->name('homePage');

// Route::get('/', function () {
//     return view('auth/signup');
// });

//Auth
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('registerStore');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::get('forgetpassword',[AuthController::class,'forgetPassword'])->name('forgetPassword');
Route::post('forgetpassword',[AuthController::class,'forgotPasswordPost'])->name('forgetPasswordPost');
Route::get('/resetpassword/{token}',[AuthController::class, 'resetPasswordform'])->name('resetPasswordform');
Route::post('/resetpassword',[AuthController::class,'resetPassword'])->name('resetPassword');
//Auth


//Analytics
Route::get('anaytics', [AnalyticsController::class, 'index'])->name('anaytics');
Route::get('user-engagement', [UserEngagementController::class, 'userEngagementDataView'])->name('userEngagementDataView');
Route::get('seed-user-engagement', [UserEngagementController::class, 'seedUserEngagement'])->name('seedUserEngagement');
Route::post('store-user-data', [UserEngagementController::class, 'storeUserData'])->name('storeUserData');
Route::get('seed-user-engagement', [UserEngagementController::class, 'seedUserEngagement'])->name('seedUserEngagement');
//Analytics

//Posts
Route::post('postStore', [PostController::class, 'postStore'])->name('postStore');
Route::post('/post/{id}/like', [PostController::class, 'likePost'])->name('post.like');
Route::post('/post/{id}/comment', [PostController::class, 'commentPost'])->name('post.comment');
//Posts

//Profile
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile/update-picture', [ProfileController::class, 'updatePicture'])->name('profile.updatePicture');

//Profile

//Chat
Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('send-message', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');
//Chat


//Friends
Route::get('create-friends', [FriendController::class, 'createFriends'])->name('createFriends');
Route::post('/friends/add/{id}', [FriendController::class, 'addFriend'])->name('friends.add');
Route::get('/friend-request', [FriendController::class, 'friendRequest'])->name('friend.friendrequest');
Route::post('/friend-requests/accept/{id}', [FriendController::class, 'acceptFriendRequest'])->name('friend.accept');
Route::post('/friend-requests/reject/{id}', [FriendController::class, 'rejectFriendRequest'])->name('friend.reject');
Route::post('/unfriend/{id}', [FriendController::class, 'unfriend'])->name('friend.unfriend');
//Friends

//Search
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/user/{id}', [SearchController::class, 'show'])->name('user.show');

