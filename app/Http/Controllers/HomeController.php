<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $events = Event::query()
            ->where('is_active', true)
            ->orderBy('start_date')
            ->orderBy('name')
            ->get();

        return view('home.index', [
            'events' => $events,
        ]);
    }
}
