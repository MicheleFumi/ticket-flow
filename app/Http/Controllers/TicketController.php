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
        $technician = Auth::guard()->user();
        $tickets = Ticket::with('status', 'technician', 'user')->where('is_reported', false)->where("is_deleted", false)->orderBy('id', 'ASC')->get();
        $reportedTickets = Ticket::with('status', 'technician')->where('is_reported', true)->orderBy('id', 'ASC')->get();
        $allTickets = Ticket::with('status', 'technician')->where("is_deleted", false)->orderBy('id', 'ASC')->get();
        return view("tickets.index", compact("tickets", "technician", "reportedTickets", "allTickets"));
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
    public function show(Ticket $ticket, Technician $technicianList)
    {


        $ticket->load('status', 'technician', 'reportatoDa');
        $technician = Auth::guard()->user();
        $technicianList = Technician::where("is_available", 1)->get();
        return view('tickets.show', compact('ticket', 'technician', 'technicianList'));
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
    public function destroy(Ticket $ticket, Request $request)
    {

        /*   $technician = Auth::guard()->user();


        if (!$technician || !$technician->is_admin) {

            return redirect()->back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        } */
        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket non trovato.');
        }

        if ($ticket->status_id === 2) {
            return redirect()->back()->with('error', 'Impossibile eliminare un ticket assegnato a un tecnico.');
        }
        if ($ticket->status_id === 3) {
            return redirect()->back()->with('error', 'Impossibile eliminare un ticket chiuso.');
        }

        DB::beginTransaction();
        try {
            $ticket->is_deleted = true;
            $ticket->save();

            DB::commit();
            return redirect()->route('tickets.index')->with('success', 'Ticket eliminato con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante l\'eliminazione del ticket: ' . $e->getMessage());
        }
    }

    public function report(Ticket $ticket, Request $request)
    {
        $technician = Auth::guard()->user();

        /*  if (!$technician && !$request->filled('commento_report')) {

            return redirect()->back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        }

        if (!$technician && $request->filled('commento_report')) {

            return redirect()->back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        } */

        if ($ticket->status_id === 3 || $ticket->status_id === 2 || $ticket->is_reported === true || $ticket->commento_report) {

            return redirect()->back()->with('error', 'Il ticket è già stato chiuso, assegnato oppure reportato');
        }

        if ($technician && $request->filled('commento_report')) {

            DB::beginTransaction();

            try {
                $ticket->is_reported = true;
                $ticket->commento_report = $request->input('commento_report');
                $ticket->reportato_da = $technician->id;
                $ticket->report_date = now();
                $ticket->save();



                DB::commit();

                return redirect()->route('tickets.index')->with('success', 'Segnalazione inviata con successo.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Errore durante la segnalazione: ' . $e->getMessage());
            }
        }
    }




    public function assign(Ticket $ticket)
    {
        /** @var \App\Models\Technician $technician */

        $technician = Auth::guard()->user();

        if (!$technician) {
            return redirect()->back()->with('error', 'Utente non autenticato come tecnico.');
        }

        if ($ticket->status_id === 3) {
            return redirect()->back()->with('error', 'Impossibile assegnare un ticket già chiuso.');
        }

        if ($ticket->status_id === 2) {
            return redirect()->back()->with('error', 'Impossibile assegnare un ticket già assegnato a un tecnico.');
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

    public function assignTo(Request $request, Ticket $ticket)
    {

        $admin = Auth::guard()->user();
        /* if (!$admin) {
            return redirect()->back()->with('error', 'Utente non autenticato come tecnico.');
        }

        if (!$admin->is_admin) {
            return redirect()->back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        } */

        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);
        $technician = Technician::find($request->technician_id);
        if ($technician->is_available === 0) {
            return redirect()->back()->with('error', 'Tecnico non disponibile');
        }

        if ($ticket->status_id === 2) {
            return redirect()->back()->with('error', 'Il ticket è già stato assegnato ad un tecnico ed è in fase di lavorazione.');
        }
        if ($ticket->status_id === 3) {
            return redirect()->back()->with('error', 'Il ticket è già stato chiuso.');
        }

        DB::beginTransaction();
        try {

            $ticket->assignToTechnician($technician);
            DB::commit();
            return redirect()->route('tickets.index')->with('success', 'Tecnico assegnato con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante la rimozione: ' . $e->getMessage());
        }
    }

    public function unassign(Request $request)
    {
        /** @var \App\Models\Technician $admin */
        $admin = Auth::guard()->user();

        /*  if (!$admin) {
            return redirect()->back()->with('error', 'Utente non autenticato come tecnico.');
        } */

        /*  if (!$admin->is_admin) {
            return redirect()->back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        } */

        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);

        $technician = Technician::find($request->technician_id);

        if (!$technician) {
            return redirect()->back()->with('error', 'Tecnico non trovato.');
        }

        $tickets = Ticket::where('technician_id', $technician->id)->get();

        if ($tickets->isEmpty()) {
            return redirect()->back()->with('info', 'Nessun ticket assegnato a questo tecnico.');
        }

        DB::beginTransaction();

        try {
            foreach ($tickets as $ticket) {
                $ticket->removeFromTechnician($technician);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Tecnico rimosso da tutti i ticket con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante la rimozione: ' . $e->getMessage());
        }
    }

    public function close(Request $request)
    {
        /** @var \App\Models\Technician $technician */
        $technician = Auth::guard()->user();

        if (!$technician) {
            return redirect()->back()->with('error', 'Utente non autenticato come tecnico.');
        }

        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
        ]);
        $ticket = Ticket::find($request->ticket_id);

        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket non trovato.');
        }
        $note_chiusura = $request->input('note_chiusura');

        // if (!$technician->is_admin && $ticket->technician_id !== $technician->id) {
        //     return redirect()->back()->with('error', 'Non sei autorizzato a chiudere questo ticket.');
        // }


        DB::beginTransaction();

        try {
            $ticket->close($technician, $note_chiusura);

            DB::commit();

            return redirect()->route('dashboard.index')->with('success', 'Ticket chiuso con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante la chiusura del ticket: ' . $e->getMessage());
        }
    }
}
