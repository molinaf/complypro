<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Authorisation;

use App\Models\Module; // Adjust model as needed
use App\Models\F2F; // Adjust model as needed
use App\Models\Induction; // Adjust model as needed
use App\Models\License; // Adjust model as needed
use App\Models\Category; // Adjust model as needed

class LinkController extends BaseController
{
    protected $model = Link::class;
    
    public function modules()
    {
       // $data = $this->model::all();
        //return view('dynamic.link', ['items' => $data, 'model' => $this->getModelName()]);
        return 'Got here link_modules';
    }
    public function requisites()
    {
       // $data = $this->model::all();
        return view('requisite');
        //return 'Got here requisites';
    }
    public function schematic()
    {
       // $data = $this->model::all();
        return view('schematic.index');
        //return 'Got here requisites';
    }
    
    public function listAuthorisations()
    {
        // Fetch authorisations grouped by categories
        $authorisations = Authorisation::with('category')->get()->groupBy('category.name');

        // Pass to the view
        return view('dynamic.relate_table', [
            'authorisations' => $authorisations,
        ]);
    }

    public function showRelatedItems($authorisationId)
    {
        // Find the selected authorisation
        $authorisation = Authorisation::findOrFail($authorisationId);

        // Load related data from pivot tables
        $relatedItems = [
            'modules' => $authorisation->modules,
            'f2fs' => $authorisation->f2fs,
            'inductions' => $authorisation->inductions,
            'licenses' => $authorisation->licenses,
        ];
        return view('related_items', compact('relatedItems', 'authorisation'))->render();
        /*return view('dynamic.related_items',[
            'authorisation' => $authorisation,
            'relatedItems' => $relatedItems,
        ]);
        return view('dynamic.related-items', [
            'authorisation' => $authorisation,
            'relatedItems' => $relatedItems,
        ]);*/

    }
    
    public function showTable($relatedTable)
    {

        // Fetch Authorisations
        $authorisations = Authorisation::select('id', 'name', 'category_id')->get();
        
        // Dynamically determine the related model
        $modelClass = 'App\\Models\\' . ucfirst($relatedTable);
        
        // Check if the model exists, otherwise abort
        if (!class_exists($modelClass)) {
            abort(404, "Table not found");
        }

        // Fetch the related table
        $relatedTableData = $modelClass::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();
        
        // Pass data to the view
        return view('dynamic.table', [
            'authorisations' => $authorisations,
            'relatedTable' => $relatedTableData,
            'categories' => $categories,
            'relatedTableName' => $relatedTable
        ]);

    }
}