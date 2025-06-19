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

        $query = Ticket::with(['latestLog.technician', 'status', 'user']);

        if ($technician->is_admin) {
            $query->where('status_id', 2);
        } else {
            // Per i tecnici non admin, vedi solo i ticket assegnati a loro e in lavorazione.
            $query->where('status_id', 2)
                ->whereHas('latestLog', function ($q) use ($technician) {
                    $q->where('assegnato_a', $technician->id);
                });
        }

        $tickets = $query->get();
        // dd($tickets);

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
            $tickets = Ticket::withoutGlobalScopes(["still_active"])
                ->with(['allTechnicians', 'status', 'closedBy'])
                ->where('status_id', 3)
                ->orderBy('data_chiusura', 'desc')
                ->get();
        }

        // dd($tickets);

        return view('dashboard.history', compact("technician", 'tickets'));
    }
}
