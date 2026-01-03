<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendee;
use App\Models\Member;
use App\Models\Zone;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function event($uniqueCode)
    {
        $event = Event::where('unique_code', $uniqueCode)->firstOrFail();

        // Check if there's an attendee ID in the session or query
        $attendee = null;
        if (request()->has('a')) {
            $attendee = Attendee::with('member')
                ->where('event_id', $event->id)
                ->find(request()->query('a'));
        }

        return view('checkin.event', compact('event', 'attendee'));
    }

    public function show($uniqueCode)
    {
        $event = Event::where('unique_code', $uniqueCode)->firstOrFail();
        $zones = Zone::where('is_active', true)->orderBy('name')->get();

        return view('checkin.show', compact('event', 'zones'));
    }

    public function lookup(Request $request, $uniqueCode)
    {
        $event = Event::where('unique_code', $uniqueCode)->firstOrFail();

        $phone = preg_replace('/[\s\-]/', '', $request->input('phone', ''));

        $member = Member::where('phone', $phone)->first();

        if ($member) {
            return response()->json([
                'found' => true,
                'member' => [
                    'id' => $member->id,
                    'name' => $member->name,
                ],
            ]);
        }

        return response()->json(['found' => false]);
    }

    public function store(Request $request, $uniqueCode)
    {
        $event = Event::where('unique_code', $uniqueCode)->firstOrFail();

        // Handle returning member check-in
        if ($request->boolean('returning') && $request->has('member_id')) {
            $member = Member::findOrFail($request->input('member_id'));

            // Check if already checked in
            $existingAttendee = Attendee::where('event_id', $event->id)
                ->where('member_id', $member->id)
                ->first();

            if ($existingAttendee) {
                return redirect()
                    ->route('checkin.success', ['uniqueCode' => $uniqueCode, 'attendee' => $existingAttendee->id])
                    ->with('info', 'You have already checked in to this event.');
            }

            // Create attendee record
            $attendee = Attendee::create([
                'event_id' => $event->id,
                'member_id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'checked_in_at' => now(),
            ]);

            return redirect()
                ->route('checkin.success', ['uniqueCode' => $uniqueCode, 'attendee' => $attendee->id])
                ->with('success', 'Successfully checked in!');
        }

        // Handle new member check-in
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'gender' => 'required|in:male,female',
            'zone_id' => 'required',
            'custom_location' => 'required_if:zone_id,other|nullable|string|max:255',
            'referral_source' => 'nullable|in:friend,family,social_media,flyer,website,passing_by,other',
        ], [
            'gender.required' => 'Please select your gender.',
            'zone_id.required' => 'Please select a zone or choose "Other".',
            'custom_location.required_if' => 'Please enter your location.',
        ]);

        // Normalize phone number (remove spaces, dashes)
        $phone = preg_replace('/[\s\-]/', '', $validated['phone']);

        // Determine zone_id and custom_location
        $zoneId = null;
        $customLocation = null;

        if ($validated['zone_id'] === 'other') {
            $customLocation = $validated['custom_location'];
        } else {
            $zoneId = (int) $validated['zone_id'];
        }

        // Find or create member by phone number
        $member = Member::firstOrCreate(
            ['phone' => $phone],
            [
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'gender' => $validated['gender'],
                'zone_id' => $zoneId,
                'custom_location' => $customLocation,
                'referral_source' => $validated['referral_source'] ?? null,
            ]
        );

        // Update member info if they exist
        if (!$member->wasRecentlyCreated) {
            $updateData = ['name' => $validated['name']];

            // Update email if provided and not set
            if (!empty($validated['email']) && !$member->email) {
                $updateData['email'] = $validated['email'];
            }

            // Update gender if not set
            if (!$member->gender) {
                $updateData['gender'] = $validated['gender'];
            }

            // Only update location if member doesn't have one set yet
            if (!$member->zone_id && !$member->custom_location) {
                $updateData['zone_id'] = $zoneId;
                $updateData['custom_location'] = $customLocation;
            }

            // Update referral source if not set
            if (!empty($validated['referral_source']) && !$member->referral_source) {
                $updateData['referral_source'] = $validated['referral_source'];
            }

            $member->update($updateData);
        }

        // Check if already checked in to this event
        $existingAttendee = Attendee::where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existingAttendee) {
            return redirect()
                ->route('checkin.success', ['uniqueCode' => $uniqueCode, 'attendee' => $existingAttendee->id])
                ->with('info', 'You have already checked in to this event.');
        }

        // Create attendee record
        $attendee = Attendee::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'name' => $validated['name'],
            'phone' => $phone,
            'checked_in_at' => now(),
        ]);

        return redirect()
            ->route('checkin.success', ['uniqueCode' => $uniqueCode, 'attendee' => $attendee->id])
            ->with('success', 'Successfully checked in!');
    }

    public function success($uniqueCode, $attendeeId)
    {
        $event = Event::where('unique_code', $uniqueCode)->firstOrFail();
        $attendee = Attendee::with('member.zone')->findOrFail($attendeeId);

        return view('checkin.success', compact('event', 'attendee'));
    }

    public function kiosk($uniqueCode)
    {
        $event = Event::where('unique_code', $uniqueCode)->firstOrFail();
        $zones = Zone::where('is_active', true)->orderBy('name')->get();

        return view('checkin.kiosk', compact('event', 'zones'));
    }
}
