<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'La password attuale non è corretta.'], 403);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password aggiornata con successo!'], 200);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->tickets()->delete();
        $user->tokens()->delete();
        $user->forceDelete();

        return response()->json([
            'message' => 'Account eliminato con successo!'
        ], 200);
    }
}
