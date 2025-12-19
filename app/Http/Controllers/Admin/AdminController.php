<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class AdminController extends Controller
{
     public function index()
    {
        $users = User::with('roles')->paginate(10);
        $posts = Post::latest()->paginate(10);
        $roles = Role::all();

        return view('admin.dashboard', compact('users', 'posts', 'roles'));
        
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
           'role' => 'required|in:admin,editor,reader',
        ]);

        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role updated successfully');
    }

     public function approvePost(Post $post)
    {
        $post->update(['status' => 'approved']);
        return back();
    }

     public function rejectPost(Post $post)
    {
        $post->update(['status' => 'rejected']);
        return back();
    }

    public function deletePost(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Post deleted');
    }
}
