<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('status', 'technician')->get();
        return view("tickets.index", compact("tickets"));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {


        $ticket->load('status',);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function assign(Ticket $ticket)
    {
        $technicianId = auth('web')->id();
        $technician = \App\Models\Technician::findOrFail($technicianId);


        DB::beginTransaction();

        try {
            // Assegna il ticket
            $ticket->technician_id = $technician->id;
            $ticket->status_id = 2;
            $ticket->data_assegnazione = Carbon::now();
            $ticket->save();


            // Rendi il tecnico non disponibile
            $technician->update(['is_available' => false]);

            DB::commit();

            return redirect()->route('tickets.index')->with('success', 'Ticket assegnato con successo');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Si è verificato un errore: ' . $e->getMessage());
        }
    }
}
