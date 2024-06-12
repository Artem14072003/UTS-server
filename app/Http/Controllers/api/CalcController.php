<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\calculator\UpdateRequest;
use App\Http\Resources\CalcResource;
use App\Models\Calc;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CalcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $calc = Calc::all();
            return response()->json(new CalcResource($calc));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        try {
            $calc = Calc::find(1);
            $newCalc = $request->validated();

            $calc->update($newCalc);
            return response()->json(['success' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}
