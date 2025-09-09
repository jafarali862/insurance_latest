<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Salary;

class ExecutiveController extends Controller
{
    // public function odometerList()
    // {
    //     $data = DB::table('odometer_readings')
    //         ->leftJoin('users', 'odometer_readings.user_id', '=', 'users.id')
    //         ->select('odometer_readings.*', 'users.name as user_name')
    //         ->orderBy('odometer_readings.updated_at', 'desc')
    //         ->paginate(10);

    //     return view('dashboard.odometer.index')->with(['odometerRecords' => $data]);
    // }


    public function odometerList(Request $request)
    {
        // $query = DB::table('odometer_readings')
        // ->leftJoin('users', 'odometer_readings.user_id', '=', 'users.id')
        // ->select('odometer_readings.*', 'users.name as user_name');

        // if ($request->filled('from_date')) {
        // $query->whereDate('odometer_readings.created_at', '>=', $request->from_date);
        // }

        // if ($request->filled('to_date')) {
        // $query->whereDate('odometer_readings.created_at', '<=', $request->to_date);
        // }

        // // Default to today's records if no filters
        // // if (!$request->filled('from_date') && !$request->filled('to_date')) {
        // // $query->whereDate('odometer_readings.created_at', today());
        // // }

        // $data = $query->orderBy('odometer_readings.updated_at', 'desc')->paginate(10);

        // return view('dashboard.odometer.index')->with([
        // 'odometerRecords' => $data,
        // 'from_date' => $request->from_date,
        // 'to_date' => $request->to_date,
        // ]);

        $query = DB::table('odometer_readings')
        ->leftJoin('users', 'odometer_readings.user_id', '=', 'users.id')
        ->select('odometer_readings.*', 'users.name as user_name');

    if ($request->filled('from_date')) {
        $query->whereDate('odometer_readings.created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('odometer_readings.created_at', '<=', $request->to_date);
    }

    $data = $query->orderBy('odometer_readings.updated_at', 'desc')->get(); // NOTE: ->get()

    return view('dashboard.odometer.index')->with([
        'odometerRecords' => $data,
        'from_date' => $request->from_date,
        'to_date' => $request->to_date,
    ]);
    }


    public function view($id)
    {
        $record = DB::table('odometer_readings')
            ->leftJoin('users', 'odometer_readings.user_id', '=', 'users.id')
            ->select(
                'odometer_readings.check_in_km',
                'odometer_readings.check_in_image',
                'odometer_readings.check_in_time',
                'odometer_readings.check_in_date',
                'odometer_readings.check_out_km',
                'odometer_readings.check_out_image',
                'odometer_readings.check_out_time',
                'odometer_readings.check_out_date',
                'odometer_readings.check_in_latitude_and_longitude',
                'odometer_readings.check_out_latitude_and_longitude',
                'users.name as user_name'
            )
            ->where('odometer_readings.id', $id)
            ->first();

        return view('dashboard.odometer.view')->with(['record' => $record]);
    }

    public function salaryCreate(Request $request)
    {
        $users = User::where('role', '!=', 1)->get();
        return view('dashboard.salary.create')->with(['users' => $users]);
    }

    public function salaryStore(Request $request)
    {
        $request->validate([
            'user' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'month_year' => 'required|string',
            'total_salary' => 'required|numeric|min:0',
        ]);

        date_default_timezone_set('Asia/Kolkata');
        $date = date('d-m-Y');

        Salary::create([
            'user_id' => $request->user,
            'basic' => $request->basic_salary,
            'allowance' => $request->allowance,
            'bonus' => $request->bonus,
            'total' => $request->total_salary,
            'month_year' => $request->month_year,
            'date' => $date, 
        ]);

        return response()->json(['message' => 'Salary created successfully!'], 201);
    }

    public function salaryIndex()
    {
        $salaries = Salary::orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.salary.index', compact('salaries'));
    }
}


