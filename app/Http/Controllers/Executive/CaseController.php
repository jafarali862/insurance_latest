<?php

namespace App\Http\Controllers\Executive;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cases = DB::table('case_assignments')
            ->leftJoin('insurance_companies', 'case_assignments.company_id', '=', 'insurance_companies.id')
            ->leftJoin('insurance_customers', 'case_assignments.customer_id', '=', 'insurance_customers.id')
            ->leftJoin('users as driver', 'case_assignments.executive_driver', '=', 'driver.id')
            ->leftJoin('users as garage', 'case_assignments.executive_garage', '=', 'garage.id')
            ->leftJoin('users as spot', 'case_assignments.executive_spot', '=', 'spot.id')
            ->leftJoin('users as meeting', 'case_assignments.executive_meeting', '=', second: 'meeting.id')
            ->where(function ($query) {
                $id = Auth::user()->id;
                $query->where('executive_driver', $id)
                    ->orWhere('executive_garage', $id)
                    ->orWhere('executive_spot', $id)
                    ->orWhere('executive_meeting', $id);
            })
            ->select(
                'case_assignments.*',
                'insurance_companies.name as company_name',
                'insurance_customers.name as customer_name',
                'driver.name as driver_name',
                'garage.name as garage_name',
                'spot.name as spot_name',
                'meeting.name as meeting_name'
            )
            ->paginate(10);

        return view("dashboard.executive.case.index", compact('cases'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
