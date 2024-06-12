<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\truck\StoreRequest;
use App\Http\Requests\truck\UpdateRequest;
use App\Http\Resources\CatalogCollection;
use App\Http\Resources\TruckCollection;
use App\Models\AddTruck;
use App\Models\Image;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $trucks = Truck::query()
                ->join('images', function ($join) {
                    $join->on('trucks.id', '=', 'images.truck_id')
                        ->whereRaw('(images.id = (SELECT id FROM images WHERE images.truck_id = trucks.id ORDER BY id ASC LIMIT 1))');
                })
                ->select('trucks.*', 'images.image')
                ->get();
            $swiper = Truck::query()
                ->join('images', function ($join) {
                    $join->on('trucks.id', '=', 'images.truck_id')
                        ->whereRaw('(images.id = (SELECT id FROM images WHERE images.truck_id = trucks.id ORDER BY id ASC LIMIT 1))');
                })
                ->select('trucks.id', 'trucks.title', 'images.image')
                ->orderByDesc('trucks.created_at')
                ->take(5)
                ->get();
            return response()->json(new CatalogCollection($trucks, $swiper));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            $truck = $request->validated();
            $add = null;
            if (isset($truck['add'])) {
                $add = $truck['add'];
            }

            $images = $truck['image'];

            unset($truck['image'], $truck['add']);
            $trucks = Truck::create($truck);

            foreach ($images as $image) {

                $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $image);
                $data = base64_decode($base64Data);
                $tmpFile = tempnam(sys_get_temp_dir(), 'base64');
                file_put_contents($tmpFile, $data);

                $uploadedFile = new UploadedFile($tmpFile, 'image.png', null, null, true);
                $path = $uploadedFile->store('public');
                Image::create([
                    'truck_id' => $trucks->id,
                    'name' => $trucks->title,
                    'image' => $path
                ]);
                unlink($tmpFile);
            }

            if ($add !== null) {
                foreach ($add as $item) {
                    $item['truck_id'] = $trucks->id;
                    AddTruck::create($item);
                }
            }
            return response()->json(['success' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @param $id
     * Show the form for editing the specified resource.
     */
    public function show($id)
    {
        try {
            $model = new Truck();

            $truck = Truck::where('id', $id)->get();

            $swiper = Truck::query()
                ->join('images', 'trucks.id', '=', 'truck_id')
                ->select('trucks.id', 'images.image')
                ->where('trucks.id', '=', $id)
                ->get();

            $options = Truck::query()
                ->where('id', $id)
                ->select(
                    'trucks.year_release',
                    'trucks.wheel_formula',
                    'trucks.engine_power',
                    'trucks.transmission',
                    'trucks.fuel',
                    'trucks.weight',
                    'trucks.load_capacity',
                    'trucks.engine_model',
                    'trucks.wheels',
                    'trucks.guarantee',
                )->get();

            $specifications = Truck::query()
                ->join('add_trucks', 'trucks.id', '=', 'truck_id')
                ->select('add_trucks.*')
                ->where('trucks.id', '=', $id)
                ->get();

            $similarTrucks = Truck::query()
                ->join('images', function ($join) {
                    $join->on('trucks.id', '=', 'images.truck_id')
                        ->whereRaw('images.id = (SELECT id FROM images WHERE images.truck_id = trucks.id ORDER BY id ASC LIMIT 1)');
                })
                ->select('trucks.*', 'images.image')
                ->where('trucks.id', '!=', $truck[0]->id)
                ->where('engine_power', '=', $truck[0]->engine_power)
                ->where('transmission', '=', $truck[0]->transmission)
                ->where('weight', '=', $truck[0]->weight)
                ->where('load_capacity', '=', $truck[0]->load_capacity)
                ->take(3)
                ->get();

            $newOption = $model->setNewOption($options);

            return response()->json(new TruckCollection($truck, $newOption, $swiper, $specifications, $similarTrucks));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $truck = Truck::find($id);
            $newTruck = $request->validated();
            $isSimler = false;
            $images = Image::query()->where('truck_id', '=', $id)->get();

            $truck->update($newTruck);

            foreach ($images as $key => $image) {
                if (!isset($newTruck['image'][$key])) {
                    Storage::delete($image['image']);
                    $image->delete(); // Удаление записи из базы данных
                    unset($images[$key]); // Удаление элемента из массива $images
                    continue;
                }
                if (!Storage::exists(str_replace('http://localhost:8000/storage/', 'public/', $newTruck['image'][$key]))) {
                    Storage::delete($image['image']);
                    $isSimler = true;
                    $image->delete();
                }
            }

            foreach ($newTruck['image'] as $item) {
                if (!$isSimler) break;

                $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $item);
                $data = base64_decode($base64Data);
                $tmpFile = tempnam(sys_get_temp_dir(), 'base64');
                file_put_contents($tmpFile, $data);

                $uploadedFile = new UploadedFile($tmpFile, 'image.png', null, null, true);
                $path = $uploadedFile->store('public');

                Image::create([
                    'truck_id' => $id,
                    'name' => $truck->title,
                    'image' => $path
                ]);

                unlink($tmpFile);
            }


            $specifications = AddTruck::where('truck_id', $truck->id);

            $specifications->delete();

            if (isset($newTruck['add'])) {
                $addData = $newTruck['add'];

                foreach ($addData as $addItem) {
                    AddTruck::create([
                        'truck_id' => $truck->id,
                        ...$addItem
                    ]);
                }

                return response()->json(['success' => 'OK']);
            }

            return response()->json(['success' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }

    public function destroy($id)
    {
        try {
            $truck = Truck::find($id);
            $images = Image::where('truck_id', $id)->get();
            $specifications = AddTruck::where('truck_id', $id)->get();

            if (isset($images)) {
                foreach ($images as $image) {
                    if (Storage::exists($image->image)) {
                        Storage::delete($image->image);
                    }
                    $image->delete();
                }
            }

            if (isset($specifications)) {
                foreach ($specifications as $specification) {
                    $specification->delete();
                }
            }

            $truck->delete();

            return response()->json(['success' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
