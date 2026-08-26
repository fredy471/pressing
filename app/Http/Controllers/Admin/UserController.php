<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Http\Requests\Admin\StoreUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
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
        $temporaryPassword = Str::random(8);
        $data['password'] = Hash::make($temporaryPassword);
        $data['created_by'] = auth()->id();
        unset($data['role']);
        // TODO : envoyer $temporaryPassword au user via email/WhatsApp
        // Mail::to($user->email)->send(new TemporaryPasswordMail($temporaryPassword));
        $user = User::create($data);
        $user->assignRole($role);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur cree avec success. !');
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
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer votre propre compte.');
        }
        $role = $user->getRoleNames()->first() ?? 'Aucun rôle';
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Le compte de l'utilisateur (Rôle : {$role}) a été supprimé avec succès.");
    }
}
