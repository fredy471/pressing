<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Contact;

class PageController extends Controller
{
    public function index()
    {
        return response()
            ->view('pages.home')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }
    public function services()
    {
        // On récupère uniquement les services actifs depuis la base de données
        $services = Service::where('is_active', true)->get();

        return response()
            ->view('client.orders.create', compact('services'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function contact()
    {
        return response()
            ->view('pages.contact')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function sendContactForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'website' => 'nullable|string|max:255',
        ]);

        

        // Ici, vous pouvez envoyer l'e-mail ou stocker le message dans la base de données        
        Contact::create($validatedData);
        // Par exemple, en utilisant un service d'envoi d'e-mails ou en créant un modèle pour les messages de contact.

        return redirect()->back()->with('success', 'Merci pour votre message ! Nous vous répondrons bientôt.');
    }
}
