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
        $technician = Auth::user();

        if (!$technician) {
            return redirect('/login')->with('error', 'Devi essere loggato come tecnico per accedere alla cronologia.');
        }

        if ($technician->is_admin) {
            $tickets = Ticket::join('ticket_logs as latest_logs', function ($join) {
                $join->on('tickets.id', '=', 'latest_logs.ticket_id')
                    ->whereRaw('latest_logs.id = (select max(id) from ticket_logs where ticket_id = tickets.id)');
            })
                ->where('tickets.status_id', 3)
                ->orderBy('latest_logs.data_chiusura', 'desc')
                ->with(['latestLog.technician', 'status', 'user'])
                ->get(['tickets.*']);
        } else {
            $tickets = Ticket::join('ticket_logs as latest_logs', function ($join) {
                $join->on('tickets.id', '=', 'latest_logs.ticket_id')
                    ->whereRaw('latest_logs.id = (select max(id) from ticket_logs where ticket_id = tickets.id)');
            })
                ->where('tickets.status_id', 3)
                ->where('latest_logs.assegnato_a', $technician->id)
                ->orderBy('latest_logs.data_chiusura', 'desc')
                ->with(['latestLog.technician', 'status', 'user'])
                ->get(['tickets.*']);
        }

        // dd($tickets);

        return view('dashboard.history', compact('technician', 'tickets'));
    }
}
