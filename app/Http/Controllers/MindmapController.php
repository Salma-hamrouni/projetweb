<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objectif;

class MindmapController extends Controller
{
    public function index()
    {
        $objectifs = Objectif::with('etapes')->where('user_id', auth()->id())->get();

        return view('MindMap.index', compact('objectifs'));
    }
}
