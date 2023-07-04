<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NucleotideSequence;

class MutantController extends Controller
{
    public function isMutant(Request $request)
    {
        $sequence = $request->input('sequence');

        // Dependiendo la especie, esta cadena puede tener una longitud de hasta 10.000 N.
        if (strlen($sequence) > 10000) {
            return response()->json([
                'message' => 'La secuencia excede los 10.000 nucleótidos.'
            ], 400);
        }

        // Verificar si la secuencia contiene caracteres inválidos
        if (!preg_match('/^[AUCG]+$/i', $sequence)) {
            return response()->json([
                'message' => 'La secuencia contiene caracteres inválidos. Solo se permiten las bases nitrogenadas del ARN A, U, C y G.'
            ], 400);
        }


        try {
            // Verificar si es mutante o no
            $isMutant = $this->checkMutant($sequence);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }

        // Verifica si la secuencia ya existe, si no existe la registra
        $nucleotideSequence = NucleotideSequence::firstOrCreate(
            ['sequence' => $sequence],
            ['has_mutation' => $isMutant]
        );

        // Retornar respuesta
        if ($isMutant) {
            return response()->json([
                'message' => 'Se ha detectado una mutación en la secuencia.'
            ]);
        } else {
            return response()->json([
                'message' => 'La secuencia no es mutante.'
            ]);
        }
    }

    private function checkMutant($sequence)
    {
        $matrix = $this->createMatrix($sequence);
        $n = count($matrix);

        $principalDiagonal = '';
        $secondaryDiagonal = '';

        // Construir las diagonales principales
        for ($i = 0; $i < $n; $i++) {
            $principalDiagonal .= $matrix[$i][$i];
            $secondaryDiagonal .= $matrix[$n - $i - 1][$i];
        }
        // Verificar si alguna de las cuatro bases nitrogenadas de la secuencia de ARN (AUCG)
        // se repite un número par de veces en alguna de las diagonales principales
        return $this->hasEvenCount($principalDiagonal) || $this->hasEvenCount($secondaryDiagonal);
    }

    private function createMatrix($sequence)
    {
        $n = strlen($sequence);
        $matrixSize = sqrt($n);

        /// En caso de que el tamaño de la matriz no sea un número decimal devuelve un error.
        if (fmod($matrixSize, 1) != 0 || ($matrixSize * $matrixSize != $n)) {
            throw new \Exception('La cantidad de nucleótidos de la cadena es inválida. No se puede calcular si es mutante o no.', 400);
        }

        $matrixSize = intval($matrixSize);
        $matrix = [];

        for ($i = 0; $i < $n; $i += $matrixSize) {
            $matrix[] = str_split(substr($sequence, $i, $matrixSize));
        }

        return $matrix;
    }

    private function hasEvenCount($nucleotide)
    {
        $string = strtolower($nucleotide);
        $occurrences = array();

        // Obtengo la cantidad de ocurrencias de cada caracter
        for ($i = 0; $i < strlen($string); $i++) {
            $character = $string[$i];

            if (array_key_exists($character, $occurrences)) {
                $occurrences[$character]++;
            } else {
                $occurrences[$character] = 1;
            }
        }

        // Evalúo si la cantidad obtenida es par
        foreach ($occurrences as $character => $count) {
            if ($count % 2 === 0) {
                return true;
            }
        }

        return false;
    }

    // Permite obtener una estadística de las cadenas analizadas y el porcentaje de cantidad de mutaciones detectadas
    public function getStats()
    {
        $sequencesWithMutationCount = NucleotideSequence::where('has_mutation', true)->count();
        $sequencesWithoutMutationCount = NucleotideSequence::where('has_mutation', false)->count();

        $totalSequencesCount = $sequencesWithMutationCount + $sequencesWithoutMutationCount;
        $mutationPercentage = $totalSequencesCount > 0 ? ($sequencesWithMutationCount / $totalSequencesCount) * 100 : 0;
        $nonMutationPercentage = 100 - $mutationPercentage;

        return response()->json([
            'count_sequences_with_mutation' => $sequencesWithMutationCount,
            'count_sequences_without_mutation' => $sequencesWithoutMutationCount,
            'total_sequences_count' => $totalSequencesCount,
            'mutation_percentage' => $mutationPercentage,
            'non_mutation_percentage' => $nonMutationPercentage
        ]);
    }

}
