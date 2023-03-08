<?php

namespace App\Http\Controllers;

use App\Actions\Contracts\CreateContract;
use App\Http\Resources\ContractResource;
use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ContractResource::collection(
            Contract::query()
                ->orderBy('reference')
                ->paginate(),
        );
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
        $contract = new CreateContract();
        $contract->handle($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        return ContractResource::make($contract->load(['unit.building.owner', 'tenants']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        //
    }
}
