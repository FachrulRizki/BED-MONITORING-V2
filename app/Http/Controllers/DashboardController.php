<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $rooms = Room::all();

        return view('dashboard.index', compact('rooms'));
    }
}
