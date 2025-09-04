<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\InsuranceCompany;
use App\Models\User;
use App\Models\InsuranceCase;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\CaseAssignment;
use App\Models\AssignWorkData;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view("Auth.login");
    }

    public function registerForm()
    {
        return view("Auth.register");
    }

    public function register(Request $request)
    {
        $request->validate([
            "name" => "required",
            "phone" => "required|max:10",
            "email" => "required|email|unique:users,email",
            "password" => [
                "required",
                "min:8",
                "regex:/[A-Z]/",
                "regex:/[a-z]/",
                "regex:/[0-9]/",
                "regex:/[@$!%*?&#^()_+=-]/",
            ],
            "password_confirmation" => "required|same:password",
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email, 
            'password' => Hash::make($request->password),
            'role' => 1,
            'status' => 1,
        ]);

        return redirect()->route('login.form');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required ',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('login.form')->with(['error' => 'Invalid username or password. Try again.']);
    }

    public function dashboard()
    {
        $totalEmployee = User::where('role', '!=', 1)->count();
        $totalCompany = InsuranceCompany::count();
        $totalCase = InsuranceCase::count();
        $completeCase = InsuranceCase::where('status', 0)->count();
        $pendingCase = InsuranceCase::where('status', 1)->count(); 
        $assignedCase = CaseAssignment::count(); 
        $fakeCase = CaseAssignment::where('is_fake', 1)->count();
        $totalSubmittedCase = AssignWorkData::count();
        $todaySubmittedCase = AssignWorkData::whereDate('created_at', now()->toDateString())->count();
        $currentYear = Carbon::now()->year;

        $totalcaseCount = DB::table('insurance_customers')
        ->leftJoin('insurance_cases', 'insurance_customers.id', '=', 'insurance_cases.customer_id')
        ->leftJoin('insurance_companies', 'insurance_companies.id', '=', 'insurance_customers.company_id')
        ->count(); // get total count


        $data = InsuranceCase::selectRaw('MONTH(created_at) as month, COUNT(*) as case_count')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $casesCount = array_fill(0, 12, 0);

        foreach ($data as $item) {
            $casesCount[$item->month - 1] = $item->case_count;
        }

        return view('dashboard.index')->with([
            'totalEmployee' => $totalEmployee,
            'totalCompany' => $totalCompany,
            'totalCase' => $totalCase,
            'completeCase' => $completeCase,
            'pendingCase' => $pendingCase,
            'casesCount' => $casesCount,
            'currentYear' => $currentYear,
            'assignedCase' => $assignedCase,
            'fakeCase'=>$fakeCase,
            'totalSubmittedCase' => $totalSubmittedCase, 
            'todaySubmittedCase' => $todaySubmittedCase,
            'totalcaseCount'=>$totalcaseCount,
            'darkMode' => true
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('login')->with(['success' => 'Successfully logged out!']);
    }
}
