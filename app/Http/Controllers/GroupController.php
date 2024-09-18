<?php
// app/Http/Controllers/GroupController.php
namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; //←追記

class GroupController extends Controller
{
    public function index()
    {
        Gate::authorize('isAdmin');//←追記
        $userId = Auth::id();
        $groups = Group::where('admin_id', $userId)->with('user')->latest()->get();
        // $users = User::where('user_id', $userId)->latest()->get();
        return view('groups.index', compact('groups'));

    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        Gate::authorize('isAdmin');//←追記
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $adminId = Auth::id();

        Group::create([
            'admin_id' => $adminId,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        return redirect()->route('groups.index')->with('success', 'User added successfully');
    }
}
