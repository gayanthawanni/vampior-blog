<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;



class CommentController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'comment' => 'required'
    ]);

    Comment::create([
        'comment' => $request->comment,
        'user_id' => auth()->id(),
        'post_id' => $request->post_id,
    ]);

    return back();
}
}
