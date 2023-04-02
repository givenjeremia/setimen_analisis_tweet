<?php

namespace App\Http\Controllers;

use App\Models\Visualisasi;
use Illuminate\Http\Request;

class VisualisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('visualisasi');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Visualisasi  $visualisasi
     * @return \Illuminate\Http\Response
     */
    public function show(Visualisasi $visualisasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Visualisasi  $visualisasi
     * @return \Illuminate\Http\Response
     */
    public function edit(Visualisasi $visualisasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visualisasi  $visualisasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visualisasi $visualisasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Visualisasi  $visualisasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visualisasi $visualisasi)
    {
        //
    }
}
