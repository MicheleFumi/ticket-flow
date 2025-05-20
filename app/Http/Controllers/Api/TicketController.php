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
        $ticket=Ticket::create([
            'user_id'=>$request->user()->id,
            'titolo'=>$request->titolo,
            'commento'=>$request->commento,
            'status_id' => $request->status_id ?? 1,  // 1 = Aperto di default


        ]);
        return response()->json([
            'message'=> 'ticket creato',
            'data'=> $ticket
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {

        $ticket->load('status');
        return response()->json([
            'data'=>$ticket
            
        ]);
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
        $data=$request->all();
        $ticket->titolo =$data['titolo'];
        $ticket->commento =$data['commento'];
       
        if (!isset($data['status_id']) || $data['status_id']=== null ) {
            $ticket->status_id=1;
        } else{
             $ticket->status_id =$data['status_id'];
        };
        $ticket->update();

        return response()->json([
            'data'=>$ticket
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {        
        $ticket->delete();
        $ticketList= Ticket::all();

        return response()->json([
            'data'=>$ticketList
        ]);
    }
}
