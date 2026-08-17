<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class PageController extends Controller
{
    public function index() 
    {
        return view('pages.home');
    }
    public function services()
    {
        // On récupère uniquement les services actifs depuis la base de données
        $services = Service::where('is_active', true)->get();

        return view('pages.services', compact('services'));
    }

    public function contact()
    {
        return view('pages.contact');
    }
}

