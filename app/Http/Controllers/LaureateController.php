<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laureate;

class LaureateController extends Controller
{
    public function index()
    {
        $laureates = Laureate::orderBy('award_year')->get();

        return view('laureates.index', compact('laureates'));
    }

    public function create()
    {
        return view('laureates.form', [
            'laureate' => new Laureate()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'citizenship' => 'required|string|max:255',
            'award_year' => 'required|integer|between:1901,2100',
            'works_text' => 'required|string|max:5000'
        ]);

        $works = preg_split('/\R/u', $data['works_text']);

        $data['works'] = array_values(array_filter(
            array_map('trim', $works),
            fn($work) => $work !== ''
        ));

        unset($data['works_text']);

        Laureate::create($data);

        return redirect()->route('laureates.index');
    }

    public function show(Laureate $laureate)
    {
        return view('laureates.show', compact('laureate'));
    }

    public function edit(Laureate $laureate)
    {
        return view('laureates.form', compact('laureate'));
    }

    public function update(Request $request, Laureate $laureate)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'citizenship' => 'required|string|max:255',
            'award_year' => 'required|integer|between:1901,2100',
            'works_text' => 'required|string|max:5000'
        ]);

        $works = preg_split('/\R/u', $data['works_text']);

        $data['works'] = array_values(array_filter(
            array_map('trim', $works),
            fn($work) => $work !== ''
        ));

        unset($data['works_text']);

        $laureate->update($data);

        return redirect()->route('laureates.index');
    }

    public function destroy(string $id)
    {
        //
    }
}
