<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::with("comment")->join('users', 'user_id', '=', 'users.id')->get(['posts.*', 'users.username']);
        return array_reverse($posts->toArray());
    }

    public function store(Request $request)
    {
        $request->validate([
            'text_en' => 'required|min:1',
        ]);
        $data = $request->all();

        $post = Post::create([
            'user_id' => Auth::user()->id,
            'text_en' => strip_tags($data['text_en']),
        ]);

        /*if ($data['locale'] === 'en') {
            $post = Post::create([
                'user_id' => strip_tags($data['user_id']),
                'text_en' => strip_tags($data['text']),
                'image' => $this->storeImage($request) ?? null,
            ]);
        }
        elseif ($data['locale'] === 'ru') {
            $post = Post::create([
                'user_id' => strip_tags($data['user_id']),
                'text_ru' => strip_tags($data['text']),
                'image' => $this->storeImage($request) ?? null,
            ]);
        }
        elseif ($data['locale'] === 'uk') {
            $post = Post::create([
                'user_id' => strip_tags($data['user_id']),
                'text_uk' => strip_tags($data['text']),
                'image' => $this->storeImage($request) ?? null,
            ]);
        }*/

        if ($post) {
            return response()->json(['message' => 'Post created successfully', 'data' => $this->index()]);
        } else {
            return response()->json(['message' => 'Post was not created']);
        }
    }

    public function update($id, Request $request)
    {
        $post = Post::find($id);
        $post->update($request->all());

        return response()->json('The post successfully updated');
    }

    public function delete($id)
    {
        $post = Post::find($id);
        $post->delete();

        return response()->json('The post successfully deleted');
    }
}
