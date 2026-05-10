<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('orders')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with('orders')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:0,1']);
        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);
        return redirect()->back()->with('success', 'Cập nhật quyền thành công');
    }
}
