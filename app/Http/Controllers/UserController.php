<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::withCount('laureates')->orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $laureates = $user->laureates()->withTrashed()->orderBy('award_year')->get();

        return view('users.show', compact('user', 'laureates'));
    }
}
