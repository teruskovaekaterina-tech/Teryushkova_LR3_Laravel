<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaureateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    
    
    
    public function index()
    {
        $laureates = \App\Models\Laureate::orderBy('award_year')->get();

        return view('laureates.index', compact('laureates'));
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    
    public function show(\App\Models\Laureate $laureate)
    {
        return view('laureates.show', compact('laureate'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
