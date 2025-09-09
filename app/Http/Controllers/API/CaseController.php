<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\QuestionnaireSubmission;
use App\Models\InsuranceCompany;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AssignWorkData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class CaseController extends Controller
{
    public function allCaseList(Request $request)
    {
       
        $id = Auth::user()->id;

        $cases = DB::table('case_assignments')
                ->leftJoin('insurance_customers', 'case_assignments.customer_id', '=', 'insurance_customers.id')
                ->leftJoin('assign_work_data', 'case_assignments.id', '=', 'assign_work_data.case_id')
                ->leftJoin('insurance_companies', 'case_assignments.company_id', '=', 'insurance_companies.id')
                ->leftJoin('insurance_cases', 'insurance_customers.id', '=', 'insurance_cases.customer_id')
                ->leftJoin('users as driver', 'case_assignments.executive_driver', '=', 'driver.id')
                ->leftJoin('users as garage', 'case_assignments.executive_garage', '=', 'garage.id')
                ->leftJoin('users as spot', 'case_assignments.executive_spot', '=', 'spot.id')
                ->leftJoin('users as meeting', 'case_assignments.executive_meeting', '=', 'meeting.id')
                ->leftJoin('users as accident', 'case_assignments.executive_accident_person', '=', 'accident.id')

                ->where(function ($query) use ($id) 
                {

                    $query->where('executive_driver', $id)
                    ->orWhere('executive_garage', $id)
                    ->orWhere('executive_spot', $id)
                    ->orWhere('executive_meeting', $id)
                    ->orWhere('executive_accident_person', $id);
                    })
                    
                ->where('case_assignments.status', 1)
                ->select(
                    'case_assignments.id',
                    'assign_work_data.id as assign_work_data_id',
                    'case_assignments.date',
                    'case_assignments.other',
                    'insurance_cases.case_details',
                    'insurance_cases.insurance_type',
                    'case_assignments.status',
                    'case_assignments.type',
                    'insurance_customers.name as customer_name',
                    'insurance_customers.present_address as customer_place',
                    'insurance_customers.phone',
                    'insurance_companies.name as company_name',
                    'insurance_companies.questionnaires as company_questionnaires',
                    'driver.name as driver_name',
                    'assign_work_data.updated_at',
                    'case_assignments.executive_driver','case_assignments.executive_garage','case_assignments.executive_spot',
                    'case_assignments.executive_meeting', 'case_assignments.executive_accident_person'
                )
                ->get();

                if ($cases->isEmpty()) 
                {
                return response()->json(['message' => 'No case data found.'], 200);
                }

                $result = [];

                foreach ($cases as $case) {
                $assignData = DB::table('assign_work_data')->where('case_id', $case->id)->first();

                $reassignStatus = [
                    'garage'    => $assignData->garage_reassign_status ?? 0,
                    'driver'    => $assignData->driver_reassign_status ?? 0,
                    'spot'      => $assignData->spot_reassign_status ?? 0,
                    'meeting'   => $assignData->owner_reassign_status ?? 0,
                    'accident'  => $assignData->accident_person_reassign_status ?? 0,
                ];


        $executiveIds = [
        'driver'   => $case->executive_driver,
        'garage'   => $case->executive_garage,
        'spot'     => $case->executive_spot,
        'meeting'  => $case->executive_meeting,
        'accident' => $case->executive_accident_person,
        ];

        $works = [];

        foreach (['driver', 'garage', 'spot', 'meeting', 'accident'] as $work)
         {
            if ($reassignStatus[$work] != 1 && ($work != 'accident' || $case->type != 'OD')) {
                $questionnaire = [];

                $questionnaires = json_decode($case->company_questionnaires, true);
                if (!empty($questionnaires) && isset($questionnaires[$work]))
                 {
                   
                    foreach ($questionnaires[$work] as $fieldKey => $field)                 
                    {

                    if (isset($field['type']) && $field['type'] === 'file') 
                    {
                   
                    {

                    $field['multiple'] = true;
                    }
                    }

                    $questionnaire[$fieldKey] = $field;
                    }
                }

                $works[] = [
                    'work' => $work,
                    'case_work_id' => $work . $case->phone . $case->id . ($case->updated_at ?? 0),
                    'executive_id'   => $executiveIds[$work],
                    'questionnaire' => $questionnaire
                ];
            }
        }

        if (!empty($works)) 
        {
            $result[] = [
                'id'                => $case->id,
                'assign_id'         => $case->assign_work_data_id,
                'customer_name'     => $case->customer_name,
                'customer_place'    => $case->customer_place,
                'phone'             => $case->phone,
                'company_name'      => $case->company_name,
                'type'              => $case->type,
                'status'            => $case->status,
                'driver_name'       => $case->driver_name,
                'date'              => $case->date,
                'other'             => $case->other,
                'case_details'      => $case->case_details,
                'insurance_type'    => $case->insurance_type,
                'login_id'          =>Auth::user()->id,
                'works'             => $works
            ];
        }
        }
        return response()->json($result, 200);
    }

  

    // private function createCaseArray($case, $work,$questionnaire = [])
    // {
    //     return [
    //         'id' => $case->id,
    //         'customer_name' => $case->customer_name,
    //         'customer_place' => $case->customer_place,
    //         'phone' => $case->phone,
    //         'company_name' => $case->company_name,
    //         'type' => $case->type,
    //         'driver_name' => $case->driver_name,
    //         'date' => $case->date,
    //         'other' => $case->other,
    //         'case_details' => $case->case_details,
    //         'insurance_type' => $case->insurance_type,
    //         'work' => $work,
    //         'case_work_id' => $work . $case->phone . $case->id . $case->updated_at ?? 0,
    //         'questionnaire' => $questionnaire

    //     ];
    // }


     
     public function upload(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);

        $user = User::findOrFail($id);

        // Delete old image
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $request->file('image')->store('profile_images', 'public');

        $user->profile_image = $path;
        $user->save();

        return response()->json([
            'message' => 'Profile image updated successfully',
            'image_url' => asset('storage/' . $path)
        ]);
    }

    // Get profile image URL
    public function show($id)
    {
        $user = User::findOrFail($id);

        if (!$user->profile_image) {
            return response()->json(['message' => 'No image found'], 404);
        }

        return response()->json([
            'image_url' => asset('storage/' . $user->profile_image)
        ]);
    }

   
      
    public function getAssignments($executive_id, $period)
    {
     
        // $startDate = match ($period) 
        // {
        //     'daily' => Carbon::today(),
        //     'weekly' => Carbon::now()->startOfWeek(),
        //     'monthly' => Carbon::now()->startOfMonth(),
        //     default => null,
        // };

        // if (!$startDate) 
        // {
        //  return response()->json(['error' => 'Invalid period. Use daily, weekly, or monthly.'], 400);
        // }

        // $query = DB::table('case_assignments')
        //         ->where(function ($q) use ($executive_id) 
        //         {
        //         $q->where('executive_driver', $executive_id)
        //         ->orWhere('executive_garage', $executive_id)
        //         ->orWhere('executive_spot', $executive_id)
        //         ->orWhere('executive_meeting', $executive_id)
        //         ->orWhere('executive_accident_person', $executive_id);
        //         })
        //         ->whereDate('created_at', '>=', $startDate);


        // $new = (clone $query)->where('status', 1)->count();
        // $completed = (clone $query)->where('status', 2)->count();

        // return response()->json([
            
        //     'executive_id' => $executive_id,
        //     'period' => $period,
        //     'start_date' => $startDate->toDateString(),
        //     'new' => $new,
        //     'completed' => $completed,
        // ]);


        $period     = strtolower($period);

        $startDate  = null;
        $endDate    = null;

        if ($period === 'daily') 
        {
        $startDate  = Carbon::today();
        $endDate    = Carbon::tomorrow()->subSecond();  // End of today
        } 

        elseif ($period === 'weekly') 
        {
        $startDate  = Carbon::now()->startOfWeek();
        $endDate    = Carbon::now()->endOfWeek();
        } 
        
        elseif ($period === 'monthly') 
        {
        $startDate  = Carbon::now()->startOfMonth();
        $endDate    = Carbon::now()->endOfMonth();
        } 

        else 
        {
        $months = [
        'january' => 1,
        'february' => 2,
        'march' => 3,
        'april' => 4,
        'may' => 5,
        'june' => 6,
        'july' => 7,
        'august' => 8,
        'september' => 9,
        'october' => 10,
        'november' => 11,
        'december' => 12,
        ];

        if(array_key_exists($period, $months)) 
        {
        $year           = Carbon::now()->year;
        $monthNumber    = $months[$period];

        $startDate      = Carbon::create($year, $monthNumber, 1)->startOfDay();
        $endDate        = Carbon::create($year, $monthNumber, 1)->endOfMonth()->endOfDay();
        }

        else 
        {
        return response()->json(['error' => 'Invalid period. Use daily, weekly, monthly or month names (january-december).'], 400);
        }
        }


        $query = DB::table('case_assignments')
        ->where(function ($q) use ($executive_id) 
        {
        $q->where('executive_driver', $executive_id)
        ->orWhere('executive_garage', $executive_id)
        ->orWhere('executive_spot', $executive_id)
        ->orWhere('executive_meeting', $executive_id)
        ->orWhere('executive_accident_person', $executive_id);
        })
        ->whereBetween('created_at', [$startDate, $endDate]);

        $new = (clone $query)->where('status', 1)->count();
        $completed = (clone $query)->where('status', 2)->count();

        return response()->json([
        'executive_id' => $executive_id,
        'period' => $period,
        'start_date' => $startDate->toDateString(),
        'end_date' => $endDate->toDateString(),
        'new' => $new,
        'completed' => $completed,
        ]);

    }


    // public function storequestion(Request $request)
    // {

    // if (!Auth::check()) 
    // {
    // return response()->json(['error' => 'Unauthorized'], 401);
    // }

    // $data = $request->all();

    // if (empty($data['id']) || empty($data['works'])) 
    // {
    // return response()->json(['error' => 'Invalid data'], 422);
    // }

    // $submission = new QuestionnaireSubmission();
    // $submission->case_id   = $data['id'];
    // $submission->full_data = json_encode($data);
    // $submission->save();


    // DB::table('case_assignments')
    // ->where('id', $submission->case_id)
    // ->update(['status' => 2]);


    // $workToTableMap = [
    // 'driver'   => 'driver_data',
    // 'garage'   => 'garage_data',
    // 'meeting'  => 'owner_data',
    // 'spot'     => 'spot_data',
    // 'accident' => 'accident_person_data',
    // ];

    // foreach ($data['works'] as $workItem) 
    // {
    // if (empty($workItem['work']) || empty($workItem['questionnaire'])) 
    // {
    // continue;
    // }

    // $workType      = $workItem['work'];
    // $questionnaire = $workItem['questionnaire'];
    // $assignWorkId  = $data['assign_id'];
    // $executiveId   = $workItem['executive_id'] ?? null;
    // $table         = $workToTableMap[$workType] ?? ($workType . '_data');

    // if (!Schema::hasTable($table)) 
    // {
    // continue;
    // }

    // $baseInsertData = [
    // 'assign_work_id' => $assignWorkId,
    // 'executive_id'   => $executiveId,
    // 'created_at'     => now(),
    // 'updated_at'     => now(),
    // ];

    // $hasSingleFields = false;

    // foreach ($questionnaire as $field) 
    // {
    // if (!isset($field['name']) || !array_key_exists('data', $field)) 
    // {
    // continue;
    // }

    // $name  = $field['name'];
    // $value = $field['data'];

    // if (is_array($value)) 
    // {
    // foreach ($value as $item) 
    // {
    //     $fileRow = $baseInsertData;
    //     $fileRow[$name] = $item;

    //     DB::table($table)->insert($fileRow);

    //     $oldTable = $table . '_old';

    //     if (Schema::hasTable($oldTable)) 
    //     {
    //         DB::table($oldTable)->insert($fileRow);
    //     }
    // }
    // } 

    // else 
    // {
    // $baseInsertData[$name] = $value;
    // $hasSingleFields = true;
    // }
    // }


    // if ($hasSingleFields) 
    // {
    // DB::table($table)->insert($baseInsertData);

    // $oldTable = $table . '_old';
    // if (Schema::hasTable($oldTable)) 
    // {
    // DB::table($oldTable)->insert($baseInsertData);
    // }
    // }
    // }

    // return response()->json([
    // 'status' => 'success',
    // 'id'     => $submission->id,
    // ]);

    // }


    public function storequestion(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $data = $request->all();

    if (empty($data['id']) || empty($data['works'])) {
        return response()->json(['error' => 'Invalid data'], 422);
    }

    // Save the questionnaire submission
    $submission = new QuestionnaireSubmission();
    $submission->case_id   = $data['id'];
    $submission->full_data = json_encode($data);
    $submission->save();

    // Update related case status (remove duplicate)
    DB::table('case_assignments')
        ->where('id', $submission->case_id)
        ->update(['status' => 2]);

    // Table mapping
    $workToTableMap = [
        'driver'   => 'driver_data',
        'garage'   => 'garage_data',
        'meeting'  => 'owner_data',
        'spot'     => 'spot_data',
        'accident' => 'accident_person_data',
    ];


    $ownerDataUsed = false;
    $garageDataUsed = false;
    $driverDataUsed = false;
    $spotDataUsed = false;
    $accidentDataUsed = false;

    foreach ($data['works'] as $workItem) {
        if (empty($workItem['work']) || empty($workItem['questionnaire'])) {
            continue;
        }

        $workType      = $workItem['work'];
        $questionnaire = $workItem['questionnaire'];
        $assignWorkId  = $data['assign_id'];
        $executiveId   = $workItem['executive_id'] ?? null;
        $table         = $workToTableMap[$workType] ?? ($workType . '_data');

        if (!Schema::hasTable($table)) {
            continue;
        }


        if ($table === 'owner_data') {
            $ownerDataUsed = true;
        } elseif ($table === 'garage_data') {
            $garageDataUsed = true;
        }
        elseif ($table === 'driver_data') {
            $driverDataUsed = true;
        }
        elseif ($table === 'driver_data') {
            $driverDataUsed = true;
        }
        elseif ($table === 'spot_data') {
            $spotDataUsed = true;
        }

        elseif ($table === 'accident_person_data') {
            $accidentDataUsed = true;
        }


        $baseInsertData = [
            'assign_work_id' => $assignWorkId,
            'executive_id'   => $executiveId,
            'created_at'     => now(),
            'updated_at'     => now(),
        ];

        $hasSingleFields = false;

        foreach ($questionnaire as $field) {
            if (!isset($field['name']) || !array_key_exists('data', $field)) {
                continue;
            }

            $name  = $field['name'];
            $value = $field['data'];

            if (is_array($value)) {
                foreach ($value as $item) {
                    $fileRow = $baseInsertData;
                    $fileRow[$name] = $item;

                    DB::table($table)->insert($fileRow);

                    $oldTable = $table . '_old';
                    if (Schema::hasTable($oldTable)) {
                        DB::table($oldTable)->insert($fileRow);
                    }
                }
            } else {
                $baseInsertData[$name] = $value;
                $hasSingleFields = true;
            }
        }

        if ($hasSingleFields) {
            DB::table($table)->insert($baseInsertData);

            $oldTable = $table . '_old';
            if (Schema::hasTable($oldTable)) {
                DB::table($oldTable)->insert($baseInsertData);
            }
        }
    }


    if ($ownerDataUsed) {
        DB::table('assign_work_data')
            ->where('case_id', $submission->case_id)
            ->increment('owner_re_assign_count', 1, ['owner_reassign_status' => 1]);
    }

    if ($garageDataUsed) {
        DB::table('assign_work_data')
            ->where('case_id', $submission->case_id)
            ->increment('garage_re_assign_count', 1, ['garage_reassign_status' => 1]);
    }

    if ($driverDataUsed) {
        DB::table('assign_work_data')
            ->where('case_id', $submission->case_id)
            ->increment('driver_re_assign_count', 1, ['driver_reassign_status' => 1]);
    }

    if ($spotDataUsed) {
        DB::table('assign_work_data')
            ->where('case_id', $submission->case_id)
            ->increment('spot_re_assign_count', 1, ['spot_reassign_status' => 1]);
    }

    if ($accidentDataUsed) {
        DB::table('assign_work_data')
            ->where('case_id', $submission->case_id)
            ->increment('accident_person_reassign_count', 1, ['accident_person_reassign_status' => 1]);
    }

    return response()->json([
        'status' => 'success',
        'id'     => $submission->id,
    ]);
}



    public function getAssignedWorks(Request $request)
    {

        Validator::make($request->all(), [
            'assign_id'=>'required',
        ]);

        //  $assignWorkData= AssignWorkData::leftJoin('accident_person_data','assign_work_data.id','=','accident_person_data.assign_work_id')
        //                                 ->leftJoin('driver_data','driver_data.assign_work_id','=','assign_work_data.id')
        //                                 ->leftJoin('garage_data','garage_data.assign_work_id','=','assign_work_data.id')
        //                                 ->leftJoin('owner_data','owner_data.assign_work_id','=','assign_work_data.id')
        //                                 ->leftJoin('spot_data','spot_data.assign_work_id','=','assign_work_data.id')
        //                                 ->leftJoin('users as au','au.id','=','accident_person_data.executive_id')
        //                                 ->leftJoin('users as du','du.id','=','driver_data.executive_id')
        //                                 ->leftJoin('users as gu','gu.id','=','garage_data.executive_id')
        //                                 ->leftJoin('users as ou','ou.id','=','owner_data.executive_id')
        //                                 ->leftJoin('users as su','su.id','=','spot_data.executive_id')
        //                                 ->where('assign_work_data.case_id',$request->assign_id)
        //                                 ->select('accident_person_data.*','accident_person_data.ration_card as accident_ration_card',
        //                                         'driver_data.*','driver_data.ration_card as driver_ration_card','garage_data.*','owner_data.*',
        //                                         'owner_data.ration_card as owner_ration_card','spot_data.*','au.name as au_name','du.name as du_name',
        //                                         'gu.name as gu_name','ou.name as ou_name','su.name as su_name')
        //                                 ->distinct()
        //                                 ->get();


         $assignWorkData= AssignWorkData::leftJoin('accident_person_data','assign_work_data.id','=','accident_person_data.assign_work_id')
                                        ->leftJoin('driver_data','driver_data.assign_work_id','=','assign_work_data.id')
                                        ->leftJoin('garage_data','garage_data.assign_work_id','=','assign_work_data.id')
                                        ->leftJoin('owner_data','owner_data.assign_work_id','=','assign_work_data.id')
                                        ->leftJoin('spot_data','spot_data.assign_work_id','=','assign_work_data.id')
                                        ->leftJoin('users as au','au.id','=','accident_person_data.executive_id')
                                        ->leftJoin('users as du','du.id','=','driver_data.executive_id')
                                        ->leftJoin('users as gu','gu.id','=','garage_data.executive_id')
                                        ->leftJoin('users as ou','ou.id','=','owner_data.executive_id')
                                        ->leftJoin('users as su','su.id','=','spot_data.executive_id')
                                        ->where('assign_work_data.case_id',$request->assign_id)
                                        ->select('accident_person_data.*','driver_data.*','garage_data.*','owner_data.*','spot_data.*','au.name as au_name',
                                                 'du.name as du_name','gu.name as gu_name','ou.name as ou_name','su.name as su_name')
                                        ->distinct()
                                        ->get();

         return response()->json(['assignedWorkData'=>$assignWorkData],200);                               
    }
    
    public function specialCaseList()
    {
        $id = Auth::user()->id;

        $cases = DB::table('case_assignments')
                ->leftJoin('insurance_customers', 'case_assignments.customer_id', '=', 'insurance_customers.id')
                ->leftJoin('assign_work_data', 'case_assignments.id', '=', 'assign_work_data.case_id')
                ->leftJoin('insurance_companies', 'case_assignments.company_id', '=', 'insurance_companies.id')
                ->leftJoin('insurance_cases', 'insurance_customers.id', '=', 'insurance_cases.customer_id')
                ->leftJoin('users as driver', 'case_assignments.executive_driver', '=', 'driver.id')
                ->where(function ($query) use ($id) 
                {
                    $query->where('executive_driver', $id)
                        ->orWhere('executive_garage', $id)
                        ->orWhere('executive_spot', $id)
                        ->orWhere('executive_meeting', $id)
                        ->orWhere('executive_accident_person', $id);
                })

                ->leftJoin('accident_person_data as apd', function($join) {
                    $join->on('assign_work_data.id', '=', 'apd.assign_work_id')
                        ->where('apd.sp_case', '=', 1);
                })

                ->leftJoin('driver_data as dd', function($join) {
                    $join->on('assign_work_data.id', '=', 'dd.assign_work_id')
                        ->where('dd.sp_case', '=', 1);
                })

                ->leftJoin('garage_data as gd', function($join) {
                    $join->on('assign_work_data.id', '=', 'gd.assign_work_id')
                        ->where('gd.sp_case', '=', 1);
                })

                ->leftJoin('owner_data as od', function($join) {
                    $join->on('assign_work_data.id', '=', 'od.assign_work_id')
                        ->where('od.sp_case', '=', 1);
                })
                ->leftJoin('spot_data as sd', function($join) {
                    $join->on('assign_work_data.id', '=', 'sd.assign_work_id')
                        ->where('sd.sp_case', '=', 1);
                })
                
               
                ->select('case_assignments.id',
                    'case_assignments.other',
                    'insurance_cases.case_details',
                    'insurance_cases.insurance_type',
                    'insurance_customers.name as customer_name',
                    'insurance_customers.present_address as customer_place',
                    'insurance_customers.phone',
                    'insurance_companies.name as company_name',
                    'apd.id as apd_section_id',
                    'dd.id as dd_section_id',
                    'gd.id as gd_section_id',
                    'od.id as od_section_id',
                    'sd.id as sd_section_id',
                    'case_assignments.date as date',
                )
                ->get();
                $result=[];

                foreach($cases as $case){
                    if(!is_null($case->apd_section_id) || !is_null($case->dd_section_id) || !is_null($case->gd_section_id) || !is_null($case->od_section_id) 
                    ||  !is_null($case->sd_section_id))
                    {
                        $result[]=
                        [
                        'id'                => $case->id,
                        'other'             => $case->other,
                        'case_details'      => $case->case_details,
                        'insurance_type'    => $case->insurance_type,
                        'customer_name'     => $case->customer_name,
                        'customer_place'    => $case->customer_place,
                        'phone'             => $case->phone,
                        'company_name'      => $case->company_name,
                        'apd_section_id'    => $case->apd_section_id,
                        'dd_section_id'     => $case->dd_section_id,
                        'gd_section_id'     => $case->gd_section_id,
                        'od_section_id'     => $case->od_section_id,
                        'sd_section_id'     => $case->sd_section_id,
                        'work_id'           =>$case->apd_section_id .$case->dd_section_id .$case->gd_section_id . $case->od_section_id . $case->sd_section_id.$case->id,
                        'case_date'         => $case->date,
                        ];
                    }
                }
                

        if ($cases->isEmpty()) 
        {
            return response()->json(['message' => 'No case data found.'], 200);
        }
        else
        {
            return response()->json(['data' => $result], 200);
        }

    } 


    public function getQuestionnaire($id)
    {

        $company = InsuranceCompany::find($id);

        if (!$company) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Insurance company not found.'
            ], 404);
        }

        $questionnaireData = json_decode($company->questionnaires, true) ?? [];
        $groupedQuestionnaire = $questionnaireData;

        // $caseIds = InsuranceCase::where('company_id', $company->id)->pluck('id');

        return response()->json([
            'questionnaire' => $groupedQuestionnaire,
        ]);
    }


}
