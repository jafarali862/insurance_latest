<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OdometerReading;
use App\Models\AccidentPersonData;
use App\Models\DriverData;
use App\Models\GarageData;
use App\Models\SpotData;
use App\Models\OwnerData;
use Carbon\Carbon;

class ExecutiveController extends Controller
{
    public function checkIn(Request $request, $id)
    {
        $check_in_image = $request->file('check_in_image')->store('odometer', 'public');

        date_default_timezone_set('Asia/Kolkata');

        $time = date('h:i A');
        $date = date('d-m-Y');

        OdometerReading::create([
            'user_id' => $id,
            'check_in_km' => $request->check_in_km,
            'check_in_image' => $check_in_image,
            'check_in_time' => $time,
            'check_in_date' => $date,
            'check_in_latitude_and_longitude' => $request->check_in_latitude_and_longitude,
            'status' => 0,
            'updated_at' => now(),
        ]);

        return response()->json(['Check in successfully complete'], 200);
    }

    public function checkOut(Request $request, $id)
    {
        $check_out_image = $request->file('check_out_image')->store('odometer', 'public');

        $lastReading = OdometerReading::where('user_id', $id)->latest()->first();

        date_default_timezone_set('Asia/Kolkata');

        $time = date('h:i A');
        $date = date('d-m-Y');

        if ($lastReading) 
        {
            $lastReading->update([
                'check_out_km' => $request->check_out_km,
                'check_out_image' => $check_out_image,
                'check_out_time' => $time,
                'check_out_date' => $date,
                'check_out_latitude_and_longitude' => $request->check_out_latitude_and_longitude,
                'status' => 1,
                'updated_at' => now(),
            ]);

            return response()->json(['Check out successfully complete'], 200);
        } 
        else     
        {
            return response()->json(['error' => 'No check-in record found for this user'], 404);
        }
    }


    // public function checkInData(Request $request)
    // {
    //     $user = Auth::user();
    //     date_default_timezone_set('Asia/Kolkata');
    //     $date = date('d-m-Y');

    //     $lastReading = OdometerReading::where('user_id', $user->id)->latest()->first();

    //     if($lastReading) 
    //     {
    //         if ($lastReading->check_in_date === $date) 
    //         {
    //             if ($lastReading->status == 1) 
    //             {
    //                 return response()->json(['message' => 'Data not found'], 346);
    //             }
    //             return response()->json($lastReading, 200);
    //         }
    //     }
    //     return response()->json(['message' => 'Data not found'], 346);
    // }


 

public function checkInData(Request $request)
{
    $user = Auth::user();
    $date = date('d-m-Y');

    $lastReading = OdometerReading::where('user_id', $user->id)->latest()->first();

    if ($lastReading) {
        if ($lastReading->check_in_date === $date) {
            if ($lastReading->status == 1) {
                return response()->json(['message' => 'Data not found'], 346);
            }

            $data = $lastReading->toArray();

     
            $data['created_at'] = Carbon::parse($lastReading->created_at)
                ->format('d-m-Y h:i A');

            $data['updated_at'] = Carbon::parse($lastReading->updated_at)
                ->format('d-m-Y h:i A');

            // Remove original UTC timestamps
            // unset($data['created_at'], $data['updated_at']);

            return response()->json($data, 200);
        }
    }

    return response()->json(['message' => 'Data not found'], 346);
}



    public function passwordResetRequest(Request $request)
    {
        $user = Auth::user();
        date_default_timezone_set('Asia/Kolkata');
        $date = date('d-m-Y');

        $existingRequest = PasswordResetRequest::where('user_id', $user->id)
            ->where('status', 1)
            ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'A password reset request is already in progress.',
            ], 409);
        }

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'password' => $request->password,
            'request_date' => $date,
            'status' => 1,
        ]);

        return response()->json(['message' => 'Request added successfully'], 200);
    }

    public function timeline(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $locations = array_merge(
                    GarageData::where('executive_id', $user->id)->whereDate('created_at', $today)->pluck('location')->toArray(),
                    DriverData::where('executive_id', $user->id)->whereDate('created_at', $today)->pluck('location')->toArray(),
                    OwnerData::where('executive_id', $user->id)->whereDate('created_at', $today)->pluck('location')->toArray(),
                    SpotData::where('executive_id', $user->id)->whereDate('created_at', $today)->pluck('location')->toArray(),
                    AccidentPersonData::where('executive_id', $user->id)->whereDate('created_at', $today)->pluck('location')->toArray()
                    );

        return response()->json($locations, 200);
    }

    public function weeklyChart(Request $request)
    {
        $user = Auth::user();
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        $weeklyCounts = [
            'sun' => 0,
            'mon' => 0,
            'tue' => 0,
            'wed' => 0,
            'thu' => 0,
            'fri' => 0,
            'sat' => 0,
        ];

        $garageData = GarageData::where('executive_id', $user->id)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->get();

        foreach ($garageData as $data) 
        {
            $weeklyCounts[strtolower($data->created_at->format('D'))]++;
        }

        $driverData = DriverData::where('executive_id', $user->id)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->get();

        foreach ($driverData as $data) {
            $weeklyCounts[strtolower($data->created_at->format('D'))]++;
        }

        $ownerData = OwnerData::where('executive_id', $user->id)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->get();

        foreach ($ownerData as $data) {
            $weeklyCounts[strtolower($data->created_at->format('D'))]++;
        }

        $spotData = SpotData::where('executive_id', $user->id)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->get();

        foreach ($spotData as $data) {
            $weeklyCounts[strtolower($data->created_at->format('D'))]++;
        }

        $accidentPersonData = AccidentPersonData::where('executive_id', $user->id)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->get();
            
        foreach ($accidentPersonData as $data) {
            $weeklyCounts[strtolower($data->created_at->format('D'))]++;
        }

        return response()->json($weeklyCounts);
    }

    public function monthlyChart(Request $request)
    {
        $user = Auth::user();

        $monthlyCounts = [];

        $now = now();

        for ($i = 3; $i >= 0; $i--) {
            $monthStart = $now->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $now->copy()->subMonths($i)->endOfMonth();

            $monthName = $monthStart->format('F');

            $garageCount = GarageData::where('executive_id', $user->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();

            $driverCount = DriverData::where('executive_id', $user->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();

            $ownerCount = OwnerData::where('executive_id', $user->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();

            $spotCount = SpotData::where('executive_id', $user->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();

            $accidentCount = AccidentPersonData::where('executive_id', $user->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();

            $monthlyCounts[$monthName] = $garageCount + $driverCount + $ownerCount + $spotCount + $accidentCount;
        }

        return response()->json($monthlyCounts);
    }
}
