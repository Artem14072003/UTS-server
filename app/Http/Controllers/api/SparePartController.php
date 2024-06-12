<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\spare\CreateRequest;
use App\Http\Requests\spare\UpdateRequest;
use App\Http\Resources\SparePartCollection;
use App\Http\Resources\SparePartsCollection;
use App\Models\AddSparePart;
use App\Models\AddTruck;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SparePartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $spare_parts = SparePart::all();
            return response()->json(new SparePartsCollection($spare_parts));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        try {
            $spare_parts = $request->validated();

            $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $spare_parts['image']);

            $data = base64_decode($base64Data);

            $tmpFile = tempnam(sys_get_temp_dir(), 'base64');
            file_put_contents($tmpFile, $data);

            $uploadedFile = new UploadedFile($tmpFile, 'image.png', null, null, true);

            $path = $uploadedFile->store('public/spare-parts');
            $spare_parts['image'] = $path;
            unlink($tmpFile);

            $spare_part = SparePart::create($spare_parts);
            if (isset($spare_parts['add'])) {
                foreach ($spare_parts['add'] as $item) {
                    $item['truck_id'] = $spare_part->id;
                    AddSparePart::create($item);
                }
            }
            return response()->json(['success' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @param $id
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $spare_part = SparePart::where('spare_parts.id', '=', $id)->get();
            $specifications = SparePart::query()
                ->join('add_spare_parts', 'spare_parts.id', '=', 'truck_id')
                ->select('spare_parts.*', 'add_spare_parts.title', 'add_spare_parts.value')
                ->where('spare_parts.id', '=', $id)
                ->get();

            return response()->json(new SparePartCollection($spare_part, $specifications));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(UpdateRequest $request, $id)
    {
        try {

            $spare_part = SparePart::where('id', $id)->firstOrFail();
            $newSparePart = $request->validated();

            if ($spare_part->image !== str_replace('http://localhost:8000/storage/', 'public/', $newSparePart['image'])) {
                if (Storage::exists($spare_part->image))
                    Storage::delete($spare_part->image);

                $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $newSparePart['image']);

                $data = base64_decode($base64Data);

                $tmpFile = tempnam(sys_get_temp_dir(), 'base64');
                file_put_contents($tmpFile, $data);

                $uploadedFile = new UploadedFile($tmpFile, 'image.png', null, null, true);

                $path = $uploadedFile->store('public/spare-parts');

                $spare_part->image = $path;

                unlink($tmpFile);

            }

            $addSparePart = AddSparePart::where('truck_id', $id);

            $addSparePart->delete();

            if ($newSparePart['add']) {
                $add = $newSparePart['add'];
                foreach ($add as $item) {
                    AddSparePart::create(['truck_id' => $id, ...$item]);
                }
            }

            unset($newSparePart['add']);
            $spare_part->title = $newSparePart['title'];
            $spare_part->description = $newSparePart['description'];
            $spare_part->price = $newSparePart['price'];
            $spare_part->model = $newSparePart['model'];
            $spare_part->save();
            return response()->json(['success' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $sparePart = SparePart::find($id);
            $specifications = AddSparePart::where('truck_id', $id)->get();

            if (isset($specifications)) {
                foreach ($specifications as $specification) {
                    $specification->delete();
                }
            }

            $sparePart->delete();
            return response()->json(['success' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }
}
