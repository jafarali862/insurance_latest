<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\PasswordResetRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', 1);
        $role=0;
        $search=strtolower($search);
        if($search=='executive'){
            $role=3;
        }elseif($search=='sub admin'){
            $role=2;
        }
        $users = User::when(!empty($search), function ($query) use ($search,$role) {
            $query->where(function ($q) use ($search,$role) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $role . '%');;
            });
        })
        ->where('status',$status)
        ->where('role', '!=', 1)->paginate(10);
        return view("dashboard.user.index")->with(["users" => $users,"status"=>$status]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("dashboard.user.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email",
            'password'=>"required",
            "phone" => "required|unique:users,phone",
            "place" => "required",
            "role" => "required",
        ]);

        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "place" => $request->place,
            "password" => Hash::make($request->password),
            "role" => $request->role,
            "status" => 1,
            "create_by" => Auth::user()->id,
            "update_by" => Auth::user()->id,
        ]);

        // Return JSON response

        
        // return response()->json(['success' => 'User added successfully']);

        return response()->json([
        'success' => 'User added successfully',
        'redirect_url' => route('user.list') // pass the redirect URL too
        ]);
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
        $user = User::where('id', $id)->first();
        return view("dashboard.user.edit")->with("user", $user);
    }

    public function userActiveChange(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id',
            'action' => 'required|string|in:activate,deactivate',
        ]);

        $user = User::findOrFail($request->id);
        $status = $request->action === 'activate' ? 1 : 0;
        $user->update(['status' =>$status]);

        return response()->json(['message' => 'User status updated successfully']);
    }


    public function approval(Request $request)
    {
        $users = User::where('login_request', 1)->get();
        return view('dashboard.user.approval')->with('users', $users);
    }

    public function approve($id)
    {
        $user = User::where('id', $id)->update(['login_request' => 0]);
        return response()->json(['message' => 'Approved Successfully']);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            "name" => "required",
            "phone" => "required",
            "email" => "required",
            "password"=>"nullable",
            "role" => "required",
            "status" => "required",
        ]);

        User::where('id', $request->id)->update([
            "name" => $request->name,
            "phone" => $request->phone,
            "email" => $request->email,
            "password"=>Hash::make($request->password),
            "role" => $request->role,
            "status" => $request->status,
        ]);

        // return response()->json(['success' => 'User update successfully']);

        return response()->json([
        'success' => 'User update successfully',
        'redirect_url' => route('user.list') // pass the redirect URL too
        ]);

    }


    public function passwordResetRequest(Request $request)
    {
        $requests = DB::table('password_reset_requests')
            ->leftJoin('users', 'users.id', '=', 'password_reset_requests.user_id')
            ->select('password_reset_requests.*', 'users.name as user_name')
            ->where('password_reset_requests.status', 1)
            ->orderBy('password_reset_requests.created_at', 'desc')
            ->paginate(10);

        return view('dashboard.password-reset.index')->with(['requests' => $requests]);
    }


    public function passwordResetEdit(Request $request, $id)
    {
        $requestData = DB::table('password_reset_requests')
            ->leftJoin('users', 'users.id', '=', 'password_reset_requests.user_id')
            ->select('password_reset_requests.*', 'users.name as user_name')
            ->where('password_reset_requests.id', $id)
            ->first();

        if (!$requestData) {
            return redirect()->route('dashboard.password-reset.index')->with('error', 'Request not found.');
        }

        return view('dashboard.password-reset.edit', ['request' => $requestData]);
    }

    public function passwordResetUpdate(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        PasswordResetRequest::where('id', $id)->update(['status' => 0]);

        foreach ($user->tokens as $token) {
            $token->revoke();
        }

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.'
        ]);
    }

    public function passwordResetReject(Request $request, $id)
    {
        PasswordResetRequest::where('id', $id)->update(['status' => 2]);

        return response()->json([
            'success' => true,
            'message' => 'Request reject success'
        ]);
    }

    public function allPasswordRestRequest()
    {
        $all_request = DB::table('password_reset_requests')
            ->leftJoin('users', 'users.id', '=', 'password_reset_requests.user_id')
            ->select('password_reset_requests.*', 'users.name as user_name')
            ->orderBy('password_reset_requests.created_at', 'desc')
            ->paginate(10);
        return view('dashboard.password-reset.all')->with(['data' => $all_request]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
