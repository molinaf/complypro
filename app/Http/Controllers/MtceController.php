<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class MtceController extends Controller
{
    protected $model;

    public function __construct(Request $request)
    {
        // Dynamically resolve the model from the route
        $this->model = $this->resolveModel($request->route('model'));
    }

    // Resolve the model dynamically
    protected function resolveModel($model)
    {
        if ($model===null) {
            return;
        }
        $models = [
            'authorisations' => \App\Models\Authorisation::class,
            'categories' => \App\Models\Category::class,
            'modules' => \App\Models\Module::class,
            'f2fs' => \App\Models\F2f::class,
            'inductions' => \App\Models\Induction::class,
            'licenses' => \App\Models\License::class,
        ];

        if (!array_key_exists($model, $models)) {
            abort(406, "Model not found");
        }

        return $models[$model];
    }

    public function index()
    {
        $items = $this->model::all();
        $model = strtolower(class_basename($this->model));
        return view('dynamic.index', compact('items', 'model'));
    }

    public function create()
    {
        $model = strtolower(class_basename($this->model));
        return view('dynamic.create', compact('model'));
    }

    public function store(Request $request)
    {
        $model = strtolower(class_basename($this->model));
        $this->model::create($request->all());
        return redirect()->route('dynamic.index', ['model' =>  Str::plural($model)]);
    }

    public function edit($model, $id)
    {
        $item = $this->model::findOrFail($id);
        $model = strtolower(class_basename($this->model));
        return view('dynamic.edit', compact('item', 'model'));
    }

    public function update(Request $request, $name, $id)
    {
        $item = $this->model::findOrFail($id);
        $item->update($request->all());
        $model = strtolower(class_basename($this->model));
        $pluralModel = Str::plural($model);
        //dd($model,$pluralModel);
        return redirect()->route('dynamic.index', ['model' => $pluralModel]);
    }

    public function destroy($tableName, $id)
    {
        if (app()->runningInConsole()) {
            return;
        }
        $item = $this->model::findOrFail($id);
        //dd($item,$id);
        $item->delete();
        $model = strtolower(class_basename($this->model));
        $pluralModel = Str::plural($model);
        return redirect()->route('dynamic.index', ['model' => $pluralModel]);
    }
}
