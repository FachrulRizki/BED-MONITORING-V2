<?php

namespace App\Http\Controllers;

use App\Events\BedAvailabilityUpdated;
use App\Models\AmprahanReport;
use App\Models\Room;
use App\Services\AmprahanNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmprahanController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $reports = $this->filteredReportsQuery($filters)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('amprahans.index', compact('reports', 'filters'));
    }

    public function print(Request $request): View
    {
        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $reports = $this->filteredReportsQuery($filters)
            ->latest()
            ->get();

        return view('amprahans.print', compact('reports', 'filters'));
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
            'next_shift'           => ['required', 'in:pagi,sore,malam'],
            'report_time'          => ['required', 'date_format:H:i'],
            'male_patient_count'   => ['required', 'integer', 'min:0'],
            'female_patient_count' => ['required', 'integer', 'min:0'],
            'officer_name'         => ['required', 'string', 'max:255'],
            'next_officer_name'    => ['required', 'string', 'max:255'],
            'action_plan'          => ['nullable', 'string'],
            'image'                => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $imagePath = $request->file('image')->store('amprahans', 'public');

        $report = AmprahanReport::create([
            'room_id'              => $validated['room_id'],
            'shift'                => $validated['shift'],
            'next_shift'           => $validated['next_shift'],
            'report_time'          => $validated['report_time'],
            'male_patient_count'   => $validated['male_patient_count'],
            'female_patient_count' => $validated['female_patient_count'],
            'officer_name'         => $validated['officer_name'],
            'next_officer_name'    => $validated['next_officer_name'],
            'action_plan'          => $validated['action_plan'] ?? null,
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

    protected function filteredReportsQuery(array $filters): Builder
    {
        return AmprahanReport::with(['room', 'submittedBy'])
            ->when($filters['date_from'] ?? null, function (Builder $query, string $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($filters['date_to'] ?? null, function (Builder $query, string $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            });
    }
}
