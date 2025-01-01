<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $model;

    public function index()
    {
        $data = $this->model::all();
        return view('dynamic.index', ['items' => $data, 'model' => $this->getModelName()]);
    }

    public function create()
    {
        return view('dynamic.create', ['model' => $this->getModelName()]);
    }

    public function store(Request $request)
    {
        $this->model::create($request->all());
        return redirect()->route('dynamic.index', ['model' => $this->getModelName()]);
    }

    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        return view('dynamic.edit', ['item' => $item, 'model' => $this->getModelName()]);
    }

    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('dynamic.index', ['model' => $this->getModelName()]);
    }

    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        $item->delete();
        return redirect()->route('dynamic.index', ['model' => $this->getModelName()]);
    }

    private function getModelName()
    {
        return strtolower(class_basename($this->model));
    }
}
