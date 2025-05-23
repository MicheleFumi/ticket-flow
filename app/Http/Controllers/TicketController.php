<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        /** @var \App\Models\Technician $technician */

        $technician = Auth::guard()->user();

        if (!$technician) {
            return redirect()->back()->with('error', 'Utente non autenticato come tecnico.');
        }

        DB::beginTransaction();

        try {
            $ticket->assignToTechnician($technician);

            DB::commit();

            return redirect()->route('dashboard.index')->with('success', 'Ticket assegnato con successo.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Errore durante l\'assegnazione: ' . $e->getMessage());
        }
    }
}
