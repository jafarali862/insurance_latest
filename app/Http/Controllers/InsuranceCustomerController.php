<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\InsuranceCase;
use App\Models\CaseAssignment;
use App\Models\AssignWorkData;
use App\Models\InsuranceCompany;
use App\Models\InsuranceCustomer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class InsuranceCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        
       
        $customers = DB::table('insurance_customers')
                    ->leftJoin('insurance_cases', 'insurance_customers.id', '=', 'insurance_cases.customer_id')

                     ->leftJoin('insurance_companies', 'insurance_companies.id', '=', 'insurance_customers.company_id')

                    ->select('insurance_customers.*', 'insurance_cases.case_status', 'insurance_cases.assigned_status','insurance_companies.name as custname')
                    ->when(!empty($search), function ($query) use ($search) 
                    {
                    $query->where(function ($q) use ($search) {
                    $q->where('insurance_customers.name', 'like', '%' . $search . '%')
                    ->orWhere('insurance_customers.phone', 'like', '%' . $search . '%');
                    });
                    })
                    ->orderBy('insurance_customers.created_at', 'desc')
                    ->paginate(15);     
        
        return view("dashboard.case.index",compact('customers'));
    }

    public function todaycase(Request $request)
    {
        $search = $request->input('search', '');

        $customers = DB::table('insurance_customers')
        ->leftJoin('insurance_cases', 'insurance_customers.id', '=', 'insurance_cases.customer_id')
        ->leftJoin('insurance_companies', 'insurance_companies.id', '=', 'insurance_customers.company_id')
        ->select(
        'insurance_customers.*',
        'insurance_cases.case_status',
        'insurance_cases.assigned_status',
        'insurance_companies.name as custname'
        )
        ->when(!empty($search), function ($query) use ($search) {
        $query->where(function ($q) use ($search) {
        $q->where('insurance_customers.name', 'like', '%' . $search . '%')
        ->orWhere('insurance_customers.phone', 'like', '%' . $search . '%');
        });
        })
        ->whereDate('insurance_cases.created_at', Carbon::today()) // ✅ filter for today
        ->orderBy('insurance_customers.created_at', 'desc')
        ->paginate(15);    

        return view("dashboard.case.index",compact('customers'));
    }


    public function view(string $id)
    { 
         $insuranceCustomer = InsuranceCustomer::all();
         $companies = InsuranceCompany::where('status', '!=', 0)->get();
         $users = User::where('role', '!=', 1)->where('status', '!=', 0)->get();

         $customers = DB::table('insurance_customers')
                    ->leftJoin('insurance_cases', 'insurance_customers.id', '=', 'insurance_cases.customer_id')
                    ->leftJoin('case_assignments', 'case_assignments.customer_id', '=', 'insurance_customers.id')
                    ->leftJoin('users as driver', 'driver.id', '=', 'case_assignments.executive_driver')
                    ->leftJoin('users as garage', 'garage.id', '=', 'case_assignments.executive_garage')
                    ->leftJoin('users as spot', 'spot.id', '=', 'case_assignments.executive_spot')
                    ->leftJoin('users as meeting', 'meeting.id', '=', 'case_assignments.executive_meeting')
                    ->leftJoin('users as accident', 'accident.id', '=', 'case_assignments.executive_accident_person')

                    ->leftJoin('insurance_companies', 'insurance_companies.id', '=', 'insurance_customers.company_id')
                    ->select('insurance_customers.*', 'insurance_cases.case_status', 'insurance_cases.assigned_status','insurance_cases.case_details',
                            'insurance_cases.insurance_type as ins_type','driver.id as driver_id',
                            'garage.id as garage_id','spot.id as spot_id','meeting.id as meeting_id','accident.id as accident_id','insurance_companies.name as custname',
                            'case_assignments.type as case_type','case_assignments.date as case_date','case_assignments.other as case_other','driver.name as driver_name',
                            'garage.name as garage_name','spot.name as spot_name','meeting.name as meeting_name','accident.name as accident_name')
                    ->where('insurance_customers.id',$id)
                    ->first(); 
                    
        return view("dashboard.case.view",compact('customers','companies','id','users','insuranceCustomer'));    
    }



    public function markFakeData(Request $request)
    {
    $caseId = $request->input('case_id');

    $case = CaseAssignment::find($caseId);

    if (!$case) {
        return back()->withErrors(['error' => 'Insurance case not found.']);
    }

    $case->is_fake = true; // or set a status column, e.g., $case->status = 'fake';
    $case->save();

    return back()->with('success', 'This section has been marked as fake data.');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $insuranceCustomer = InsuranceCustomer::all();
        $companies = InsuranceCompany::where('status', '!=', 0)->orderBy('id','desc')->get();
        $users = User::where('role', '!=', 1)->where('status', '!=', 0)->get();
        $executives = User::where('role', '!=', 1)->get();
        
        return view("dashboard.insurance.create")->with(["companies" => $companies, "executives" => $executives,"users"=>$users,'insuranceCustomer'=>$insuranceCustomer]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
        $request->validate([
            "company" => "required",
            "name" => "required",
            "father_name" => "required",
            "phone" => "required|size:10",
            "emergency_contact_number" => "required|size:10",
            "present_address" => "required",
            "permanent_address" => "required",
            "insurance_type" => "required",
            "case_details" => "required",
            'policy_no'=>"required",
            'policy_start'=>"required",
            'policy_end'=>"nullable",
            'investigation_type'=>'required',
            "Default_Executive" => "required",
            "executive_driver" => "required",
            "executive_garage" => "required",
            "executive_spot" => "required",
            "executive_meeting" => "required",
            "date" => "required",
            'other'=>"nullable",
            "intimation_report" => "nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048",

        ]);


        $intimationReportPath = null;

        if ($request->hasFile('intimation_report')) 
        {
        $file = $request->file('intimation_report');
        $filename = time() . '_' . $file->getClientOriginalName();
        $intimationReportPath = $file->storeAs('uploads/intimation_reports', $filename, 'public');
        }
        
        $customer = InsuranceCustomer::create([
            'company_id' => $request->company,
            'name' => $request->name,
            'father_name' => $request->father_name,
            'phone' => $request->phone,
            'emergency_contact_number' => $request->emergency_contact_number,
            'email' => $request->email,
            'present_address' => $request->present_address,
            'permanent_address' => $request->permanent_address,
            'status' => 1,
            'create_by' => Auth::user()->id,
            'update_by' => Auth::user()->id,
            'policy_no'=>$request->policy_no,
            'policy_start'=>$request->policy_start,
            'policy_end'=>$request->policy_end,
            'insurance_type' => $request->investigation_type,
            'crime_number' => $request->crime_no,
            'police_station' => $request->police_station,
            'intimation_report' => $intimationReportPath,

        ]);
        
       $case= InsuranceCase::create([
            'company_id' => $request->company,
            'customer_id' => $customer->id, 
            'insurance_type' => $request->insurance_type,
            'case_details' => $request->case_details,
            'status' => 1,
            'assigned_status' => 1,
            'case_status' => 1,
            'create_by' => Auth::user()->id,
            'update_by' => Auth::user()->id,
        ]);
        
        $executiveAccidentPerson = $request->investigation_type === 'MAC' ? $request->executive_accident_person : null;

        $caseAssignment = CaseAssignment::create([
            "case_id" =>            $case->id ?? null,
            "company_id" =>         $request->company,
            "customer_id" =>        $customer->id,
            "executive_driver" =>   $request->executive_driver,
            "executive_garage" =>   $request->executive_garage,
            "executive_spot" =>     $request->executive_spot,
            "executive_meeting" =>  $request->executive_meeting,
            "executive_accident_person" => $executiveAccidentPerson,
            "date" =>               $request->date,
            "type" => $request->investigation_type,
            "other" => $request->other,
            "status" => 1,
            "case_status" => 1,
            "create_by" => Auth::user()->id ?? null,
            "update_by" => Auth::user()->id,
        ]);

       AssignWorkData::create([
        "case_id" => $caseAssignment->id,
        ]);

        return response()->json([
        'success' => 'New insurance customer added successfully',
        'redirect_url' => route('case.index')
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    public function showAssignForm($customerId)
    {
    $insuranceCustomer = InsuranceCustomer::with('company')->findOrFail($customerId);
    $insuranceCompany = $insuranceCustomer->company;
    $users = User::all(); // Get executives
    $caseId = InsuranceCase::where('customer_id', $customerId)->first(); 
    $caseAssignment = CaseAssignment::where('case_id', $caseId->id ?? null)->first();

    return view('dashboard.assign.create', compact('insuranceCustomer', 'insuranceCompany', 'users', 'caseId', 'caseAssignment'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    { 
         $insuranceCustomer = InsuranceCustomer::all();
         $companies = InsuranceCompany::where('status', '!=', 0)->get();
         $users = User::where('role', '!=', 1)->where('status', '!=', 0)->get();
         $customers = DB::table('insurance_customers')
            ->leftJoin('insurance_cases', 'insurance_customers.id', '=', 'insurance_cases.customer_id')
            ->leftJoin('case_assignments', 'case_assignments.customer_id', '=', 'insurance_customers.id')
            ->leftJoin('users as driver', 'driver.id', '=', 'case_assignments.executive_driver')
            ->leftJoin('users as garage', 'garage.id', '=', 'case_assignments.executive_garage')
            ->leftJoin('users as spot', 'spot.id', '=', 'case_assignments.executive_spot')
            ->leftJoin('users as meeting', 'meeting.id', '=', 'case_assignments.executive_meeting')
            ->leftJoin('users as accident', 'accident.id', '=', 'case_assignments.executive_accident_person')
            ->select('insurance_customers.*', 'insurance_cases.case_status', 'insurance_cases.assigned_status','insurance_cases.case_details','insurance_cases.insurance_type as ins_type','driver.id as driver_id',
                     'garage.id as garage_id','spot.id as spot_id','meeting.id as meeting_id','accident.id as accident_id','case_assignments.type as case_type','case_assignments.date as case_date','case_assignments.other as case_other')
            ->where('insurance_customers.id',$id)
            ->first(); 
            
        return view("dashboard.case.edit",compact('customers','companies','id','users','insuranceCustomer'));    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        
    try 
    {
        $validator = Validator::make($request->all(), [
            "company" => "required",
            "name" => "required",
            "father_name" => "required",
            "phone" => "required|size:10",
            "emergency_contact_number" => "required|size:10",
            "present_address" => "required",
            "permanent_address" => "required",
            "insurance_type" => "required",
            "case_details" => "required",
            'policy_no' => "required",
            'policy_start' => "required",
            'policy_end' => "nullable",
            'investigation_type' => 'required',
            "Default_Executive" => "required",
            "executive_driver" => "required",
            "executive_garage" => "required",
            "executive_spot" => "required",
            "executive_meeting" => "required",
            "date" => "required",
            'other' => "nullable",
        ]);

        if ($validator->fails()) 
        {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = InsuranceCustomer::findOrFail($id);

        $intimationReportPath = $customer->intimation_report;

        if ($request->hasFile('intimation_report')) 
        {
            $file = $request->file('intimation_report');
            $filename = time() . '_' . $file->getClientOriginalName();
            $newPath = $file->storeAs('uploads/intimation_reports', $filename, 'public');

            // Delete old file if it exists
            if ($intimationReportPath && Storage::disk('public')->exists($intimationReportPath)) 
            {
            Storage::disk('public')->delete($intimationReportPath);
            }

            $intimationReportPath = $newPath;
        }

        $customer->update([
            'company_id' => $request->company,
            'name' => $request->name,
            'father_name' => $request->father_name,
            'phone' => $request->phone,
            'emergency_contact_number' => $request->emergency_contact_number,
            'email' => $request->email,
            'present_address' => $request->present_address,
            'permanent_address' => $request->permanent_address,
            'status' => 1,
            'create_by' => Auth::id(),
            'update_by' => Auth::id(),
            'policy_no' => $request->policy_no,
            'policy_start' => $request->policy_start,
            'policy_end' => $request->policy_end,
            'insurance_type' => $request->investigation_type,
            'crime_number' => $request->crime_no,
            'police_station' => $request->police_station,
            'intimation_report' => $intimationReportPath,
        ]);

        // Update InsuranceCase
        InsuranceCase::where('customer_id', $id)->update([
            'company_id' => $request->company,
            'customer_id' => $id,
            'insurance_type' => $request->insurance_type,
            'case_details' => $request->case_details,
            'create_by' => Auth::id(),
            'update_by' => Auth::id(),
        ]);

        // Update CaseAssignment
        $executiveAccidentPerson = $request->investigation_type === 'MAC' ? $request->executive_accident_person : null;

        CaseAssignment::where('customer_id', $id)->update([
            "executive_driver" => $request->executive_driver,
            "executive_garage" => $request->executive_garage,
            "executive_spot" => $request->executive_spot,
            "executive_meeting" => $request->executive_meeting,
            "executive_accident_person" => $executiveAccidentPerson,
            "date" => $request->date,
            "type" => $request->investigation_type,
            "other" => $request->other,
            "create_by" => Auth::id(),
            "update_by" => Auth::id(),
        ]);

        return response()->json([
            'success' => 'Insurance customer updated successfully',
            'redirect_url' => route('case.index')
        ]);
    } 

    catch (\Exception $e) 
    {
        // Log the full error for backend debugging
        Log::error('Update Error:', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the insurance customer.',
            'error' => $e->getMessage() // remove this in production
        ], 500);
    }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
