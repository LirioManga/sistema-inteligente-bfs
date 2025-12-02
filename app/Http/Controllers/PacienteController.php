<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    //

    public function store(Request $request)
    {
        try {

            $paciente = User::create([
                'name'  => $request->input('name'),
                'email' => $request->input('email'),
                'role'  => 'paciente',
                'password' => $request->input('email'), 
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paciente registado com sucesso',
                'paciente' => $paciente
            ]);

        } catch (Exception $e) {
             return response()->json([
                'success' => false,
                'message' => 'Erro ao registar paciente' . $e->getMessage(),
            ]);
        }
    }
}
