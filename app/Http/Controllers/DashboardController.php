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
        $technician = Auth::guard('web')->user();
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
}
