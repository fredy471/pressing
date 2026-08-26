<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $service = Service::latest()->paginate(10);

        return view('admin.services.index', compact('service'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request): RedirectResponse
    {
        Service::create($request->validated());
        return redirect()->route('admin.services.index')
            ->with('success', 'Service crée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        // On s'assure de ne mettre à jour QUE la description (le prix et le nom restent figés)
        $service->update($request->only('description'));

        return redirect()->route('admin.services.index')
            ->with('success', 'Description mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function toggleActive(Service $service): RedirectResponse
    {
        $service->update([
            'is_active' => !$service->is_active 
        ]);
        $status = $service->is_active ? 'activé' : 'désactivé';

        return redirect()->route('admin.services.index')
            ->with('success', "Le service a été {$status} avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }
}
