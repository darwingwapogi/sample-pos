<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    private $model;

    public function create(Request $request, $model)
    {
        //TODO make a FormRequest. see model->validatorDefinitions for form rules.
        //add also policy
        $validatedData = $request->validate($model->validatorDefinitions());

        $this->model = new $model();
        $this->model->fill($validatedData);

        try {
            $this->model->save();
    
            return response()->json(['message' => 'Created successfully!', 'data' => $this->model], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating resource', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $model, $id)
    {
        try {
            $validatedData = $request->validate($model->validatorDefinitions());

            $this->model = $model::find($id);

            if (!$this->model) {
                throw new ModelNotFoundException("Resource not found with id {$id}");
            }

            $this->model->fill($validatedData);
            $this->model->save();

            return response()->json(['message' => 'Updated successfully!', 'data' => $this->model], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}