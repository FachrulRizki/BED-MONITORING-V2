<?php

namespace App\Http\Controllers;

use App\Events\BedAvailabilityUpdated;
use App\Models\AmprahanReport;
use App\Models\Room;
use App\Services\AmprahanNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmprahanController extends Controller
{
    public function index(): View
    {
        $reports = AmprahanReport::with(['room'])
            ->latest()
            ->paginate(15);

        return view('amprahans.index', compact('reports'));
    }

    public function create(): View
    {
        $rooms = Room::orderBy('name')->get();

        return view('amprahans.create', compact('rooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_id'              => ['required', 'exists:rooms,id'],
            'shift'                => ['required', 'in:pagi,sore,malam'],
            'report_time'          => ['required', 'date_format:H:i'],
            'male_patient_count'   => ['required', 'integer', 'min:0'],
            'female_patient_count' => ['required', 'integer', 'min:0'],
            'officer_name'         => ['nullable', 'string', 'max:255'],
            'image'                => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('amprahans', 'public');
        }

        $report = AmprahanReport::create([
            'room_id'              => $validated['room_id'],
            'shift'                => $validated['shift'],
            'report_time'          => $validated['report_time'],
            'male_patient_count'   => $validated['male_patient_count'],
            'female_patient_count' => $validated['female_patient_count'],
            'officer_name'         => $validated['officer_name'] ?? null,
            'image_path'           => $imagePath,
            'submitted_by'         => auth()->id(),
        ]);

        $report->room->update([
            'male_occupied'   => $report->male_patient_count,
            'female_occupied' => $report->female_patient_count,
        ]);

        broadcast(new BedAvailabilityUpdated());

        (new AmprahanNotificationService())->notify($report);

        return redirect()->route('amprahans.index');
    }

    public function show(AmprahanReport $amprahan): View
    {
        $amprahan->load(['room', 'submittedBy']);

        return view('amprahans.show', compact('amprahan'));
    }
}
