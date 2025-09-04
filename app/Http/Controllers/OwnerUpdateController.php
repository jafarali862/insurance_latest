<?php
namespace App\Http\Controllers;

use App\Models\OwnerData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OwnerUpdateController extends Controller
{
   
    
    public function updateOwnerDatanew(Request $request)
    {

    Log::info('Files Received:', $request->allFiles());

    $fieldValues = $request->input('field_value', []);
    $otherValues = $request->input('other_value', []);

    foreach ($request->allFiles()['field_value'] ?? [] as $ownerId => $fields) 
    {
        foreach ($fields as $column => $file) 
        {

            if (!isset($fieldValues[$ownerId][$column])) 
            {
                $fieldValues[$ownerId][$column] = null;
            }
        }
    }

    if (empty($fieldValues)) {
        Log::warning('No field values submitted.');
        return back()->withErrors(['error' => 'No data submitted.']);
    }

    foreach ($fieldValues as $ownerId => $fields) {
        $owner = OwnerData::find($ownerId);
        if (!$owner) {
            Log::warning("Owner with ID {$ownerId} not found.");
            continue;
        }

        foreach ($fields as $column => $value) {
            $uploadedFile = $request->file("field_value.$ownerId.$column");

            if ($uploadedFile instanceof \Illuminate\Http\UploadedFile) {
                Log::info("File uploaded for owner ID {$ownerId}, column {$column}, original name: {$uploadedFile->getClientOriginalName()}");
                $storedPath = $uploadedFile->store('owner_uploads', 'public');
                if ($storedPath) {
                    Log::info("File stored at: {$storedPath}");
                    $value = $storedPath;
                } else {
                    Log::error("Failed to store uploaded file for owner ID {$ownerId}, column {$column}");
                }
            } else {
                Log::info("No uploaded file for owner ID {$ownerId}, column {$column}. Value: {$value}");
            }

            if ($value === 'Other' && !empty($otherValues[$ownerId][$column])) {
                $value = $otherValues[$ownerId][$column];
                Log::info("Other value detected for owner ID {$ownerId}, column {$column}: {$value}");
            }

            $owner->$column = $value;
        }

        if ($owner->save()) {
            Log::info("Owner ID {$ownerId} data saved successfully.");
        } else {
            Log::error("Failed to save data for owner ID {$ownerId}");
        }
    }

    return back()->with('success', 'Owner data updated successfully.');
    }

    public function ownerSpecialCase($id)
    {
        $accidentData = OwnerData::findOrFail($id);
        $accidentData->sp_case = 1;
        $accidentData->save();
        return back()->with('success', 'Owner section data Reasigned successfully!');
    }

}
