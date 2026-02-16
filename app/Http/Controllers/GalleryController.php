<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::has('galleryImages')
            ->withCount('galleryImages')
            ->orderBy('date', 'desc')
            ->get();

        $selectedEvent = null;
        $query = GalleryImage::with('event')->orderBy('sort_order');

        if ($request->has('event') && $request->event) {
            $selectedEvent = Event::find($request->event);
            $query->where('event_id', $request->event);
        }

        $images = $query->get();

        return view('gallery.index', compact('events', 'images', 'selectedEvent'));
    }
}
