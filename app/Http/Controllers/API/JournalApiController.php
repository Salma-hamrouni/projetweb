<?php

namespace App\Http\Controllers\API;

use App\Models\Journal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JournalApiController extends Controller
{
    public function index()
    {
        // Récupérer tous les journaux
        $journals = Journal::with('user')->latest()->get();
        return response()->json($journals, 200);
    }

    public function show($id)
    {
        // Récupérer un journal spécifique
        $journal = Journal::findOrFail($id);
        return response()->json($journal, 200);
    }
}
