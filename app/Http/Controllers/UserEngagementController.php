<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\UserEngagement;
use Carbon\Carbon;
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

        //create data set for chart
        $dataSet = $data->toArray();
        // Remove items after the first 5 items because the chart modal will look ugly if there are more data
        $dataSet = array_slice($dataSet, 0, 5);
        $labels = [];
        $elapsedTimeData = [];
        $tabSwitchData = [];
        // $chartData = [
        //     [
        //         'label' => 'Total Time',
        //         'backgroundColor' => "#51A351",
        //         'data' => []
        //     ],
        //     [
        //         'label' => 'Tab Switches',
        //         'backgroundColor' => "#50b5ff",
        //         'data' => []
        //     ]
        // ];
        foreach($dataSet as $key => $item){
            $convertedDate = date('M d',strtotime($item['date'])); //convert the stored data format to this date format - May 3
            array_push($labels,$convertedDate);
            // echo $item['elapsed_time'].'<br>';
            // echo ($item['elapsed_time'] / 3600).'<br>';
            // echo floor($item['elapsed_time'] / 3600).'<br>';
            // echo (int)floor($item['elapsed_time'] / 3600).'<br><br><br>';
            array_push($elapsedTimeData,(int)floor($item['elapsed_time'] / 60));
            array_push($tabSwitchData,$item['tab_switch']);
            // array_push($chartData[0]['data'],(int)floor($item['elapsed_time'] / 3600));
            // array_push($chartData[1]['data'],$item['tab_switch']);
        }
        // dd($labels);

        $labels = json_encode($labels,true);
        $elapsedTimeData = json_encode($elapsedTimeData,true);
        $tabSwitchData = json_encode($tabSwitchData,true);
        // dd($dataSet);
        // $chartData = json_encode($chartData,true);
        return view('analytics.userEngagementDataView',[
            'data' => $data,
            'labels' => $labels,
            'elapsedTimeData' => $elapsedTimeData,
            'tabSwitchData' => $tabSwitchData,
            // 'chartData' => $chartData
        ]);
    }

    public function seedUserEngagement(){
         // Get the logged-in user
        $loggedInUser = auth()->user();

        // Get the first user engagement date for the logged-in user
        $firstUserEngagementDate = UserEngagement::where('user_id', $loggedInUser->id)
            ->orderBy('date', 'asc')
            ->value('date');

        // If no user engagements found, default to today's date
        if (!$firstUserEngagementDate) {
            $firstUserEngagementDate = Carbon::today()->toDateString();
        }

        // Generate 10 rows for 10 days before the first date
        $faker = \Faker\Factory::create();
        for ($i = 1; $i <= 10; $i++) {
            $date = Carbon::parse($firstUserEngagementDate)->subDays($i);

            UserEngagement::create([
                'user_id' => $loggedInUser->id,
                'date' => $date,
                'start_time' => '07:00:00', // Random time in seconds (0 - 86400)
                'elapsed_time' => $faker->numberBetween(0, 86401), // Random time in milliseconds (0 - 3600000)
                'tab_switch' => $faker->numberBetween(0, 20), // Random number for tab switch (0 - 10)
            ]);
        }
        return redirect()->route('userEngagementDataView');
    }
}
