<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Tenant $tenant)
    {
        return $tenant->load('contracts');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Tenant $tenant)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Tenant $tenant)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant, Contract $contract)
    {
        return $contract->load('unit.building.owner');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant, Contract $contract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant, Contract $contract)
    {
        //
    }
}
