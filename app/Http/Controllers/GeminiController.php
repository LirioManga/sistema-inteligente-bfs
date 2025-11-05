<?php

namespace App\Http\Controllers;

use App\Services\BFSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeminiController extends Controller
{
    //

    // public function interpretar(Request $request)
    // {
    //     $texto = $request->input('texto');

    //     if (!$texto) {
    //         return response()->json(['erro' => 'Por favor, envie o texto dos sintomas.'], 400);
    //     }

    //     try {
    //         $url = env('GEMINI_URL');
    //         $apiKey = env('GEMINI_API_KEY');

    //         $response = Http::withHeaders([
    //             'Content-Type' => 'application/json',
    //             'X-goog-api-key' => $apiKey,
    //         ])->post($url, [
    //             'contents' => [[
    //                 'parts' => [[
    //                     'text' => "Analisa os seguintes sintomas e retorna **apenas JSON** no formato:
    //                             { \"categoria\": \"<setor hospitalar>\", \"gravidade\": \"<baixa|media|alta>\" }

    //                             Sintomas: $texto

    //                             Não adiciones explicações, texto extra, nem markdown. Apenas devolve o JSON."

    //                 ]]
    //             ]]
    //         ]);

    //         $data = $response->json();


    //         $output = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
    //         $json = json_decode($output, true);

    //         return response()->json([
    //             'categoria' => $json['categoria'] ?? 'geral',
    //             'gravidade' => $json['gravidade'] ?? 'baixa',
    //             'raw' => $output
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['erro' => 'Erro ao conectar à API: ' . $e->getMessage()], 500);
    //     }
    // }


    public function interpretar(Request $request, BFSService $bfs)
    {
        $texto = $request->input('texto');

        if (!$texto) {
            return response()->json(['erro' => 'Por favor, descreva o que sente.'], 400);
        }

        try {
            $url = env('GEMINI_URL');
            $apiKey = env('GEMINI_API_KEY');

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-goog-api-key' => $apiKey,
            ])->post($url, [
                'contents' => [[
                    'parts' => [[
                        'text' => "Tu és um agente de triagem hospitalar. Responde APENAS com JSON válido no formato:
                                { \"categoria\": \"<setor hospitalar>\", \"gravidade\": \"<baixa|media|alta>\" }
                                Não acrescentes texto, listas, nem markdown. Sintomas: $texto"
                    ]]
                ]]
            ]);

            $data = $response->json();


            $output = $data['candidates'][0]['content']['parts'][0]['text'] ?? ($data['output'] ?? '{}');

            // 1) Remover blocos de markdown ```json ... ``` ou ``` ... ```
            $clean = preg_replace('/```(?:json)?\s*/i', '', $output);
            $clean = preg_replace('/\s*```/i', '', $clean);


            $clean = trim($clean);


            $jsonText = null;
            if (preg_match('/(\{(?:[^{}]|(?R))*\}|\[(?:[^\[\]]|(?R))*\])/s', $clean, $matches)) {
                $jsonText = $matches[1];
            } else {

                if (strpos($clean, '{') !== false && strrpos($clean, '}') !== false) {
                    $jsonText = substr($clean, strpos($clean, '{'), strrpos($clean, '}') - strpos($clean, '{') + 1);
                } else {
                    $jsonText = $clean;
                }
            }

            $jsonText = preg_replace('/,(\s*[\]\}])/s', '$1', $jsonText);
            $decoded = json_decode($jsonText, true);


            if (json_last_error() !== JSON_ERROR_NONE) {
                $decoded = json_decode($clean, true);
            }


            if (!is_array($decoded) || empty($decoded)) {

                return response()->json([
                    'categoria' => 'geral',
                    'gravidade' => 'baixa',
                    'raw' => $output
                ]);
            }

            $categoria = null;
            $gravidade = null;

            $normalizeSeverity = function ($s) {
                if (!$s) return 'baixa';
                $s = mb_strtolower(trim($s));
                if (str_contains($s, 'alta')) return 'alta';
                if (str_contains($s, 'media') || str_contains($s, 'média')) return 'media';
                return 'baixa';
            };

            if (isset($decoded['categoria']) && isset($decoded['gravidade'])) {
                $categoria = mb_strtolower(trim($decoded['categoria']));
                $gravidade = $normalizeSeverity($decoded['gravidade']);
            } else {

                $items = $decoded;
                if (array_values($items) === $items) {

                    $chosen = null;
                    $priorities = ['alta' => 3, 'media' => 2, 'baixa' => 1];
                    $bestScore = 0;

                    foreach ($items as $it) {
                        if (!is_array($it)) continue;
                        $itCategoria = $it['categoria'] ?? null;
                        $itGrav = $normalizeSeverity($it['gravidade'] ?? null);
                        $score = $priorities[$itGrav] ?? 0;
                        if ($score > $bestScore) {
                            $bestScore = $score;
                            $chosen = ['categoria' => $itCategoria, 'gravidade' => $itGrav];
                        }
                    }

                    if ($chosen) {
                        $categoria = mb_strtolower(trim($chosen['categoria']));
                        $gravidade = $chosen['gravidade'];
                    } else {
                        $first = $items[0];
                        $categoria = isset($first['categoria']) ? mb_strtolower(trim($first['categoria'])) : 'geral';
                        $gravidade = $normalizeSeverity($first['gravidade'] ?? null);
                    }
                } else {

                    $categoria = mb_strtolower(trim($decoded['categoria'] ?? 'geral'));
                    $gravidade = $normalizeSeverity($decoded['gravidade'] ?? null);
                }
            }

            $categoria = $categoria ?: 'geral';
            $gravidade = $gravidade ?: 'baixa';
            $departamento = $bfs->buscarDepartamentoInteligente($categoria, $gravidade);

            return response()->json([
                'categoria' => $categoria,
                'gravidade' => $gravidade,
                'mensagem' => 'Caro paciente, dirija-se ao sector de ' . $departamento,
                'raw' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json(['erro' => 'Erro ao conectar à API: ' . $e->getMessage()], 500);
        }
    }
}
