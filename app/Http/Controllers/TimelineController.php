<?php
namespace App\Http\Controllers;

use App\Models\Objectif;
use App\Http\Controllers\ProgressionController;

class TimelineController extends Controller
{
    public function index()
{
    $objectifs = Objectif::with('etapes')->get();
    $progressionController = new ProgressionController();

    $objectifsWithProgression = $objectifs->map(function ($objectif) use ($progressionController) {
        $data = $progressionController->calculateProgression($objectif);
        $objectif->progression = $data['progression'];
        $objectif->bgClass = $data['bgClass'];
        return $objectif;
    });

    return view('timeline.index', ['objectifs' => $objectifsWithProgression]);
}

}
