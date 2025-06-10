<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TechnicianController extends Controller
{
    public function index()
    {
        $technicians = Technician::all();
        $users = $this->getUsers();
        $nonAdminTechnicians = Technician::where('is_admin', false)->get();
        $adminTechnicians = Technician::where('is_admin', true)->where("is_superadmin", false)->get();
        // dd($nonAdminTechnicians);
        return view('technicians.index', compact('technicians', 'users', 'nonAdminTechnicians', 'adminTechnicians'));
    }

    public function userToTechnician(Request $request)
    {

        $admin = auth()->guard()->user();

        if (!$admin->is_admin) {
            return Redirect::back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        }

        $user = User::where('id', $request->input('user_id'))->firstOrFail();

        if (!$user) {
            return Redirect::back()->with('error', 'Utente non trovato.');
        }

        if ($user->is_technician) {
            return Redirect::back()->with("error", "L'utente è già un tecnico.");
        }

        DB::beginTransaction();

        try {

            $technician = Technician::withoutGlobalScopes()
                ->where('email', $user->email)
                ->first();

            if ($technician) {
                $technician->still_active = true;
                $technician->is_available = true;
                $technician->save();
            } else {
                $technician = Technician::create([
                    'nome' => $user->nome,
                    'cognome' => $user->cognome,
                    'email' => $user->email,
                    'password' => $user->password,
                    'telefono' => $user->telefono,
                    'is_admin' => $user->is_admin ?? false,
                    'is_available' => true,
                    'still_active' => true,
                ]);
            }

            $user->update([
                'is_technician' => true,
            ]);

            DB::commit();

            return Redirect::back()->with('success', 'Utente promosso e tecnico creato con successo!');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Errore durante la promozione dell\'utente a tecnico: ' . $e->getMessage());
        }
    }

    public function technicianToUser(Request $request)
    {

        $authTechnician = auth()->guard()->user();

        if (!$authTechnician || !$authTechnician->is_admin) {
            return Redirect::back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        }

        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);

        $technician = Technician::find($request->technician_id);

        if (!$technician) {
            return Redirect::back()->with('error', 'Tecnico non trovato.');
        }

        if ($technician->is_admin) {
            return Redirect::back()->with('error', 'Impossibile rimuovere un tecnico amministratore. Modificare prima i suoi privilegi.');
        }

        DB::beginTransaction();

        try {

            foreach ($technician->tickets as $ticket) {
                $ticket->removeFromTechnician($technician);
            }

            $user = User::where('email', $technician->email)->first();
            if ($user) {
                $user->update(['is_technician' => false]);
            }

            $technician->still_active = false;
            $technician->save();

            DB::commit();

            return Redirect::back()->with('success', 'Tecnico rimosso con successo!');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Errore durante la rimozione del tecnico: ' . $e->getMessage());
        }
    }

    public function technicianToAdmin(Request $request)
    {
        $authTechnician = auth()->guard()->user();

        if (!$authTechnician || !$authTechnician->is_superadmin) {
            return Redirect::back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        }

        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);

        $technician = Technician::find($request->technician_id);

        if (!$technician) {
            return Redirect::back()->with('error', 'Tecnico non trovato.');
        }

        if ($technician->is_admin) {
            return Redirect::back()->with('error', 'Il tecnico è già un amministratore.');
        }

        DB::beginTransaction();

        try {
            $technician->is_admin = true;
            $technician->save();
            DB::commit();
            return Redirect::back()->with('success', 'Tecnico promosso ad amministratore con successo!');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Errore durante la promozione del tecnico: ' . $e->getMessage());
        }
    }

    public function adminToTechnician(Request $request)
    {
        $authTechnician = auth()->guard()->user();

        if (!$authTechnician || !$authTechnician->is_superadmin) {
            return Redirect::back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        }

        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);

        $technician = Technician::find($request->technician_id);

        if (!$technician) {
            return Redirect::back()->with('error', 'Tecnico non trovato.');
        }

        if (!$technician->is_admin) {
            return Redirect::back()->with('error', 'Il tecnico non è un amministratore.');
        }

        if ($technician->is_superadmin) {
            return Redirect::back()->with('error', 'Impossibile degradare il super amministratore a tecnico.');
        }

        DB::beginTransaction();

        try {
            $technician->is_admin = false;
            $technician->save();
            DB::commit();
            return Redirect::back()->with('success', 'Amministratore degradato a tecnico con successo!');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Errore durante la degradazione dell\'amministratore: ' . $e->getMessage());
        }
    }


    private function getUsers()
    {
        return User::where('is_technician', false)->get();
    }
}
