<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index()
    {
        // Afficher les journaux
        $journals = Journal::with('user')->latest()->get();  // Pour récupérer les journaux récents avec l'utilisateur associé

        return view('journals.index', compact('journals'));
    }
}
