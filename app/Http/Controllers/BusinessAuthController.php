<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

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
        try{
            $request->validate([
                'name'=>'required|string|max:255',
                'email'=>'required|email|max:255|unique',
                'password'=>'required|string|min:8|confirmed',
            ]);
            Business::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
            ]);
            Auth::attempt($request->only('email','password'));
        }
        catch(ValidationException $e)
        {
            return redirect()->withErrors($e->validator->getMessageBag())->withInput();
        }

    catch (Exception $e) {
        // If any other type of exception occurs, catch it and
        // redirect back with the exception message
        return redirect()->back()->withErrors($e->getMessage())->withInput();
    }

    // If no exception occurred, redirect to the intended URL
    return redirect()->route('business.dashboard');
    }

    public function businesslogin(Request $request)
    {
        $credential= $request->only('email','passsword');
        if(Auth::attempt($credential))
        {
            return redirect()->route('businessDashboard');
        }
        return redirect()->back()->withInput()->withErrors(['email' => 'Invalid email or password.']);
    }
}
