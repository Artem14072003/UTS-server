<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\mail\CreateRequest;
use App\Http\Resources\HomeCollaction;
use App\Models\Admin;
use App\Models\Truck;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $cards = Truck::query()
                ->join('images', function ($join) {
                    $join->on('trucks.id', '=', 'images.truck_id')
                        ->whereRaw('images.id = (SELECT MIN(id) FROM images WHERE images.truck_id = trucks.id)');
                })
                ->select('trucks.*', 'images.image')
                ->orderByDesc("trucks.created_at")
                ->take(3)
                ->get();
            return response()->json(new HomeCollaction($cards), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function mailto(CreateRequest $massages)
    {
        try {
            $data = $massages->validated();
            $data['services'] = Admin::newServices($data['services']);
            if ($data['services'] !== 'error') {
                Mail::send('email.message', $data, function ($message) use ($data) {
                    $message->to('crivenko.artemy@ya.ru')
                        ->subject("Пользователь: " .
                            $data['fullname'] . ', ' .
                            'хочет воспользоваться нашими услугами!');
                });

                return response()->json(['success' => 'OK']);
            }
            return response()->json(['error' => 'Такой услуги не существует!'], 405);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
