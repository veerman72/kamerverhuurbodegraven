<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Unit;
use Illuminate\Http\Request;

class BuildingUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Building $building)
    {
        return $building->load('units');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Building $building)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Building $building)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Building $building, Unit $unit)
    {
        return $unit;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Building $building, Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Building $building, Unit $unit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building, Unit $unit)
    {
        //
    }
}
