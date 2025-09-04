<?php

namespace App\Http\Controllers;

use App\Models\DriverData;
use Illuminate\Http\Request;

class DriverUpdateController extends Controller
{

    public function updateDriverDatanew(Request $request)
    {

        $selected = $request->input('selected_field');

        if (!$selected || !str_contains($selected, ':')) 
        {
            return back()->withErrors(['error' => 'Please select a driver record to update.']);
        }

        [$fieldName, $garageId] = explode(':', $selected);

        $fieldName = preg_replace('/[^a-zA-Z0-9_]/', '', $fieldName);

        $garage = DriverData::findOrFail($garageId);

        $inputKey = "field_value.$garageId.$fieldName";
        $otherKey = "other_value.$garageId.$fieldName";

        $finalValue = null;

        if ($request->hasFile($inputKey)) 
        {
            $file = $request->file($inputKey);
            $path = $file->store('driver_uploads', 'public');
            $finalValue = $path;
        }

        elseif ($request->filled($inputKey)) 
        {
            $value = $request->input($inputKey);

            // If "Other" is selected
            if ($value === 'Other' && $request->filled($otherKey)) {
                $finalValue = $request->input($otherKey);
            } else {
                $finalValue = $value;
            }
        } 
        else 
        {
            return back()->withErrors(['error' => 'No valid input found to update.']);
        }

        $garage->$fieldName = $finalValue;
        $garage->save();

        return back()->with('success', 'Driver Data Updated Successfully!');

    }


    public function driverSpecialCase($id)
    {
        $driverData = DriverData::findOrFail($id);
        $driverData->sp_case = 1;
        $driverData->save();
        return back()->with('success', 'Driver section data Reasigned successfully!');
    }
}
