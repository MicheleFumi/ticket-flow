<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validazione dei dati
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'telefono' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Creazione utente
        $user = User::create([
            'name' => $request->name,
            'cognome' => $request->cognome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
        ]);

        // Generazione del token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Risposta
        return response()->json([
            'message' => 'Utente registrato con successo!',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
}
