<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Http\Requests\Admin\StoreUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // je prend tout les informations avec les roles aussi
        $user = User::with('roles')->get();
        return view('admin.users.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $role = $data['role'];
        $data['password'] = $role === 'client' ? Hash::make('client@2026') : Hash::make('agent@2026');
        $data['created_by'] = auth()->id();
        unset($data['role']);

        $user = User::create($data);
        $user->assignRole($role);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur cree avec succes !');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->loadCount('orders');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('admin.users.update', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
