<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::join('users', 'user_id', '=', 'users.id')->get(['comments.*', 'users.username'])->toArray();

        return array_reverse($comments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required',
            'comment' => 'required|min:1'
        ]);
        $data = $request->all();

        $comm = Comment::create([
            'user_id' => Auth::user()->id,
            'post_id' => strip_tags($data['post_id']),
            'comment' => strip_tags($data['comment'])
        ]);

        if ($comm) {
            return response()->json(['message' => 'Comment created successfully', 'data' => $this->getComments()]);
        } else {
            return response()->json(['message' => 'Post was not created']);
        }
    }

    public function update($id, Request $request)
    {
        $comm = Comment::find($id);
        $comm->update($request->all());

        return response()->json('The post successfully updated');
    }

    public function delete($id)
    {
        $comm = Comment::find($id);
        $comm->delete();

        return response()->json('The comment successfully deleted');
    }

    public function storeReply(Request $request)
    {
        $request->validate([
            'post_id' => 'required',
            'comment' => 'required|min:1',
            'parent_id' => 'required'
        ]);
        $data = $request->all();

        $comm = Comment::create([
            'user_id' => Auth::user()->id,
            'post_id' => strip_tags($data['post_id']),
            'comment' => strip_tags($data['comment']),
            'parent_id' => strip_tags($data['parent_id'])
        ]);

        if ($comm) {
            return response()->json(['message' => 'Comment created successfully', 'data' => Comment::find($data['parent_id'])->get()]);
        } else {
            return response()->json(['message' => 'Post was not created']);
        }
    }

    public function getComments() {
        $comm = Post::with("comment")->join('users', 'user_id', '=', 'users.id')->get(['posts.*', 'users.username']);
        return array_reverse($comm->toArray());
    }

    public function getUsername($id) {
        return User::find($id)->username;
    }
}
