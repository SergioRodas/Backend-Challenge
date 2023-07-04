<!DOCTYPE html>
<html>
<head>
    <title>API Mutante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>API Mutante</h1>
    <p>Bienvenido a la API Mutante. Utiliza los siguientes enlaces para acceder a las funciones:</p>
    <ul>
        <li><a href="{{ route('stats') }}">Estadísticas</a></li>
        <li><a href="{{ route('mutationCheck') }}">Verificar Mutación</a></li>
    </ul>
</body>
</html>
