<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Models\Photo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PhotoController extends Controller
{

    public function getPhotos($id)
    {
        $photos = Photo::where('user_id', '=', $id)->get();
        return response()->json(['photos' => $photos]);
    }

    public function store(PhotoRequest $request)
    {
        $data = $request->validated();
        $images = $data['images'];

        foreach ($images as $image) {
            $name = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('/images', $image, $name);
            $previewName = 'prev_' . $name;
            Photo::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'path' => $filePath,
                'url' => url('/storage/' . $filePath),
                'preview_url' => url('/storage/images/' . $previewName),
                'user_id' => Auth::user()->id,
            ]);

            Image::make($image)->fit(100, 100)->save(storage_path('app/public/images/' . $previewName));
        }

        return response()->json(['message' => 'success']);
    }

    public function updateTitle(Request $request)
    {
        $data = $request->all();
        Photo::where('id', '=', $data['id'])->update([
            'title' => $data['title'],
        ]);
    }

    public function updateDescription(Request $request)
    {
        $data = $request->all();
        Photo::where('id', '=', $data['id'])->update([
            'description' => $data['description'],
        ]);
    }

    public function updateImage(Request $request)
    {
        $data = $request->all();
        if ($data['image'] != 'undefined') {
            $name = md5(Carbon::now() . '_' . $data['image']->getClientOriginalName()) . '.' . $data['image']->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('/images', $data['image'], $name);
            $previewName = 'prev_' . $name;

            Photo::where('id', '=', $data['photo_id'])->update([
                'path' => $filePath,
                'url' => url('/storage/' . $filePath),
                'preview_url' => url('/storage/images/' . $previewName),
            ]);

            Image::make($data['image'])->fit(100, 100)->save(storage_path('app/public/images/' . $previewName));

            return response()->json(['message' => 'success']);
        }
        else {
            return null;
        }
    }
}
