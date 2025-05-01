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
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\BusinessAuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\GroupController;



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


//Auth
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('registerStore');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('send-two-factor', [AuthController::class, 'sendTwoFactorCode'])->name('sendTwoFactorCode');


// Route::get('two-factor', [AuthController::class, 'showTwoFactorForm'])->name('twoFactorForm');
// Route::post('two-factor', [AuthController::class, 'verifyTwoFactor'])->name('verifyTwoFactor');


Route::get('forgetpassword',[AuthController::class,'forgetPassword'])->name('forgetPassword');
Route::post('forgetpassword',[AuthController::class,'forgotPasswordPost'])->name('forgetPasswordPost');
Route::get('/resetpassword/{token}',[AuthController::class, 'resetPasswordform'])->name('resetPasswordform');
Route::post('/resetpassword',[AuthController::class,'resetPassword'])->name('resetPassword');

Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('requestChangePassword')->middleware('auth');
Route::post('/change-password', [AuthController::class, 'changePassword'])->name('changePassword')->middleware('auth');

Route::get('/two-factor', [AuthController::class, 'showTwoFactorForm'])->name('twoFactorForm');
Route::post('/verify-two-factor', [AuthController::class, 'verifyTwoFactor'])->name('verifyTwoFactor');



//Auth

Route::prefix('business')->group(function(){
    Route::get('login',[BusinessAuthController::class,'showLoginForm'])->name('businesslogin');
    Route::get('register', [BusinessAuthController::class, 'showRegistrationForm'])->name('businessregister');
    Route::post('register', [BusinessAuthController::class, 'businessregister'])->name('businessregisterStore');
    Route::post('login',[BusinessAuthController::class,'businesslogin']);
    Route::get('/',[BusinessAuthController::class,'home'])->name('businessDashboard');
    Route::post('logout', [BusinessAuthController::class, 'logout'])->name('businesslogout');

    Route::get('profile', [BusinessController::class, 'index'])->name('profileBusiness');
    Route::post('/business/profile/update-picture', [BusinessController::class, 'ProfilePicture'])->name('businessPicture');
    Route::post('/ads/post', [BusinessController::class, 'postAdStore'])->name('business.ads.post');


});


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
Route::get('/post-engagement', [PostController::class, 'getPostEngagement'])->name('post.engagement');
Route::get('/analytics/most-interacted-users', [PostController::class, 'getMostInteractedUsers'])->name('analytics.mostInteractedUsers');
//Posts

//Profile
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile/update-picture', [ProfileController::class, 'updatePicture'])->name('profile.updatePicture');
Route::get('/profile/view/{id}', [ProfileController::class, 'view'])->name('profileView');


//Profile

//Chat
Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('send-message', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');
Route::post('/group/send-message', [ChatController::class, 'sendGroupMessage'])->name('group.sendMessage');
Route::post('/group/create', [GroupController::class, 'store'])->name('group.create');


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
Route::post('user/{id}/report', [SearchController::class, 'reportUser'])->name('user.report');

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('admin/reports/user/{id}/profile', [ReportController::class, 'viewProfile'])->name('admin.reports.user.profile');
        Route::delete('admin/reports/user/{id}/delete', [ReportController::class, 'deleteAccount'])->name('admin.reports.user.delete');

    });


    //Notification
    Route::get('/notifications', [NotificationController::class, 'showNotifications'])->name('show.notifications');

});

