<?php

namespace App\Http\Controllers;

use App\Models\CompanyLogo;
use Illuminate\Http\Request;

class LogoManagenentController extends Controller
{

    public function index()
    {
        $logos = CompanyLogo::paginate(10);
        return view('dashboard.logo.index', compact('logos'));
    }

    public function getLogo()
    {
        return view('dashboard.logo.create');
    }

    public function storeLogo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_logos,email',
            'phone' => 'required|string|max:15',
            'place' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            
        ]);

        
        if ($request->hasFile('logo')) {
           
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        CompanyLogo::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'place' => $request->place,
            'logo' => $logoPath,
            
        ]);

        return redirect()->route('logos')->with('success', 'Company logo added successfully!');
    }

    public function editLogo($id)
    {
        
        $logo = CompanyLogo::findOrFail($id);
        return view('dashboard.logo.edit', compact('logo'));
    }

    public function updateLogo(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_logos,email,' . $id,  
            'phone' => 'required|string|max:15',
            'place' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            
        ]);

        $logo = CompanyLogo::findOrFail($id);

        
        $logo->name = $request->name;
        $logo->email = $request->email;
        $logo->phone = $request->phone;
        $logo->place = $request->place;
        

        
        if ($request->hasFile('logo')) {
            
            if (file_exists(public_path('storage/' . $logo->logo))) {
                unlink(public_path('storage/' . $logo->logo));
            }

            $logoPath = $request->file('logo')->store('logos', 'public');
            $logo->logo = $logoPath;
        }

        $logo->save();

        session()->flash('success', 'Company logo updated successfully!');
        return redirect()->route('logos');
    }
}
