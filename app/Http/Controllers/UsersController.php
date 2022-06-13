<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    // See all users (admin only)
    public function admin_index()
    {
        $users = User::paginate(10);
        return view('users.index')->with('users',$users);
    }

    // Give/remove admin privilege (admin only)
    public function admin_toggle_admin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect(route('admin.users.index'))->with('error', 'You cannot lift your own admin privilege.');
        }

        if ($user->is_admin()) {
            $user->roles()->detach(1);
        } else {
            $user->roles()->attach(1);
        }

        return redirect(route('admin.users.index'))->with('success', 'Admin privilege changed successfully.');
    }

}
