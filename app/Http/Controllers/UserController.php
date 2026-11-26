<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::withCount('laureates')->orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    public function show(Request $request, User $user): View
    {
        $laureates = $user->laureates()->withTrashed()->orderBy('award_year')->get();
        $user->load('friends');
        $isFriend = $request->user()->friends()->whereKey($user->getKey())->exists();

        return view('users.show', compact('user', 'laureates', 'isFriend'));
    }
}
