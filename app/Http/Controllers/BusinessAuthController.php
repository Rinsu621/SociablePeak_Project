<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Exception;

class BusinessAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('business.login');
    }

    public function showRegistrationForm()
    {
        return view('business.signup');
    }

    public function businessregister(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:businesses,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            Business::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            Auth::guard('business')->attempt($request->only('email', 'password'));

            return redirect()->route('businessDashboard');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->getMessageBag())
                ->withInput();

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function businesslogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('business')->attempt($credentials)) {
            return redirect()->route('businessDashboard');
        }

        return redirect()->back()
            ->withInput()
            ->withErrors(['email' => 'Invalid email or password.']);
    }

    public function logout()
    {
        Auth::guard('business')->logout();
        return redirect()->route('businesslogin')->with('status', 'Logged out successfully.');
    }

    public function home()
    {
        return view('business.dashboard');
    }
}
