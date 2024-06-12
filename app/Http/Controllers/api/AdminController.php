<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminCollection;
use App\Http\Requests\admin\UpdateRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        try {
            $admin = Admin::where('id', auth()->user()->id)->get();
            return response()->json(new AdminCollection($admin), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(UpdateRequest $request)
    {
        try {
            $admin = Admin::find(auth()->user()->id);
            $newAdmin = $request->validated();
            if (!Hash::check($newAdmin['oldPassword'], $admin['password']))
                return response()->json(['error' => 'Не верный пароль'], 405);

            unset($newAdmin['oldPassword']);
            if ($admin['image'] && Storage::exists($admin['image'])) {
                Storage::delete($admin['image']);
                $admin['image'] = null;
            }

            if (isset($newAdmin['image'])) {

                $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $newAdmin['image']);

                $data = base64_decode($base64Data);

                $tmpFile = tempnam(sys_get_temp_dir(), 'base64');
                file_put_contents($tmpFile, $data);

                $uploadedFile = new UploadedFile($tmpFile, 'image.png', null, null, true);

                $path = $uploadedFile->store('public/admin');
                $admin['image'] = $path;
                unlink($tmpFile);
            }

            $admin['login'] = $newAdmin['login'];
            $admin['password'] = $newAdmin['password'];
            $admin->save();

            return response()->json(['success' => "OK"]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['success' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
