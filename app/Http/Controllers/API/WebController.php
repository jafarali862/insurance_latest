<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OdometerReading;
use App\Models\Salary;
use App\Models\User;
use App\Models\GarageData;
use App\Models\DriverData;
use App\Models\SpotData;
use App\Models\AccidentPersonData;
use App\Models\OwnerData;
use Carbon\Carbon;


class WebController extends Controller
{
    public function report($id)
    {
        $user = User::where('id', $id)->first();
        return view('web.report')->with(['user' => $user]);
    }

    public function salaryReport($id)
    {
        $salaries = Salary::where('user_id', $id)->orderBy('created_at', 'desc')->paginate(10);
        return view('web.salary', compact('salaries'));
    }

    public function todayRecord($id)
    {
        $today = now()->startOfDay();

        $garageCount = GarageData::where('executive_id', $id)
            ->whereDate('created_at', $today)
            ->count();

        $driverCount = DriverData::where('executive_id', $id)
            ->whereDate('created_at', $today)
            ->count();

        $accidentPersonCount = AccidentPersonData::where('executive_id', $id)
            ->whereDate('created_at', $today)
            ->count();

        $ownerCount = OwnerData::where('executive_id', $id)
            ->whereDate('created_at', $today)
            ->count();

        $spotCount = SpotData::where('executive_id', $id)
            ->whereDate('created_at', $today)
            ->count();

        return view('web.today', compact('garageCount', 'driverCount', 'accidentPersonCount', 'ownerCount', 'spotCount'));
    }

    public function monthlyRecord($id)
    {
        $currentMonth = now()->month;

        $garageCount = GarageData::where('executive_id', $id)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        $driverCount = DriverData::where('executive_id', $id)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        $accidentPersonCount = AccidentPersonData::where('executive_id', $id)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        $ownerCount = OwnerData::where('executive_id', $id)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        $spotCount = SpotData::where('executive_id', $id)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        return view('web.monthly', compact('garageCount', 'driverCount', 'accidentPersonCount', 'ownerCount', 'spotCount'));
    }

    public function helpSupport()
    {
        return view('web.help-support');
    }

    public function odometer($id)
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $data = OdometerReading::where('user_id', $id)->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('web.odometer')->with(['data' => $data]);
    }


}
