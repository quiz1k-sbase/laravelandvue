<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoRequest;
use App\Models\Photo;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function store(VideoRequest $request)
    {
        $data = $request->all();
        $img = file_get_contents("https://www.googleapis.com/youtube/v3/videos?key=" . config('services.youtube.api_key') . "&part=snippet&id=" . $data['url']);
        $json = json_decode($img);
        Video::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'url' => $data['url'],
            'image_url' => $json->items[0]->snippet->thumbnails->default->url,
            'user_id' => Auth::user()->id,
        ]);

        return response()->json(['message' => 'success']);
    }

    public function getVideos($id)
    {
        $videos = Video::where('user_id', '=', $id)->get();
        return response()->json(['videos' => $videos]);
    }

    public function updateTitle(Request $request)
    {
        $data = $request->all();
        Video::where('id', '=', $data['id'])->update([
            'title' => $data['title'],
        ]);
    }

    public function updateDescription(Request $request)
    {
        $data = $request->all();
        Video::where('id', '=', $data['id'])->update([
            'description' => $data['description'],
        ]);
    }


}
