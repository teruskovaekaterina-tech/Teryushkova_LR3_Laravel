<?php

namespace App\Http\Controllers;

use App\Models\Laureate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Laureate $laureate): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $laureate->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        return redirect()->route('laureates.show', $laureate)
            ->with('status', 'Комментарий добавлен.');
    }
}
