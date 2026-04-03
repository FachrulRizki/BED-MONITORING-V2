<?php

namespace App\Http\Controllers;

use App\Models\AmprahanReport;
use App\Models\Room;
use Illuminate\View\View;

class AuthDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_rooms'          => Room::count(),
            'total_available'      => Room::sum('male_capacity') + Room::sum('female_capacity')
                                      - Room::sum('male_occupied') - Room::sum('female_occupied'),
            'total_occupied'       => Room::sum('male_occupied') + Room::sum('female_occupied'),
            'reports_today'        => AmprahanReport::whereDate('created_at', today())->count(),
            'unread_notifications' => auth()->user()->unreadNotifications()->count(),
        ];

        $recentReports = AmprahanReport::with(['room', 'submittedBy'])->latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'recentReports'));
    }
}
