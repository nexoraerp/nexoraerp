<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->get();

        return Inertia::render('Warehouses/Index', [
            'warehouses' => $warehouses,
        ]);
    }

    public function create()
    {
        return Inertia::render('Warehouses/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                Rule::unique('warehouses', 'code')->where('user_id', $request->user()?->id),
            ],
            'name' => 'required',
            'manager' => 'nullable',
            'phone' => 'nullable',
            'address' => 'nullable',
            'is_active' => 'boolean',
        ]);

        Warehouse::create($validated);

        return redirect()->route('warehouses.index');
    }

    public function edit(Warehouse $warehouse)
    {
        return Inertia::render('Warehouses/Edit', [
            'warehouse' => $warehouse,
        ]);
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                Rule::unique('warehouses', 'code')
                    ->where('user_id', $request->user()?->id)
                    ->ignore($warehouse->id),
            ],
            'name' => 'required',
            'manager' => 'nullable',
            'phone' => 'nullable',
            'address' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $warehouse->update($validated);

        return redirect()->route('warehouses.index');
    }

    public function show(Warehouse $warehouse)
    {
        return Inertia::render('Warehouses/Show', [
            'warehouse' => $warehouse,
        ]);
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index');
    }
}
