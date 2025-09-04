<?php

namespace App\Http\Controllers;

use  PDF;
use Exception;
use App\Models\FinalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;


class FinalReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
       
        $search = $request->input('search', '');
        $from = $request->input('from_date');
        $to = $request->input('to_date');
        $reports = FinalReport::
         leftJoin('case_assignments', 'final_reports.case_id', '=', 'case_assignments.id')
        ->leftJoin('insurance_companies', 'case_assignments.company_id', '=', 'insurance_companies.id')
        ->leftJoin('insurance_customers', 'case_assignments.customer_id', '=', 'insurance_customers.id')
        ->leftJoin('users as driver', 'case_assignments.executive_driver', '=', 'driver.id')
        ->leftJoin('users as garage', 'case_assignments.executive_garage', '=', 'garage.id')
        ->leftJoin('users as spot', 'case_assignments.executive_spot', '=', 'spot.id')
        ->leftJoin('users as owner', 'case_assignments.executive_meeting', '=', 'owner.id')
        ->leftJoin('users as accident', 'case_assignments.executive_accident_person', '=', 'accident.id')
        ->when(!empty($search), function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('insurance_companies.name', 'like', '%' . $search . '%')
                    ->orWhere('insurance_customers.name', 'like', '%' . $search . '%')
                    ->orWhere('case_assignments.date', 'like', '%' . $search . '%')
                    ->orWhere('case_assignments.type', 'like', '%' . $search . '%');
            });
        })
            ->when($from && $to, function ($query) use ($from, $to) {
        try 
        {
            $fromFormatted = Carbon::parse($from)->startOfDay();
            $toFormatted = Carbon::parse($to)->endOfDay();
            $query->whereBetween('final_reports.updated_at', [$fromFormatted, $toFormatted]);
        } 

        catch (\Exception $e) 
        {
            // Handle invalid date format
        }
        })

        ->select(
            'insurance_companies.name as company_name',
            'insurance_customers.name as customer_name',
            'case_assignments.date as date',
            'case_assignments.type as type',
            'final_reports.id as report_id'
        )
        // ->orderBy('case_assignments.date', 'desc') 
        ->orderBy('case_assignments.id', 'desc') 
        ->paginate(10);

        return view('dashboard.final-report.index', ['reports' => $reports, 'from' => $from,
        'to' => $to]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createFinalPdfDownload(Request $request)
    {
        try {


            $reportId = $request->report_id;

            $finalReport = FinalReport::leftJoin('case_assignments', 'final_reports.case_id', '=', 'case_assignments.id')
                            ->leftJoin('insurance_companies', 'case_assignments.company_id', '=', 'insurance_companies.id')
                            ->leftJoin('insurance_customers', 'case_assignments.customer_id', '=', 'insurance_customers.id')
                            ->leftJoin('insurance_cases', 'insurance_cases.customer_id', '=', 'insurance_customers.id')
                            ->leftJoin('users as driver', 'case_assignments.executive_driver', '=', 'driver.id')
                            ->leftJoin('users as garage', 'case_assignments.executive_garage', '=', 'garage.id')
                            ->leftJoin('users as spot', 'case_assignments.executive_spot', '=', 'spot.id')
                            ->leftJoin('users as owner', 'case_assignments.executive_meeting', '=', 'owner.id')
                            ->leftJoin('users as accident', 'case_assignments.executive_accident_person', '=', 'accident.id')

                            ->where('final_reports.id', $reportId)
                            ->select('final_reports.*',
                            'insurance_companies.name as insurance_com_name',
                            'insurance_companies.contact_person as insurance_com_contact_person',
                            'insurance_companies.email as insurance_com_email',
                            'insurance_companies.phone as insurance_com_phone',
                            'insurance_companies.address as insurance_com_address',
                            'insurance_companies.template',
                            'insurance_customers.name as customer_name',
                            'insurance_customers.father_name as customer_father_name',
                            'insurance_customers.phone as customer_phone',
                            'insurance_customers.emergency_contact_number as customer_emergancy_contact_no',
                            'insurance_customers.email as customer_email',
                            'insurance_customers.present_address as customer_present_address',
                            'insurance_customers.permanent_address as customer_premanent_address',
                            'insurance_customers.policy_no as customer_policy_no',
                            'insurance_customers.policy_start as customer_policy_start',
                            'insurance_customers.policy_end as customer_policy_end',
                            'insurance_customers.insurance_type as customer_insurance_type',
                            'insurance_customers.crime_number',
                            'insurance_customers.police_station',
                            'case_assignments.date as case_assign_date', 
                            'driver.name as driver_executive',
                            'garage.name as garage_executive',
                            'spot.name as spot_executive',
                            'owner.name as owner_executive',
                            'accident.name as accident_executive',
                            'insurance_cases.created_at as date_of_allotement',
                            'final_reports.created_at as date_of_submitted')
                            ->first();


                $questions      = DB::table('questions')->where('data_category','garage_data')->get();

                $questions_2    = DB::table('questions')->where('data_category','spot_data')->get();
                $questions_3    = DB::table('questions')->where('data_category','driver_data')->get();
                $questions_4    = DB::table('questions')->where('data_category','owner_data')->get();
                $questions_5    = DB::table('questions')->where('data_category','accident_person_data')->get();

    // Filter questions whose column_name exists in final_reports table
            $validQuestions = $questions->filter(function ($question) 
            {
            return Schema::hasColumn('final_reports', $question->column_name);
            });

            $validQuestions_2 = $questions_2->filter(function ($question2) 
            {
            return Schema::hasColumn('final_reports', $question2->column_name);
            });

             $validQuestions_3 = $questions_3->filter(function ($question3) 
            {
            return Schema::hasColumn('final_reports', $question3->column_name);
            });


            $validQuestions_4 = $questions_4->filter(function ($question4) 
            {
            return Schema::hasColumn('final_reports', $question4->column_name);
            });
           
            $validQuestions_5 = $questions_5->filter(function ($question5) 
            {
            return Schema::hasColumn('final_reports', $question5->column_name);
            });



            $templateNo = $finalReport->template;

            switch ($templateNo) 
            {
                case 1:
                    $pdf = PDF::loadView('dashboard.pdf.pdf1', compact('finalReport','validQuestions','validQuestions_2','validQuestions_3','validQuestions_4','validQuestions_5'));
                    break;
                case 2:
                    $pdf = PDF::loadView('dashboard.pdf.pdf2', compact('finalReport','validQuestions','validQuestions_2','validQuestions_3','validQuestions_4','validQuestions_5'));
                    break;
                case 3:
                    $pdf = PDF::loadView('dashboard.pdf.pdf3', compact('finalReport','validQuestions','validQuestions_2','validQuestions_3','validQuestions_4','validQuestions_5'));
                    break;
                case 4:
                    $pdf = PDF::loadView('dashboard.pdf.pdf4', compact('finalReport','validQuestions','validQuestions_2','validQuestions_3','validQuestions_4','validQuestions_5'));
                    break;
                case 5:
                    $pdf = PDF::loadView('dashboard.pdf.pdf5', compact('finalReport','validQuestions','validQuestions_2','validQuestions_3','validQuestions_4','validQuestions_5'));
                    break;
                case 6:
                    $pdf = PDF::loadView('dashboard.pdf.pdf6', compact('finalReport','validQuestions','validQuestions_2','validQuestions_3','validQuestions_4','validQuestions_5'));
                    break;
                case 7:
                    $pdf = PDF::loadView('dashboard.pdf.pdf7', compact('finalReport','validQuestions','validQuestions_2','validQuestions_3','validQuestions_4','validQuestions_5'));
                    break;
                case 8:
                    $pdf = PDF::loadView('dashboard.pdf.pdf8', compact('finalReport','validQuestions','validQuestions_2','validQuestions_3','validQuestions_4','validQuestions_5'));
                    break;
                default:
                    // return response()->json(['error' => 'Invalid template number'], 400);
                    $pdf = PDF::loadView('dashboard.pdf.pdf', compact('finalReport','validQuestions','validQuestions_2','validQuestions_3','validQuestions_4','validQuestions_5'));
            }


            return response()->stream(function () use ($pdf) 
            {
                echo $pdf->output();
            }, 200, [
                "Content-Type" => "application/pdf",
                "Content-Disposition" => "attachment; filename=insurance.pdf",
            ]);
        } 

        catch (Exception $e) 
        {
            Log::error('Error finding in pdf: ' . $e->getMessage());
        }
    }

    // public function preview($reportId)
    // {
    //     $filePath = storage_path("app/reports/report_{$reportId}.pdf");

    //     if (!file_exists($filePath)) {
    //         abort(404, 'Report not found');
    //     }

    //     return response()->file($filePath, [
    //         'Content-Type' => 'application/pdf',
    //         'Content-Disposition' => 'inline; filename="report_'.$reportId.'.pdf"',
    //     ]);
    // }


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
