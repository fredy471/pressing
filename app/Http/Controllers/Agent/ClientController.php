<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class ClientController extends Controller
{
    public function index (): View
    {
        $clients = User::where('role', 'client')->get();
        return view('agent.clients.index', compact('clients'));
    }
}
