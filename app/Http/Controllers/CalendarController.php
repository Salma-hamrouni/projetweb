<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objectif;

class CalendarController extends Controller
{
    public function index()
    {
        $objectifs = Objectif::all();

        $events = $objectifs->map(function ($event) {
            return [
                'title' => $event->title,
                'start' => $event->deadline ? $event->deadline->toDateTimeString() : $event->created_at->toDateTimeString(),
'end' => $event->deadline ? $event->deadline->toDateTimeString() : $event->created_at->toDateTimeString(),

                'description' => $event->description,
                'progression' => $event->progression,
            ];
        });
        
        return view('calendrier.index', ['events' => $events]);
        
    }
}
