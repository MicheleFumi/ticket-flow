<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TechnicianController extends Controller
{
    public function userToTechnician(Request $request)
    {
        $adminTechnician = Technician::where('is_admin', true)->first();

        if (!$adminTechnician) {
            return Redirect::back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = $this->getUsers()->firstWhere('id', $request->user_id);

        if (!$user) {
            return Redirect::back()->with('error', 'Utente non trovato.');
        }

        $technician = Technician::create([
            'name' => $user->name,
            'cognome' => $user->cognome,
            'email' => $user->email,
            'password' => $user->password,
            'telefono' => $user->telefono,
            'is_admin' => $user->is_admin ?? false,
            'is_avaible' => true,
        ]);

        return Redirect::back()->with('success', 'Tecnico creato con successo!');
    }

    private function getUsers()
    {
        return User::all();
    }
}
