<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function home()
    {
        if (Auth::check()) {
            return view('home.homepage');
        } else {
            return redirect()->route('login');
        }
    }

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

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
