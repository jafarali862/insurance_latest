<?php

namespace App\Http\Controllers;
// use PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ReportGenerateController extends Controller
{

    public function requestReport(Request $request)
    {
        // $search = $request->input('search', '');
        
        // $reports = DB::table('assign_work_data as awd')
        //         ->leftJoin('case_assignments as ca', 'awd.case_id', '=', 'ca.id')
        //         ->leftJoin('insurance_customers as ic', 'ca.customer_id', '=', 'ic.id')
        //         ->leftJoin('insurance_companies as icomp', 'ca.company_id', '=', 'icomp.id')
        //         ->when(!empty($search), function ($query) use ($search) 
        //         {
        //             $query->where(function ($q) use ($search) {
        //                 $q->where('ic.name', 'like', '%' . $search . '%')
        //                 ->orWhere('icomp.name', 'like', '%' . $search . '%')
        //                 ->orWhere('ca.date', 'like', '%' . $search . '%')
        //                 ->orWhere('ca.type', 'like', '%' . $search . '%');;
        //             });
        //         })
        //     ->select(
        //         'awd.id as report_id',
        //         'ca.id as case_assignment_id', 
        //         'ic.name as customer_name', 'ic.crime_number','ic.police_station',
        //         'icomp.name as company_name',
        //         'ca.date as date',
        //         'ca.type as type'
        //     )
        //     ->orderBy('ca.id', 'desc') 
        //     ->paginate(10);

        // $reports =  DB::table('assign_work_data as awd')
        //             ->join('questionnaire_submissions as qs', 'awd.case_id', '=', 'qs.case_id')
        //             ->leftJoin('case_assignments as ca', 'awd.case_id', '=', 'ca.id')
        //             ->leftJoin('insurance_customers as ic', 'ca.customer_id', '=', 'ic.id')
        //             ->leftJoin('insurance_companies as icomp', 'ca.company_id', '=', 'icomp.id')
        //             ->when(!empty($search), function ($query) use ($search) {
        //             $query->where(function ($q) use ($search) {
        //             $q->where('ic.name', 'like', '%' . $search . '%')
        //             ->orWhere('icomp.name', 'like', '%' . $search . '%')
        //             ->orWhere('ca.date', 'like', '%' . $search . '%')
        //             ->orWhere('ca.type', 'like', '%' . $search . '%');
        //             });
        //             })

        //             ->select(DB::raw('MAX(awd.id) as report_id'), 'qs.case_id','ca.id as case_assignment_id','ic.name as customer_name',
        //                     'ic.crime_number','ic.police_station','icomp.name as company_name','ca.date','ca.type')

        //             ->groupBy('qs.case_id','ca.id','ic.name','ic.crime_number','ic.police_station','icomp.name','ca.date','ca.type')
        //             ->orderBy('ca.id', 'desc')
        //             ->paginate(10);


            $search = $request->input('search', '');
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');

            $reports = DB::table('assign_work_data as awd')
            ->join('questionnaire_submissions as qs', 'awd.case_id', '=', 'qs.case_id')
            ->leftJoin('case_assignments as ca', 'awd.case_id', '=', 'ca.id')
            ->leftJoin('insurance_customers as ic', 'ca.customer_id', '=', 'ic.id')
            ->leftJoin('insurance_companies as icomp', 'ca.company_id', '=', 'icomp.id')

            ->when(!empty($search), function ($query) use ($search) 
            {
            $query->where(function ($q) use ($search) {
            $q->where('ic.name', 'like', '%' . $search . '%')
            ->orWhere('icomp.name', 'like', '%' . $search . '%')
            ->orWhere('ca.date', 'like', '%' . $search . '%')
            ->orWhere('ca.type', 'like', '%' . $search . '%');
            });
            })

            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) 
            {
            $query->whereBetween('qs.created_at', [
            Carbon::parse($fromDate)->startOfDay(),
            Carbon::parse($toDate)->endOfDay()
            ]);
            })

            ->select(DB::raw('MAX(awd.id) as report_id'), 'qs.case_id', 'ca.id as case_assignment_id', 'ic.name as customer_name',
            'ic.crime_number', 'ic.police_station', 'icomp.name as company_name', 'ca.date', 'ca.type')
            ->groupBy('qs.case_id', 'ca.id', 'ic.name', 'ic.crime_number', 'ic.police_station', 'icomp.name', 'ca.date', 'ca.type')
            ->orderBy('ca.id', 'desc')
            ->paginate(10);

            return view('dashboard.report.report-request', [
            'reports' => $reports,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'search' => $search,
            ]);

        // return view('dashboard.report.report-request', ['reports' => $reports]);
    }

    public function requestReportView($id, Request $request)
    {
        // Main report data
        $report = DB::table('assign_work_data as awd')
            ->leftJoin('case_assignments as ca', 'awd.case_id', '=', 'ca.id')
            ->leftJoin('insurance_customers as ic', 'ca.customer_id', '=', 'ic.id')
            ->leftJoin('insurance_companies as icomp', 'ca.company_id', '=', 'icomp.id')
            ->select('awd.*', 'ic.name as customer_name', 'icomp.name as company_name', 'ca.date as date', 'ca.type as type' ,'ca.id as case_id','ca.is_fake')
            ->where('awd.id', $id)
            ->first();

        // Retrieve related data with executive names
      
        $garageData = DB::table('garage_data as gd')
            ->leftJoin('users as u', 'gd.executive_id', '=', 'u.id')
            ->select('gd.*', 'u.name as executive_name')
            ->where('gd.assign_work_id', $id)
            ->get();

            

        $garageQuestions = Question::where('data_category', 'garage_data')->get();
        $driverQuestions = Question::where('data_category', 'driver_data')->get();
        $spotQuestions = Question::where('data_category', 'spot_data')->get();
        $ownerQuestions = Question::where('data_category', 'owner_data')->get();
        $accidentQuestions = Question::where('data_category', 'accident_person_data')->get();


        $driverData = DB::table('driver_data as dd')
            ->leftJoin('users as u', 'dd.executive_id', '=', 'u.id')
            ->select('dd.*', 'u.name as executive_name')
            ->where('dd.assign_work_id', $id)
            ->get();

        $spotData = DB::table('spot_data as sd')
            ->leftJoin('users as u', 'sd.executive_id', '=', 'u.id')
            ->select('sd.*', 'u.name as executive_name')
            ->where('sd.assign_work_id', $id)
            ->get();

        $ownerData = DB::table('owner_data as od')
            ->leftJoin('users as u', 'od.executive_id', '=', 'u.id')
            ->select('od.*', 'u.name as executive_name')
            ->where('od.assign_work_id', $id)
            ->get();

        $accidentPersonData = DB::table('accident_person_data as apd')
            ->leftJoin('users as u', 'apd.executive_id', '=', 'u.id')
            ->select('apd.*', 'u.name as executive_name')
            ->where('apd.assign_work_id', $id)
            ->get();

        $users = User::where('role', '!=', 1)->where('status', '!=', 0)->get();

         
           // dd($accidentPersonData);
        return view('dashboard.report.view')->with([
            'report' => $report,
            'garageData' => $garageData,
            'driverData' => $driverData,
            'spotData' => $spotData,
            'ownerData' => $ownerData,
            'accidentPersonData' => $accidentPersonData,
            'garageQuestions'=>$garageQuestions,
            'driverQuestions'=>$driverQuestions,
            'spotQuestions'=>$spotQuestions,
            'ownerQuestions'=>$ownerQuestions,
            'accidentQuestions'=>$accidentQuestions,
            'users'=>$users
          
        ]);
    }

    public function finalReportDownload($id)
    {

        $report = DB::table('assign_work_data as awd')
            ->leftJoin('case_assignments as ca', 'awd.case_id', '=', 'ca.id')
            ->leftJoin('insurance_customers as ic', 'ca.customer_id', '=', 'ic.id')
            ->leftJoin('insurance_companies as icomp', 'ca.company_id', '=', 'icomp.id')
            ->leftJoin('users as garage', 'ca.executive_garage', '=', 'garage.id')
            ->leftJoin('users as driver', 'ca.executive_driver', '=', 'driver.id')
            ->leftJoin('users as spot', 'ca.executive_spot', '=', 'spot.id')
            ->leftJoin('users as owner', 'ca.executive_meeting', '=', 'owner.id')
            ->leftJoin('users as accident_person', 'ca.executive_meeting', '=', 'accident_person.id')
            ->select('awd.*',
                'ic.name as customer_name',
                'icomp.name as company_name',
                'ca.date as date',
                'ca.type as type',
                'garage.name as garage_name',
                'driver.name as executive_driver_name',
                'spot.name as spot_name',
                'owner.name as owner_name',
                'accident_person.name as accident_person_name',
            )
            ->where('awd.id', $id)
            ->first();
        $pdf = PDF::loadView('dashboard.report.final-report', compact('report'));
        return $pdf->stream('final-report.pdf');
    }

    public function garageReAssign(Request $request)
    {
       
    $request->validate([
        'id' => 'required|integer|exists:assign_work_data,id',
        'executive_id' => 'required|integer|exists:users,id',
    ]);

    $assignWorkId = $request->id;
    $executiveId = $request->executive_id;

    DB::beginTransaction();

    try 
    {
        $caseId = DB::table('assign_work_data')
                    ->where('id', $assignWorkId)
                    ->value('case_id');

        if (!$caseId) 
        {
        throw new \Exception("Case ID not found for assign_work_data ID {$assignWorkId}");
        }

        DB::table('assign_work_data')->where('id', $assignWorkId)->update([
            'garage_reassign_status' => 0,
            'updated_at' => Carbon::now(),
        ]);

        DB::table('garage_data')->where('assign_work_id', $assignWorkId)->update([
            'executive_id' => $executiveId,
            'updated_at' => Carbon::now(),
        ]);

        DB::table('case_assignments')->where('id', $caseId)->update([
            'executive_garage' => $executiveId,
            'updated_at' => Carbon::now(),
        ]);

        DB::commit();

        return back()->with('success', 'Garage reassigned successfully.');
    } 
    catch (\Exception $e) 
    {
        DB::rollBack();
        return back()->withErrors(['error' => 'Reassignment failed: ' . $e->getMessage()]);
    }

}


    // }

    public function driverReAssign(Request $request)
    {
       
        $request->validate([
        'id' => 'required|integer|exists:assign_work_data,id',
        'executive_id' => 'required|integer|exists:users,id',
        ]);

        $assignWorkId = $request->id;
        $executiveId = $request->executive_id;

        DB::beginTransaction();

        try 
        {
            $caseId = DB::table('assign_work_data')
                        ->where('id', $assignWorkId)
                        ->value('case_id');

            if (!$caseId) 
            {
            throw new \Exception("Case ID not found for assign_work_data ID {$assignWorkId}");
            }

            DB::table('assign_work_data')->where('id', $assignWorkId)->update([
                'driver_reassign_status' => 0,
                'updated_at' => Carbon::now(),
            ]);

            DB::table('driver_data')->where('assign_work_id', $assignWorkId)->update([
                'executive_id' => $executiveId,
                'updated_at' => Carbon::now(),
            ]);

            DB::table('case_assignments')->where('id', $caseId)->update([
                'executive_driver' => $executiveId,
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();

            return back()->with('success', 'Driver reassigned successfully.');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            return back()->withErrors(['error' => 'Reassignment failed: ' . $e->getMessage()]);
        }

    }

    public function spotReAssign(Request $request)
    {
       
        $request->validate([
        'id' => 'required|integer|exists:assign_work_data,id',
        'executive_id' => 'required|integer|exists:users,id',
        ]);

        $assignWorkId = $request->id;
        $executiveId = $request->executive_id;

        DB::beginTransaction();

        try 
        {
            $caseId = DB::table('assign_work_data')
                        ->where('id', $assignWorkId)
                        ->value('case_id');

            if (!$caseId) 
            {
            throw new \Exception("Case ID not found for assign_work_data ID {$assignWorkId}");
            }

            DB::table('assign_work_data')->where('id', $assignWorkId)->update([
                'spot_reassign_status' => 0,
                'updated_at' => Carbon::now(),
            ]);

            DB::table('spot_data')->where('assign_work_id', $assignWorkId)->update([
                'executive_id' => $executiveId,
                'updated_at' => Carbon::now(),
            ]);

            DB::table('case_assignments')->where('id', $caseId)->update([
                'executive_spot' => $executiveId,
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();

            return back()->with('success', 'Spot reassigned successfully.');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            return back()->withErrors(['error' => 'Reassignment failed: ' . $e->getMessage()]);
        }
    }

    public function ownerReAssign(Request $request)
    {

        $request->validate([
        'id' => 'required|integer|exists:assign_work_data,id',
        'executive_id' => 'required|integer|exists:users,id',
        ]);

        $assignWorkId = $request->id;
        $executiveId = $request->executive_id;

        DB::beginTransaction();

        try 
        {
            $caseId = DB::table('assign_work_data')
                        ->where('id', $assignWorkId)
                        ->value('case_id');

            if (!$caseId) 
            {
            throw new \Exception("Case ID not found for assign_work_data ID {$assignWorkId}");
            }

            DB::table('assign_work_data')->where('id', $assignWorkId)->update([
                'owner_reassign_status' => 0,
                'updated_at' => Carbon::now(),
            ]);

            DB::table('owner_data')->where('assign_work_id', $assignWorkId)->update([
                'executive_id' => $executiveId,
                'updated_at' => Carbon::now(),
            ]);

            DB::table('case_assignments')->where('id', $caseId)->update([
                'executive_meeting' => $executiveId,
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();

            return back()->with('success', 'Owner reassigned successfully.');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            return back()->withErrors(['error' => 'Reassignment failed: ' . $e->getMessage()]);
        }

    }

    public function accidentPersonReAssign(Request $request)
    {
         $request->validate([
        'id' => 'required|integer|exists:assign_work_data,id',
        'executive_id' => 'required|integer|exists:users,id',
        ]);

        $assignWorkId = $request->id;
        $executiveId = $request->executive_id;

        DB::beginTransaction();

        try 
        {
            $caseId = DB::table('assign_work_data')
                        ->where('id', $assignWorkId)
                        ->value('case_id');

            if (!$caseId) 
            {
            throw new \Exception("Case ID not found for assign_work_data ID {$assignWorkId}");
            }

            DB::table('assign_work_data')->where('id', $assignWorkId)->update([
                'accident_person_reassign_status' => 0,
                'updated_at' => Carbon::now(),
            ]);

            DB::table('accident_person_data')->where('assign_work_id', $assignWorkId)->update([
                'executive_id' => $executiveId,
                'updated_at' => Carbon::now(),
            ]);

            DB::table('case_assignments')->where('id', $caseId)->update([
                'executive_accident_person' => $executiveId,
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();

            return back()->with('success', 'Accident Persons reassigned successfully.');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();
            return back()->withErrors(['error' => 'Reassignment failed: ' . $e->getMessage()]);
        }
    }

    public function saveSelected(Request $request)
    {

   try {
    $request->validate([
        'case_id' => 'required|integer',
        'column_name' => 'required|string',
        'value' => 'required|string',
    ]);

    $caseId = $request->case_id;
    $column = $request->column_name;
    $value = trim($request->value); 

    $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
    $mediaExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'mp3', 'wav', 'ogg', 'mp4', 'm4a', 'aac'];

    // Save media as JSON array, plain text as-is (no json_encode)
    $finalValue = in_array($extension, $mediaExtensions)
        ? json_encode([$value])   // media file: ["filename.jpg"]
        : $value;                 // plain text: Garage786 (no quotes)

    $tables = ['final_reports_new', 'final_reports'];

    foreach ($tables as $tableName) {
        if (!Schema::hasTable($tableName)) {
            continue;
        }

        // Ensure column exists as VARCHAR(255), not JSON
        if (!Schema::hasColumn($tableName, $column)) {
            Schema::table($tableName, function (Blueprint $table) use ($column) {
                $table->string($column, 255)->nullable(); // VARCHAR(255)
            });
        }

        $existing = DB::table($tableName)->where('case_id', $caseId)->first();

        if ($existing) {
            DB::table($tableName)
                ->where('case_id', $caseId)
                ->update([
                    $column => $finalValue,
                    'updated_at' => now()
                ]);
        } else {
            DB::table($tableName)
                ->insert([
                    'case_id' => $caseId,
                    $column => $finalValue,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
        }
    }

    return response()->json([
        'data' => [
            'status' => 200,
            'message' => 'Answer saved successfully in all relevant tables.'
        ]
    ], 200);

} catch (\Exception $e) {
    return response()->json([
        'error' => 'Error saving owner selection: ' . $e->getMessage(),
    ], 500);
}

     
    }


}
