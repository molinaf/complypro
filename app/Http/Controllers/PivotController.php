<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PivotController extends Controller
{
    public function store(Request $request)
    {
        // Retrieve selected authorisation IDs and related table details
        $authorisationIds = $request->input('authorisation_ids', []);
        $relatedItemId = $request->input('related_item_id');
        $relatedTable = $request->input('related_table'); // Added for table selection

        // Check if related item and table are provided
        if (!$relatedItemId || !$relatedTable) {
            return redirect()->back()->with('error', 'Please select a related item and table.');
        }

        // Map related table to corresponding pivot table and foreign key
        $pivotTables = [
            'modules' => ['table' => 'module_authorisation', 'foreignKey' => 'module_id'],
            'f2fs' => ['table' => 'f2f_authorisation', 'foreignKey' => 'f2f_id'],
            'inductions' => ['table' => 'induction_authorisation', 'foreignKey' => 'induction_id'],
            'licenses' => ['table' => 'license_authorisation', 'foreignKey' => 'license_id'],
        ];
        //dd($authorisationIds,$relatedItemId, $relatedTable,$pivotTables);
        $relatedTable = Str::plural($relatedTable);
        if (!array_key_exists($relatedTable, $pivotTables)) {
            return redirect()->back()->with('error', 'Invalid related table selected.');
        }

        //dd( $relatedTable, $pivotTables);
        $pivotTable = $pivotTables[$relatedTable]['table'];
        $foreignKey = $pivotTables[$relatedTable]['foreignKey'];

        // Insert into the correct pivot table
        foreach ($authorisationIds as $authorisationId) {
            // Avoid duplicate entries
            $exists = DB::table($pivotTable)
                ->where($foreignKey, $relatedItemId)
                ->where('authorisation_id', $authorisationId)
                ->exists();

            if (!$exists) {
                DB::table($pivotTable)->insert([
                    $foreignKey => $relatedItemId,
                    'authorisation_id' => $authorisationId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pivot table entries created successfully!');
    }
}
