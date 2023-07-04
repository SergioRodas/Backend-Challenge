# Backend-Challenge

Este proyecto se realiza para resolver un challenge de backend. El enunciado del mismo es este:

> ![Captura de pantalla 2023-07-04 a la(s) 00 42 07](https://github.com/SergioRodas/Backend-Challenge/assets/56599469/5dfa2cea-6a22-43a1-8c8b-7537e8d1c518) 

## Objetivo

El objetivo principal de esta solución consiste en poder automatizar el proceso de detección de mutaciones a través de una cadena de ARN.
Necesitamos diseñar e implementar una función que reciba las secuencias de nucleótidos y nos determine si la misma pertenece a un mutante o no.

Crear un API REST que:
- Reciba la cadena e indique si es un mutante o no.
- Permite obtener una estadística de las cadenas analizadas para poder sacar un porcentaje de cuántas de ellas corresponden a la de un mutante.

## Herramientas
 - Lenguaje elegido: PHP
 - Framework: Laravel
 - Persistencia de datos: MySQL
 - Entorno: Docker
 - Pruebas: PHPUnit

## Cómo levantarlo

1. Clonar el repositorio.
2. Tener instalado Docker.
4. Crear un archivo `.env` dentro de la carpeta `laravel-app` copiando la información del archivo `.env.example` que se ubica en esa carpeta.
5. En la raíz del proyecto, ejecutar el comando `make setup` para levantar el contenedor y sus servicios, además de instalar las dependencias de Laravel (para ver las instrucciones que ejecuta por detrás este comando, revisar el archivo `Makefile`).
6. Ejecutar `make data` también en la raíz del proyecto para correr las migraciones y crear las tablas necesarias en la base de datos.
7. Luego de esto se podrá visualizar:
   - La aplicación de Laravel en [http://localhost:9000/](http://localhost:9000/)
   - PHPmyAdmin en [http://localhost:9001/](http://localhost:9001/). Para acceder a la base de datos, el HOST es "mysql_db", user "root" y password "root".

## Endpoints de la API

- `GET /api/stats` - Devuelve la cantidad de secuencias totales analizadas, la cantidad de secuencias con mutación y la cantidad de secuencias sin mutación.
  La respuesta tiene el siguiente formato:
  ```json
  {
    "count_sequences_with_mutation": 5,
    "count_sequences_without_mutation": 3,
    "total_sequences_count": 8,
    "mutation_percentage": 62.5,
    "non_mutation_percentage": 37.5
  }
  ```
- `POST /api/mutant/{sequence}` - Endpoint POST para enviar una secuencia de ARN y ver si contiene o no mutación.
La respuesta tiene el siguiente formato:
  ```json
  {
    "message": "mensaje de ejemplo"
  }
  ```
    El status varía dependiendo de los parámetros ingresados. En caso de que se envíen caracteres incorrectos o una cadena de longitud mayor a 10.000, se devolverá status 400 y un mensaje descriptivo del error en la petición.

## Vistas

Hay 3 vistas disponibles para probar y visualizar los endpoints de una manera más amigable:

- `/home` [http://localhost:9000/](http://localhost:9000/)
- `/stats` [http://localhost:9000/stats](http://localhost:9000/stats)
- `/mutationCheck` [http://localhost:9000/mutationCheck](http://localhost:9000/mutationCheck)

## Rutas y controlador

Las rutas se encuentran definidas en los siguientes archivos:
- `laravel-app/routes/api.web`
- `laravel-app/routes/web.php`

La lógica de las soluciones se encuentra en el controlador:
- `laravel-app/app/Http/Controllers/MutantController.php`

  
## Modelo NucleotideSequence

El modelo `NucleotideSequence` representa una secuencia de nucleótidos en la base de datos. Está definido en el archivo `laravel-app/app/Models/NucleotideSequence.php`. Este modelo se utiliza para almacenar y administrar las secuencias de ARN analizadas en el laboratorio.

### Tabla

El modelo `NucleotideSequence` está asociado a la tabla `nucleotide_sequences` en la base de datos.

### Atributos

El modelo `NucleotideSequence` tiene los siguientes atributos:

- `sequence`: Representa la secuencia de nucleótidos de ARN.
- `has_mutation`: Indica si la secuencia de nucleótidos tiene una mutación.


## Pruebas

Para los tests se utilizó PHPUnit y se encuentran en el archivo:
- `laravel-app/tests/Feature/MutationTest.php`

Los casos de prueba incluyen secuencias con mutación, secuencias sin mutación, exceso de límite de nucleótidos, caracteres inválidos y longitud de cadena sin raíz cuadrada entera. En los tests se evalúa el status y el mensaje de la respuesta. Para ejecutar las pruebas, ejecuta el siguiente comando en la raíz del proyecto:
```bash
make test
