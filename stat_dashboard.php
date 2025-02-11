<?php include('header/header_stat_dashboard.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-white p-6 text-gray-800">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Statistiques Générales</h1>
            <button id="theme-toggle" class="p-2 bg-gray-200 rounded-full">
                <svg id="theme-icon" class="w-6 h-6 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-4.34l-.707.707m-13.314 0l-.707-.707M3 12H2m16 0h1M4.34 4.34l.707.707m13.314 0l.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z"></path>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl">Montant Total Facturé</h2>
                <p class="text-2xl font-bold">
                    <?php echo number_format($totalMontantDevis, 0, ',', ' ') . ' XOF'; ?>
                </p>
            </div>
            <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl">Total Dépenses</h2>
                <p class="text-2xl font-bold">
                    <?php echo number_format($totalDepenses, 0, ',', ' ') . ' XOF'; ?>
                </p>
            </div>
            <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl">Taux de Rentabilité</h2>
                <p class="text-2xl font-bold"><? $tauxRentabilite ?>%</p>
            </div>
        </div>

        <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Détails par Chantier</h2>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="p-2 border-b">#</th>
                        <th class="p-2 border-b">Chantier</th>
                        <th class="p-2 border-b">Montant Facturé</th>
                        <th class="p-2 border-b">Montant Dépensé</th>
                        <th class="p-2 border-b">État d'avancement</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($chantiers as $chantier) {
                        $chantierId = $chantier['id_chantier'];
                        $montantFacture = $chantier['montant_devis'] ?? 0; // Si null, utiliser 0
                        $montantDepense = (new Depense($pdo))->getTotalDepensesParChantier($chantier['id_chantier']) ?? 0; // Si null, utiliser 0

                        // Calculer l'état d'avancement (pourcentage)
                        $avancement = 0;
                        if ($montantFacture > 0) {
                            $avancement = ($montantFacture > 0) ? ($montantDepense / $montantFacture) * 100 : 0;
                        }

                        // Déterminer la couleur en fonction de l'avancement
                        $avancementClass = 'bg-gray-300'; // par défaut
                        if ($avancement >= 80) {
                            $avancementClass = 'bg-green-500';
                        } elseif ($avancement >= 50) {
                            $avancementClass = 'bg-blue-500';
                        } elseif ($avancement >= 30) {
                            $avancementClass = 'bg-yellow-500';
                        } else {
                            $avancementClass = 'bg-red-500';
                        }

                        // Affichage dans le tableau
                        echo "
                        <tr>
                            <td class='p-2 border-b'>{$chantier['num_chantier']}</td>
                            <td class='p-2 border-b'>{$chantier['lib_chantier']}</td>
                            <td class='p-2 border-b'>" . number_format($montantFacture, 0, ',', ' ') . " XOF</td>
                            <td class='p-2 border-b'>" . number_format($montantDepense, 0, ',', ' ') . " XOF</td>
                            <td class='p-2 border-b'>
                                <div class='w-full bg-gray-300 rounded-full h-4'>
                                    <div class='{$avancementClass} h-4 rounded-full' style='width: {$avancement}%;'></div>
                                </div>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
            <canvas id="chartDepenses"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('chartDepenses').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Chantier A', 'Chantier B', 'Chantier C'],
                datasets: [{
                        label: 'Montant Facturé',
                        data: [120000, 200000, 130000],
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        barThickness: 30 // Ajuste la largeur des barres
                    },
                    {
                        label: 'Montant Dépensé',
                        data: [90000, 150000, 110000],
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        barThickness: 30
                    },
                    {
                        label: 'Marge (Bénéfice)',
                        data: [30000, 50000, 20000], // Facturé - Dépenses
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        barThickness: 30
                    },
                    {
                        label: 'État d\'avancement (%)',
                        data: [75, 80, 60],
                        borderColor: 'rgba(255, 205, 86, 1)',
                        borderWidth: 2,
                        type: 'line',
                        yAxisID: 'y-axis-percentage',
                        tension: 0.3,
                        pointRadius: 5,
                        pointBackgroundColor: 'rgba(255, 205, 86, 1)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toLocaleString() + ' XOF';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: false, // Les barres seront côte à côte
                        barPercentage: 0.8 // Ajuste l'espacement entre les barres
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Montant (XOF)'
                        }
                    },
                    'y-axis-percentage': {
                        position: 'right',
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'État d\'avancement (%)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>