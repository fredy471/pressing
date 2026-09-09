<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Http\Requests\Client\ContactRequest;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function storeMessage(ContactRequest $request): RedirectResponse
    {
        $validated = $request->validated();
       Contact::create($validated);
       return back()->with('success', "demande soumis avec success.<br/> Vous serez re-contactez sous peu");
    }
}
