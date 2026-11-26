<?php

namespace App\Http\Controllers;

use App\Models\Laureate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedController extends Controller
{
    public function index(Request $request): View
    {
        $friendIds = $request->user()->friends()->pluck('users.id');

        $laureates = Laureate::with('user')
            ->whereIn('user_id', $friendIds)
            ->orderByDesc('created_at')
            ->get();

        return view('feed.index', compact('laureates'));
    }
}
