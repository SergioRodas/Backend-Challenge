<!DOCTYPE html>
<html>
<head>
    <title>Estadísticas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .chart-container {
            max-width: 400px;
            margin-top: 20px;
            height: 400px; /* Altura fija para el contenedor del gráfico */
        }

        .link-home {
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
    <h1>Estadísticas de Secuencias</h1>
    <p>Aquí puedes ver las estadísticas de las secuencias analizadas:</p>

    <div class="chart-container">
        <canvas id="statsChart"></canvas>
    </div>

    <a class="link-home" href="{{ route('home') }}">Volver al Home</a>

    <script>
        // Obtener los datos de las estadísticas desde el endpoint
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                const statsChart = document.getElementById('statsChart').getContext('2d');

                // Crear el gráfico de torta
                new Chart(statsChart, {
                    type: 'pie',
                    data: {
                        labels: ['Secuencias con mutación', 'Secuencias sin mutación'],
                        datasets: [{
                            data: [data.count_sequences_with_mutation, data.count_sequences_without_mutation],
                            backgroundColor: ['#007bff', '#28a745']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            });
    </script>
</body>
</html>
