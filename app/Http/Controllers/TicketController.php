<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $tickets = [
        [
            'id' => 1,
            'titolo' => 'Errore login',
            'commento' => 'Il sistema restituisce errore 500 al login.',
            'stato' => 'aperto',
            'data' => '2025-05-01',
        ],
        [
            'id' => 2,
            'titolo' => 'Crash su salvataggio',
            'commento' => 'L\'applicazione va in crash quando si salva un nuovo record.',
            'stato' => 'in lavorazione',
            'data' => '2025-05-10',
        ],
        [
            'id' => 3,
            'titolo' => 'UI non responsiva',
            'commento' => 'Il layout non si adatta su dispositivi mobili.',
            'stato' => 'chiuso',
            'data' => '2025-05-15',
        ],
    ];

    return view("tickets", compact("tickets"));
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
    public function show(string $id)
    {
        //
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
}
