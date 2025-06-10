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
            'aperti' => $user->tickets->where('status_id', 1)->where("is_deleted", false),
            'in_lavorazione' => $user->tickets->where('status_id', 2)->where("is_deleted", false),
            'chiusi' => $user->tickets->where('status_id', 3)->where("is_deleted", false),
        ];

        return view('users.show', compact('user', 'ticketsByStatus'));
    }
}
