<?php

namespace App\Http\Controllers;

use App\Models\Triagem;
use App\Services\BFSService;
use Exception;
use Illuminate\Http\Request;

class TriagemController extends Controller
{
    //

    public function triagemAutomatica(Request $request, BFSService $bfs)
    {
        $categoria = $request->input('categoria');
        $gravidade = $request->input('gravidade');

        $departamento = $bfs->buscarDepartamentoInteligente($categoria, $gravidade);

        return response()->json([
            'categoria' => $categoria,
            'gravidade' => $gravidade,
            'departamento' => $departamento
        ]);
    }

    public function atualizarDepartamento(Request $request, $id)
    {
        try {
            $triagem = Triagem::findOrFail($id);

            $triagem->update([
                'departamento' => $request->input('departamento')
            ]);

            return response()->json([
                'success'  => true,
                'mensagem' => 'Departamento atualizado com sucesso!',
                'triagem' => $triagem
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success'  => false,
                'mensagem' => 'Erro ao actualizar o departamento' . $e->getMessage(),
            ]);
        }
    }

    public function listar()
    {
        // Carrega todas triagens e o paciente associado
        $triagens = Triagem::with('paciente:id,name,email')->get();

        return response()->json($triagens);
    }
}
