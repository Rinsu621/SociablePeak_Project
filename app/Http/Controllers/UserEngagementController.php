<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\UserEngagement;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserEngagementController extends Controller
{
    public function storeUserData(Request $request){
        try {
            // Retrieve the authenticated user's ID
            $userId = auth()->id();
    
            // Validate request data
            $request->validate([
                'date' => 'required|date',
                'start_time' => 'required',
                'elapsed_time' => 'required|integer',
                'tab_switch' => 'required|integer',
            ]);
    
            // Retrieve data from the POST request
            $data = [
                'user_id' => $userId,
                'date' => $request->input('date'),
                'start_time' => $request->input('start_time'),
                'elapsed_time' => $request->input('elapsed_time'),
                'tab_switch' => $request->input('tab_switch')
            ];
    
            // Create or update user engagement data
            UserEngagement::updateOrCreate(
                ['user_id' => $data['user_id'], 'date' => $data['date']],
                $data
            );
            return response()->json(['message' => 'User engagement data stored successfully'], 200);
        } catch (ValidationException $e) {
            // If a validation error occurs, catch the ValidationException
            // and redirect back with the validation error messages
            return response()->json(['message' => $e->validator->getMessageBag()], 500);
        } catch (Exception $e) {
            // If any other type of exception occurs, catch it and
            // redirect back with the exception message
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function userEngagementDataView(){
        $userId = auth()->id();
        $data = UserEngagement::where('user_id',$userId)->orderBy('date','desc')->get();
        return view('analytics.userEngagementDataView',['data' => $data]);
    }
}
