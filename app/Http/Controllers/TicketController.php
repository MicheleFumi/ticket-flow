<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\Ticket;
use App\Models\TicketLog;
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
        $tickets = Ticket::with('status', 'technician', 'user')->where("status_id", 1)->where('is_reported', false)->where("is_deleted", false)->orderBy('id', 'ASC')->get();
        $allTickets = Ticket::with('status', 'technician')->where("status_id", 1)->where("is_deleted", false)->orderBy('id', 'ASC')->get();
        $reportedTickets = Ticket::with('status', 'technician')->where("status_id", 1)->where('is_reported', true)->orderBy('id', 'ASC')->get();
        $reopenedTickets = Ticket::with('status', 'technician')->where("status_id", 1)->where('is_reopened', true)->where("is_deleted", false)->orderBy('id', 'ASC')->get();
        $visibleTickets = $technician->is_admin ? $allTickets->merge($reopenedTickets)->merge($reportedTickets) : $tickets->merge($reopenedTickets)->merge($reportedTickets)->sortBy([
            fn($a, $b) => $b->is_reopened <=> $a->is_reopened,
            fn($a, $b) => ($a->status->titolo === 'Aperto' ? 0 : 1) <=> ($b->status->titolo === 'Aperto' ? 0 : 1),
            fn($a, $b) => $a->created_at <=> $b->created_at,
        ]);

        return view("tickets.index", compact("technician", "reportedTickets", "reopenedTickets", "visibleTickets"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket, Technician $technicianList)
    {


        $ticket->load('status', 'technician', 'reportatoDa', "logs", "images");
        $technician = Auth::guard()->user();
        $technicianList = Technician::where("is_available", 1)->get();
        $logs = TicketLog::where('ticket_id', $ticket->id)
            ->with([
                'assignedTechnician', // assegnato_a
                'userWhoReopened',    // riaperto_da_user
                'adminWhoReopened',   // riaperto_da_admin
                'technicianWhoClosed' // chiuso_da
            ])
            ->latest()
            ->get();
        // dd($logs);
        return view('tickets.show', compact('ticket', 'technician', 'technicianList', 'logs'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket, Request $request)
    {

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

        $technician = Auth::guard()->user();

        if (!$technician) {
            return redirect()->back()->with('error', 'Utente non autenticato come tecnico.');
        }

        if (!$technician->is_available) {
            return redirect()->back()->with('error', 'Tecnico non disponibile.');
        }

        if ($ticket->status_id === 3) {
            return redirect()->back()->with('error', 'Impossibile assegnare un ticket già chiuso.');
        }

        if ($ticket->status_id === 2) {
            return redirect()->back()->with('error', 'Impossibile assegnare un ticket già assegnato a un tecnico.');
        }

        $latestLog = $ticket->logs()->latest()->first();

        if (!$latestLog) {
            return redirect()->back()->with('error', 'Nessun log trovato per questo ticket.');
        }

        if ($latestLog->assegnato_a) {
            return redirect()->back()->with('error', 'Il ticket è già assegnato a un tecnico.');
        }


        DB::beginTransaction();

        try {
            $latestLog->assignToTechnician($technician, $ticket);
            DB::commit();

            return redirect()->route('dashboard.index')->with('success', 'Ticket assegnato con successo.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Errore durante l\'assegnazione: ' . $e->getMessage());
        }
    }

    public function assignTo(Request $request, Ticket $ticket)
    {

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

        $latestLog = $ticket->logs()->latest()->first();

        if (!$latestLog) {
            return redirect()->back()->with('error', 'Nessun log trovato per questo ticket.');
        }

        if ($latestLog->assegnato_a) {
            return redirect()->back()->with('error', 'Il ticket è già assegnato a un tecnico.');
        }

        DB::beginTransaction();
        try {

            $latestLog->assignToTechnician($technician, $ticket);
            DB::commit();
            return redirect()->route('tickets.index')->with('success', 'Tecnico assegnato con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante la rimozione: ' . $e->getMessage());
        }
    }

    public function unassign(Request $request, Ticket $ticket)
    {

        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);

        $technician = Technician::find($request->technician_id);

        if (!$technician) {
            return redirect()->back()->with('error', 'Tecnico non trovato.');
        }

        if ($ticket->status_id === 1) {
            return redirect()->back()->with('error', 'Il ticket non è assegnato a nessun tecnico.');
        }

        if ($ticket->status_id === 3) {
            return redirect()->back()->with('error', 'Il ticket è già chiuso e non può essere disassegnato.');
        }

        $latestLog = $ticket->logs()->where('assegnato_a', $technician->id)->latest()->first();

        if (!$latestLog) {
            return redirect()->back()->with('error', 'Nessun log trovato per questo tecnico.');
        }
        // dd($ticket);

        DB::beginTransaction();

        try {
            $latestLog->removeFromTechnician($technician, $ticket);
            $latestLog->save();

            DB::commit();
            return redirect()->back()->with('success', 'Tecnico rimosso da tutti i ticket con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante la rimozione: ' . $e->getMessage());
        }
    }

    public function close(Request $request, Ticket $ticket)
    {

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

        $latestLog = $ticket->logs()->latest()->first();


        DB::beginTransaction();

        try {
            $latestLog->close($technician, $note_chiusura, $ticket);

            DB::commit();

            return redirect()->route('dashboard.index')->with('success', 'Ticket chiuso con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante la chiusura del ticket: ' . $e->getMessage());
        }
    }

    public function reopen(Request $request, Ticket $ticket)
    {
        /** @var \App\Models\Technician $technician */
        $technician = Auth::guard()->user();

        if (!$technician) {
            return redirect()->back()->with('error', 'Utente non autenticato come tecnico.');
        }

        if ($ticket->status_id !== 3) {
            return redirect()->back()->with('error', 'Il ticket non è chiuso e non può essere riaperto.');
        }

        $validated = $request->validate([
            'ragione_riapertura' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            TicketLog::create([
                'ticket_id' => $ticket->id,
                'assegnato_a' => null,
                'riaperto_da_user' => null,
                'riaperto_da_admin' => $technician->id,
                'chiuso_da' => null,
                'note_riapertura' => $validated['ragione_riapertura'],
                'note_chiusura' => null,
                'data_assegnazione' => null,
                'data_riapertura' => now(),
                'data_chiusura' => null,
            ]);

            $ticket->update([
                'is_reopened' => true,
                'status_id' => 1,
            ]);

            DB::commit();

            return redirect()->route('tickets.index')->with('success', 'Ticket riaperto con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Errore durante la riapertura del ticket: ' . $e->getMessage());
        }
    }
}
