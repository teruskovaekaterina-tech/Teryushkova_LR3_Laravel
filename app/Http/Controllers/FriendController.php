<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function store(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 403);

        $request->user()->addFriend($user);

        return redirect()->route('users.show', $user)
            ->with('status', 'Пользователь добавлен в друзья.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 403);

        $request->user()->removeFriend($user);

        return redirect()->route('users.show', $user)
            ->with('status', 'Пользователь удалён из друзей.');
    }
}
