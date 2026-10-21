<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

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
}

