<?php

namespace App\Http\Controllers;


use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;


class AdminController extends Controller
{

    public function technicianToAdmin(Request $request)
    {
        /*  $authTechnician = auth()->guard()->user();

        if (!$authTechnician || !$authTechnician->is_superadmin) {
            return Redirect::back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        } */

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
        /* $authTechnician = auth()->guard()->user();

        if (!$authTechnician || !$authTechnician->is_superadmin) {
            return Redirect::back()->with('error', 'Non sei autorizzato a eseguire questa operazione.');
        } */

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
