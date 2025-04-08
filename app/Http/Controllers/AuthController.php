<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendMail;
use App\Mail\SendCode;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.signup');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('homePage');
        }
        // if (Auth::attempt($credentials)) {
        //     // Redirect to the 2FA verification page
        //    return $this->sendTwoFactorCode();
        // }

        return redirect()->back()->withInput()->withErrors(['email' => 'Invalid email or password.']);
    }

    public function register(Request $request)
    {

        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Create a new user
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            // Attempt to authenticate the user
            Auth::attempt($request->only('email', 'password'));
        } catch (ValidationException $e) {
            // If a validation error occurs, catch the ValidationException
            // and redirect back with the validation error messages
            return redirect()->back()->withErrors($e->validator->getMessageBag())->withInput();
        } catch (Exception $e) {
            // If any other type of exception occurs, catch it and
            // redirect back with the exception message
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        // If no exception occurred, redirect to the intended URL
        return redirect()->route('homePage');
    }

    public function forgetPassword()
    {
        return view('auth.forget');
    }

    public function forgotPasswordPost(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users,email']);
    $token = Str::random(64);

    // Check if a reset token already exists for this email
    $resetEntry = DB::table('password_reset_tokens')->where('email', $request->email)->first();

    if ($resetEntry) {
        // Update the existing reset token entry
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->update([
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
    } else {
        // Insert a new reset token entry
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
    }

    $action_link = route('resetPasswordform', ['token' => $token, 'email' => $request->email]);
    $body = "We are received a request to reset the password for <b>SociablePeak</b> account associated with " . $request->email . ". You can reset your password by clicking the link below";
    // Mail::send('auth.email-forget', ['action_link' => $action_link, 'body' => $body], function ($message) use ($request) {
    //     $message->from('sociablepeak@gmail.com', 'SociablePeak');
    //     $message->to($request->email)->subject('Reset Password');
    // });
    Mail::to($request->email)->send(new SendMail($action_link, $body));


    return back()->with('success', 'We have emailed your password reset link');
}

public function resetPasswordform(Request $request, $token=null)
{
    return view('auth.reset')->with(['token'=>$token,'email'=>$request->email]);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|min:5|confirmed' // This checks for 'password_confirmation'
    ]);

    $check_token = \DB::table('password_reset_tokens')->where([
        'email' => $request->email,
        'token' => $request->token,
    ])->first();

    if (!$check_token) {
        return back()->withInput()->with('error', 'Invalid token');
    } else {
        User::where('email', $request->email)->update([
            'password' => \Hash::make($request->password)
        ]);
        \DB::table('password_reset_tokens')->where([
            'email' => $request->email
        ])->delete();
        return redirect()->route('login')->with('success', 'Your password has been changed! You can login with your new password');
    }
}

//     public function verifyTwoFactor(Request $request)
//     {
//         $request->validate([
//             'two_factor_code' => 'required|numeric|digits:6', // Validate the 6-digit code
//         ]);

//         $user = Auth::user();

//         // Check if the entered code matches the one stored in the session
//         if ($request->input('two_factor_code') == Session::get('two_factor_code')) {
//             // Clear the stored code and redirect to home page
//             Session::forget('two_factor_code');
//             return redirect()->route('homePage');
//         }

//         return back()->withErrors(['two_factor_code' => 'The code is invalid.']);
//     }



public function showChangePasswordForm()
{
    return view('auth.change-password');
}


public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required'],
        'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password does not match.']);
    }


    Session::put('pending_password', $request->new_password); // Use new_password, not newpassword
    Session::put('pending_password_user_id', $user->id);

    $this->sendTwoFactorCode($user);

    return redirect()->route('twoFactorForm')->with('success', 'A verification code has been sent to your email.');
}

private function sendTwoFactorCode($user)
{
    $code = rand(100000, 999999);

    Session::put('two_factor_code', $code);

    Mail::to($user->email)->send(new \App\Mail\SendCode($code));
}


    public function showTwoFactorForm()
    {
        return view('auth.two-factor');
    }

    public function verifyTwoFactor(Request $request)
{
    // Validate the code entered by the user
    $request->validate([
        'code' => 'required|numeric|digits:6',
    ]);

    // Get the expected code from the session
    $expectedCode = session('two_factor_code');

    // Check if the entered code matches the expected code
    if ($request->code == $expectedCode) {
        // Retrieve the new password and user ID from the session
        $newPassword = session('pending_password');
        $userId = session('pending_password_user_id');

        // Clear the session variables
        session()->forget('two_factor_code');
        session()->forget('pending_password');
        session()->forget('pending_password_user_id');

        // Find the user and update their password
        $user = \App\Models\User::find($userId);

        if ($user) {
            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            // Log the user in after updating the password
            Auth::login($user);

            // Redirect to the home page with a success message
            return redirect()->route('homePage')->with('success', 'Password changed successfully.');
        }

        // If the user is not found, return an error
        return back()->withErrors(['code' => 'User not found.']);
    }

    // If the code is incorrect, return back with an error message
    return back()->withErrors(['code' => 'Invalid two-factor code.']);
}


    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
