<?php

namespace App\Http\Controllers;

use App\Http\Resources\BuildingResource;
use App\Http\Resources\OwnerResource;
use App\Models\Building;
use App\Models\Owner;
use Illuminate\Http\Request;

class OwnerBuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Owner $owner)
    {
        return OwnerResource::make($owner->load('buildings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Owner $owner)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Owner $owner)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Owner $owner, Building $building)
    {
        return BuildingResource::make($building->load(['owner', 'units']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Owner $owner, Building $building)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Owner $owner, Building $building)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Owner $owner, Building $building)
    {
        //
    }
}
