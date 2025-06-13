<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class TechnicianController extends Controller
{
    public function index()
    {
        $technicians = Technician::all();
        $allTechnicians = Technician::withoutGlobalScopes()->get();
        $users = User::all();
        $nonAdminTechnicians = Technician::where('is_admin', false)->get();
        $adminTechnicians = Technician::where('is_admin', true)->where("is_superadmin", false)->get();
        // dd($allTechnicians);
        return view('technicians.index', compact('technicians', "allTechnicians", 'users', 'nonAdminTechnicians', 'adminTechnicians'));
    }

    public function create(Request $request)
    {
        $admin = auth()->guard()->user();

        if (!$admin->is_admin) {
            return Redirect::back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:technicians,email',
            'email_confirmation' => 'required|email|max:255|same:email',
            'telefono' => 'nullable|string|max:20',
        ]);

        $exists = Technician::withoutGlobalScopes()
            ->where('email', $validated['email'])
            ->exists();

        if ($exists) {
            return Redirect::back()->with('error', 'Esiste già un tecnico con questa email.');
        }

        DB::beginTransaction();

        try {

            $randomPassword = Str::random(12);

            Technician::create([
                'nome' => $validated['nome'],
                'cognome' => $validated['cognome'],
                'email' => $validated['email'],
                'password' => Hash::make($randomPassword),
                'telefono' => $validated['telefono'],
                'is_admin' => false,
                'is_superadmin' => false,
                'is_available' => true,
                'still_active' => true,
            ]);

            DB::commit();

            return Redirect::back()->with('success', 'Tecnico creato con successo!');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Errore durante la creazione del tecnico: ' . $e->getMessage());
        }
    }


    public function destroy(Request $request)
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

            $technician->still_active = false;
            $technician->save();

            DB::commit();

            return Redirect::back()->with('success', 'Tecnico rimosso con successo!');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Errore durante la rimozione del tecnico: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $superadmin = auth()->guard()->user();

        if (!$superadmin || !$superadmin->is_superadmin) {
            return Redirect::back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        }

        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
        ]);

        $technician = Technician::withoutGlobalScopes()->find($request->technician_id);

        if (!$technician) {
            return Redirect::back()->with('error', 'Tecnico non trovato.');
        }

        DB::beginTransaction();

        try {
            $technician->still_active = true;
            $technician->is_available = true;
            $technician->save();
            DB::commit();
            return Redirect::back()->with('success', 'Tecnico ripristinato con successo!');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('error', 'Errore durante il ripristino del tecnico: ' . $e->getMessage());
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
}
