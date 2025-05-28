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
}
