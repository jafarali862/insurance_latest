<?php

namespace App\Http\Controllers\API;

use App\Models\CompanyLogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoController extends Controller
{
    public function index()
    {
        try {
            $logos = CompanyLogo::first();
            return response()->json(['data'=>$logos]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching logos.'], 500);
        }
    }



    public function store(Request $request)
    {
    try 
    {        
    $validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:company_logos,email',
    'phone' => 'required|string|max:15',
    'place' => 'required|string|max:255',
    'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('logo')) 
    {
    $logoPath = $request->file('logo')->store('logos', 'public');
    }

    $logo = CompanyLogo::create([
    'name' => $validated['name'],
    'email' => $validated['email'],
    'phone' => $validated['phone'],
    'place' => $validated['place'],
    'logo' => $logoPath ?? null,
    ]);

    return response()->json($logo, 201);
    } 
    catch (\Exception $e) 
    {
    // Debugging
    return response()->json(['error' => $e->getMessage()], 500);
    }
    }

    
}
