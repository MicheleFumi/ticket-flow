<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\Ticket;

use Illuminate\Http\Request;
use Mockery\Undefined;

use function Laravel\Prompts\error;

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
        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'titolo' => $request->titolo,
            'commento' => $request->commento,
            'status_id' => $request->status_id ?? 1,

        ]);
        if ($request->has('images') && is_array($request->images)) {
            foreach ($request->images as $imageUrl) {
                $ticket->images()->create([
                    'file_path' => $imageUrl,
                ]);
            }
        }

        $ticket->load('images');

        return response()->json([
            'message' => 'Ticket creato',
            'data' => $ticket
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
            $ticket->status_id;
        } else {
            $ticket->status_id = $data['status_id'];
        };
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

        if ($request->ticket->user_id === $user_id && $request->ticket->status_id === 1) {

            $ticket->delete();
            $ticketList = Ticket::where('user_id', $request->user()->id)->get();


            return response()->json([
                'data' => $ticketList
            ]);
        } else {
            return response()->json([
                'error' => 'something wrong happened'
            ], 400);
        }
    }
}
