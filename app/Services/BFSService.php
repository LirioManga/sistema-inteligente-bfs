<?php

namespace App\Services;

use App\Models\NoTriagem;
use Illuminate\Support\Facades\Log;

class BFSService
{
    /**
     * Create a new class instance.
     */
    // public function __construct()
    // {
    //     //
    // }

    public function buscarDepartamentoInteligente(string $categoria, string $gravidade)
    {

        Log::info("dados recebidos $categoria, $gravidade");
        $fila = [NoTriagem::find(1)];
        $visitados = [];

        while (!empty($fila)) {
            Log::info("filas: ", $fila);
            $nodo = array_shift($fila);
            if (!$nodo || in_array($nodo->id, $visitados)) continue;

            $visitados[] = $nodo->id;

            if ($nodo->departamento_id) {
                return $nodo->departamento->nome ?? 'Departamento não identificado';
            }

            $proximo = null;

            if ($this->respostaSim($nodo->pergunta, $categoria, $gravidade)) {
                $proximo = $nodo->sim;
            } else {
                $proximo = $nodo->nao;
            }

            if ($proximo) {
                $fila[] = NoTriagem::find($proximo);
            }
        }

        return "Nenhum departamento encontrado";
    }

    private function respostaSim(?string $pergunta, string $categoria, string $gravidade): bool
    {
        $pergunta = strtolower($pergunta ?? '');
        $categoria = strtolower($categoria ?? '');
        $gravidade = strtolower($gravidade ?? '');

        Log::info("pergunta: $pergunta, categoria: $categoria e gravidade: $gravidade");
        // Emergência: febre alta, dificuldade respiratória, dor intensa
        if (str_contains($pergunta, 'febre') && $gravidade === 'alta') {
            return true;
        }

        if (str_contains($pergunta, 'dificuldade respiratória') && $gravidade !== 'baixa') {
            return true;
        }

        // Crianças → Pediatria
        if (str_contains($pergunta, 'criança') && str_contains($categoria, 'pedi')) {
            return true;
        }

        // Casos leves → Clínica Geral
        if (str_contains($pergunta, 'geral') || str_contains($pergunta, 'leve')) {
            if ($gravidade === 'baixa' && (
                str_contains($categoria, 'geral') ||
                str_contains($categoria, 'médica') ||
                str_contains($categoria, 'clínica')
            )) {
                return true;
            }
        }

        return false;
    }
}
