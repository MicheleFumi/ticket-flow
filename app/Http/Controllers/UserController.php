<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where("is_technician", false)
            ->withCount('tickets')
            ->get();

        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'tickets.status'
        ]);

        $ticketsByStatus = [
            'aperti' => $user->tickets->where('status_id', 1),
            'in_lavorazione' => $user->tickets->where('status_id', 2),
            'chiusi' => $user->tickets->where('status_id', 3),
        ];

        return view('users.show', compact('user', 'ticketsByStatus'));
    }
}
