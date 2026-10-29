<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('reviews')->latest();

        // Search by name or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by auth provider
        if ($provider = $request->input('provider')) {
            if ($provider === 'google') {
                $query->whereNotNull('google_id');
            } elseif ($provider === 'email') {
                $query->whereNull('google_id');
            }
        }

        $users = $query->paginate(15)->withQueryString();

        $totalUsers = User::count();
        $googleUsersCount = User::whereNotNull('google_id')->count();
        $emailUsersCount = User::whereNull('google_id')->count();

        return view('admin.users.index', compact(
            'users', 'totalUsers', 'googleUsersCount', 'emailUsersCount'
        ));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', "User '{$user->name}' was deleted successfully.");
    }
}
