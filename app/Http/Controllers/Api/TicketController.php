<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tickets = $request->user()->tickets;
        return response()->json($tickets);
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
        $request->validate([
            'titolo' => 'required|string|max:255',
            'commento' => 'required|string',
            'status_id' => 'nullable|exists:statuses,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' // 2MB max per immagine
        ]);

        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'titolo' => $request->titolo,
            'commento' => $request->commento,
            'status_id' => $request->status_id ?? 1,
        ]);

        $log = TicketLog::create([
            'ticket_id' => $ticket->id,
            'assegnato_a' => null,
            'riaperto_da_user' => null,
            'riaperto_da_admin' => null,
            'chiuso_da' => null,
            'note_riapertura' => null,
            'note_chiusura' => null,
            'data_assegnazione' => null,
            'data_riapertura' => null,
            'data_chiusura' => null,
        ]);

        if ($request->has('images') && is_array($request->file('images'))) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('ticket_images', 'public');

                $ticket->images()->create([
                    'file_path' => $path,
                ]);
            }
        }

        $ticket->load('images');

        return response()->json([
            'message' => 'Ticket creato',
            'data' => $ticket,
            'log' => $log
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket, Request $request)
    {
        $user_id = $request->user()->id;
        if ($ticket->user_id === $user_id) {
            $ticket->load('status', "images");
            return response()->json([
                'data' => $ticket
            ]);
        }
        return response()->json([
            'error' => "ticket non trovato o non creato dall'utente",
        ], 402);
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
    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->all();
        $ticket->titolo = $data['titolo'];
        $ticket->commento = $data['commento'];

        if (!isset($data['status_id']) || $data['status_id'] === null) {
        } else {
            $ticket->status_id = $data['status_id'];
        }
        $ticket->update();

        return response()->json([
            'data' => $ticket
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket, Request $request)
    {
        $user_id = $request->user()->id;

        if ($ticket->user_id === $user_id && $ticket->status_id === 1) {

            $ticket->is_deleted = true;
            $ticket->save();

            return response()->json([
                'message' => 'Ticket eliminato con successo',
            ]);
        } else {
            return response()->json([
                'error' => 'something wrong happened'
            ], 400);
        }
    }
}
