<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends BaseController
{
    protected $model = Category::class;
    
    public function edit($id)
    {
        $item = $this->model::findOrFail($id); // Fetch the item by ID
        $model = strtolower(class_basename($this->model)); // Get model name dynamically
        return view('dynamic.edit', compact('item', 'model')); // Pass item and model to the view
    }


}