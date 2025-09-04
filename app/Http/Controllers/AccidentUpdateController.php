<?php

namespace App\Http\Controllers;

use App\Models\AccidentPersonData;
use Illuminate\Http\Request;

class AccidentUpdateController extends Controller
{
   

    public function updateAccidentDatanew(Request $request)
    {
        
        
    $selected = $request->input('selected_field');

        if (!$selected || !str_contains($selected, ':')) 
        {
            return back()->withErrors(['error' => 'Please select a accident record to update.']);
        }

        [$fieldName, $garageId] = explode(':', $selected);

        $fieldName = preg_replace('/[^a-zA-Z0-9_]/', '', $fieldName);

        $garage = AccidentPersonData::findOrFail($garageId);

        $inputKey = "field_value.$garageId.$fieldName";
        $otherKey = "other_value.$garageId.$fieldName";

        $finalValue = null;


        if ($request->hasFile($inputKey)) 
        {
            $file = $request->file($inputKey);
            $path = $file->store('spot_uploads', 'public');
            $finalValue = $path;
        }

        elseif ($request->filled($inputKey)) 
        {
            $value = $request->input($inputKey);


            if ($value === 'Other' && $request->filled($otherKey)) 
            {
                $finalValue = $request->input($otherKey);
            }

            else 
            {
                $finalValue = $value;
            }
        }
        else 
        {
            return back()->withErrors(['error' => 'No valid input found to update.']);
        }

        $garage->$fieldName = $finalValue;
        $garage->save();

        return back()->with('success', 'Accident Data Updated Successfully!');

    }

    public function accidentSpecialCase($id)
    {
        $accidentData = AccidentPersonData::findOrFail($id);
        $accidentData->sp_case = 1;
        $accidentData->save();
        return back()->with('success', 'Accident Person section data Reasigned successfully!');
    }
}
