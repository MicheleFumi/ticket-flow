<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\Ticket;
use Illuminate\Http\Request;

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


    public function assign(Ticket $ticket, Technician $technician)
    {
        $technician_id = auth()->guard('web')->user()->id;
        $ticket->technician_id = $technician_id;
        $ticket->status_id = 2;
        $ticket->save();

        $technician->is_available = false;
        $technician->save();
        return redirect()->route('tickets.index')->with('success', 'Ticket assegnato con successo');
    }
}
