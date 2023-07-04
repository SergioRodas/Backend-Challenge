<!DOCTYPE html>
<html>
<head>
    <title>Verificar Mutación</title>
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

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 10px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .response {
            max-width: 400px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Verificar Mutación</h1>
    <p>Ingresa la secuencia de ARN para verificar si tiene mutación:</p>

    <form id="sequenceForm">
        <input type="text" id="sequenceInput" placeholder="Secuencia de ARN" required>
        <div class="button-container">
            <button type="submit">Verificar</button>
            <button type="button" id="clearButton">Vaciar</button>
        </div>
    </form>

    <div id="responseContainer" style="display: none;">
        <h2>Respuesta:</h2>
        <div id="responseContent" class="response"></div>
    </div>

    <p><a href="{{ route('home') }}">Volver al Home</a></p>

    <script>
        const sequenceForm = document.getElementById('sequenceForm');
        const sequenceInput = document.getElementById('sequenceInput');
        const clearButton = document.getElementById('clearButton');
        const responseContainer = document.getElementById('responseContainer');
        const responseContent = document.getElementById('responseContent');

        sequenceForm.addEventListener('submit', e => {
            e.preventDefault();
            const sequence = sequenceInput.value.trim();
            if (sequence) {
                fetch('/api/mutant', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ sequence })
                })
                .then(response => response.json())
                .then(data => {
                    responseContent.textContent = JSON.stringify(data.message, null, 2);
                    responseContainer.style.display = 'block';
                })
                .catch(error => {
                    responseContent.textContent = 'Error: ' + error.message;
                    responseContainer.style.display = 'block';
                });
            }
        });

        clearButton.addEventListener('click', () => {
            sequenceInput.value = '';
            responseContainer.style.display = 'none';
        });
    </script>
</body>
</html>
