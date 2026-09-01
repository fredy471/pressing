<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClothesType;
use Illuminate\View\View;
use App\Http\Requests\Admin\StoreClothesTypeRequest;
use App\Http\Requests\Admin\UpdateClothesTypeRequest;
use Illuminate\Http\RedirectResponse;

class ClothesTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $clothesTypes = ClothesType::latest()->paginate(10);
        $count = ClothesType::count();

        return view('admin.clothes-types.index', compact('clothesTypes', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.clothes-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClothesTypeRequest $request): RedirectResponse
    {
        ClothesType::create($request->validated());
        return redirect()->route('admin.clothes-types.index')
            ->with('success', 'Type de vêtement créé avec succès. veuillez inserez les montants relative dans la section tarifs');
    }

    /**
     * Display the specified resource.
     */
    // public function show(ClothesType $clothesType): View
    // {
    //     return view('admin.clothes-types.show', compact('clothesType'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClothesType $clothesType): View  {
        return view('admin.clothes-types.edit', compact('clothesType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCategory(UpdateClothesTypeRequest $request, ClothesType $clothesType): RedirectResponse
    {
        $clothesType->update($request->only('category'));

        return redirect()->route('admin.clothes-types.index')
            ->with('success', 'Type de vêtement mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function toggleActive(ClothesType $clothesType): RedirectResponse
    {  
        $clothesType->update([
            'is_active' => !$clothesType->is_active
        ]);

        // On adapte le message selon le nouveau statut
        $status = $clothesType->is_active ? 'activé' : 'désactivé';

        return redirect()->back()
            ->with('success', "Le type de vêtement a été {$status} avec succès.");
    }
}
