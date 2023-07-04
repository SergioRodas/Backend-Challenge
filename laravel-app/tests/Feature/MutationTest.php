<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MutationTest extends TestCase
{
    use WithFaker;

   /**
     * Verifica si se detecta una mutación en la secuencia.
     *
     * @return void
     */
    public function testMutationDetection()
    {
        // Array de secuencias de ARN a probar
        $sequences = [
            'AUGAUCUCG',
            'AUUGGAUCCGCUUCGA',
            'AAAAAACCCGGGAUUU',
            'AUUA'
        ];

        foreach ($sequences as $sequence) {
            // Hacer la petición POST a /api/mutant con la secuencia actual
            $response = $this->postJson('/api/mutant', ['sequence' => $sequence]);

            // Verificar que se reciba una respuesta con código de estado 200 y el mensaje esperado
            $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Se ha detectado una mutación en la secuencia.'
                ]);
        }
    }



    /**
     * Verifica que la secuencia no sea mutante.
     *
     * @return void
     */
    public function testNotMutantSequence()
    {
        // Array de secuencias de ARN a probar
        $sequences = [
            'AUUGGAUCCGCUGCGA',
            'AAAAAAAAA',
            'ACAUGACCC'
        ];

        foreach ($sequences as $sequence) {
            // Hacer la petición POST a /api/mutant con la secuencia generada
            $response = $this->postJson('/api/mutant', ['sequence' => $sequence]);

            // Verificar que se reciba una respuesta con código de estado 200 y el mensaje esperado
            $response->assertStatus(200)
                ->assertJson([
                    'message' => 'La secuencia no es mutante.'
                ]);
        }
    }


    /**
     * Verifica la respuesta en caso de enviar una secuencia que contenga caracteres inválidos.
     *
     * @return void
     */
    public function testInvalidSequence()
    {
        // Array de secuencias inválidas
        $sequences = [
            '123asdAUG',
            'AUGCCCBUT',
            '@#$%^&*()',
            'UCG!123AAA',
            ''
        ];

        foreach ($sequences as $sequence) {
            // Hacer la petición POST a /api/mutant con la secuencia actual
            $response = $this->postJson('/api/mutant', ['sequence' => $sequence]);

            // Verificar que se reciba una respuesta con código de estado 400 y el mensaje esperado
            $response->assertStatus(400)
                ->assertJson([
                    'message' => 'La secuencia contiene caracteres inválidos. Solo se permiten las bases nitrogenadas del ARN A, U, C y G.'
                ]);
        }
    }



    /**
     * Verifica la respuesta en caso de enviar una secuencia que exceda los 10,000 nucleótidos.
     *
     * @return void
     */
    public function testSequenceExceedsLimit()
    {
        // Array de secuencias que exceden los 10,000 nucleótidos
        $sequences = [
            $this->generateLongSequence(10001),
            $this->generateLongSequence(10002),
            $this->generateLongSequence(10100),
            $this->generateLongSequence(10019),
        ];

        foreach ($sequences as $sequence) {
            // Hacer la petición POST a /api/mutant con la secuencia actual
            $response = $this->postJson('/api/mutant', ['sequence' => $sequence]);

            // Verificar que se reciba una respuesta con código de estado 400 y el mensaje esperado
            $response->assertStatus(400)
                ->assertJson([
                    'message' => 'La secuencia excede los 10.000 nucleótidos.'
                ]);
        }
    }

    /**
     * Genera una secuencia de ARN con la longitud especificada.
     *
     * @param int $length Longitud de la secuencia
     * @return string Secuencia de ARN generada
     */
    private function generateLongSequence($length)
    {
        $nucleotides = ['A', 'U', 'C', 'G'];
        $sequence = '';

        for ($i = 0; $i < $length; $i++) {
            $sequence .= $this->faker->randomElement($nucleotides);
        }

        return $sequence;
    }


    /**
     * Verifica la respuesta en caso de que la cantidad de nucleótidos de la secuencia sea inválida.
     *
     * @return void
     */
    public function testInvalidSequenceLength()
    {
        // Array de secuencias con longitudes inválidas (la raíz cuadrada de la longitud tiene que ser un número entero)
        $sequences = [
            'AUCAUGUGUU', // Longitud: 10
            'AA', // Longitud: 2
            'UUUUA', // Longitud: 5
            'AUCGCUAUUCCC' // Longitud: 12
        ];

        foreach ($sequences as $sequence) {
            // Hacer la petición POST a /api/mutant con la secuencia actual
            $response = $this->postJson('/api/mutant', ['sequence' => $sequence]);

            // Verificar que se reciba una respuesta con código de estado 400 y el mensaje esperado
            $response->assertStatus(400)
                ->assertJson([
                    'message' => 'La cantidad de nucleótidos de la cadena es inválida. No se puede calcular si es mutante o no.'
                ]);
        }
    }

}
