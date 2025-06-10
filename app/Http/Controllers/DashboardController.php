<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $technician = Auth::guard()->user();
        if (!$technician) {
            return redirect('/login')->with('error', 'Devi essere loggato come tecnico per accedere a questa dashboard.');
        }

        $technicianId = $technician->id;

        $tickets = Ticket::with(['technician', 'status'])
            ->where('technician_id', $technicianId)
            ->where('status_id', 2)
            ->get();

        if ($technician->is_admin) {
            $tickets = Ticket::with(['technician', 'status'])
                ->where('status_id', 2)
                ->get();
        }


        return view('dashboard.index', compact("technician", 'tickets'));
    }

    public function history()
    {
        $technician = Auth::guard()->user();
        if (!$technician) {
            return redirect('/login')->with('error', 'Devi essere loggato come tecnico per accedere alla cronologia.');
        }

        $technicianId = $technician->id;

        $tickets = Ticket::withoutGlobalScopes()
            ->with(['allTechnicians', 'status', 'closedBy'])
            ->where('technician_id', $technicianId)
            ->where('status_id', 3)
            ->orderBy('data_chiusura', 'desc')
            ->get();

        if ($technician->is_admin) {
            $tickets = Ticket::withoutGlobalScopes()
                ->with(['allTechnicians', 'status', 'closedBy'])
                ->where('status_id', 3)
                ->orderBy('data_chiusura', 'desc')
                ->get();
        }

        return view('dashboard.history', compact("technician", 'tickets'));
    }
}
