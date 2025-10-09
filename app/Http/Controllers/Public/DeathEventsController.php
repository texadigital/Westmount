<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DeathEvent;
use Illuminate\Http\Request;

class DeathEventsController extends Controller
{
    public function index()
    {
        $events = DeathEvent::query()
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->paginate(10);

        return view('public.death-events.index', compact('events'));
    }

    public function show(DeathEvent $event)
    {
        abort_unless($event->status === 'published', 404);

        $event->loadCount('contributions');
        return view('public.death-events.show', compact('event'));
    }
}
