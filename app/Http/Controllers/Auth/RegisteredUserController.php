<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Laureate;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:32', 'regex:/^[a-zA-Z0-9_]+$/', 'unique:users,username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create($data);
            $number = User::count();
            $unassigned = Laureate::withTrashed()->whereNull('user_id')->orderBy('id');

            if ($number === 1) {
                $ids = $unassigned->pluck('id')->values()->filter(fn ($id, $index) => $index % 2 === 0);
                DB::table('laureates')->whereIn('id', $ids)->update(['user_id' => $user->id]);
            } elseif ($number === 2) {
                DB::table('laureates')->whereNull('user_id')->update(['user_id' => $user->id]);
            }

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('users.show', $user);
    }
}
