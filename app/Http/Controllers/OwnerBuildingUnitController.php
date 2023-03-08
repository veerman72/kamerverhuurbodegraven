<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildingResource;
use App\Http\Resources\UnitResource;
use App\Models\Building;
use App\Models\Owner;
use App\Models\Unit;
use Illuminate\Http\Request;

class OwnerBuildingUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Owner $owner, Building $building)
    {
        return BuildingResource::make($building->load(['owner', 'units']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Owner $owner, Building $building)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Owner $owner, Building $building)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Owner $owner, Building $building, Unit $unit)
    {
        return UnitResource::make($unit->load(['building.owner', 'contract_provisions']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Owner $owner, Building $building, Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Owner $owner, Building $building, Unit $unit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Owner $owner, Building $building, Unit $unit)
    {
        //
    }
}
