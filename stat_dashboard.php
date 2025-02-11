<?php include('header/header_stat_dashboard.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Inclure Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Inclure DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Inclure DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


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
            <canvas id="chartDepenses"></canvas>
        </div>


        <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Détails par Chantier</h2>
            <table id="chantierTable" class="w-full text-left border-collapse">
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
    </div>

    <?php
    $depenseObj = new Depense($pdo);
    ?>

    <script>
        const ctx = document.getElementById('chartDepenses').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php
                        // Utilisation de json_encode pour encoder correctement les noms des chantiers en JSON
                        $labels = array_map(function ($chantier) {
                            return $chantier['lib_chantier'];
                        }, $chantiers);
                        echo json_encode($labels);
                        ?>,
                datasets: [{
                        label: 'Montant Facturé',
                        data: <?php
                                // Utilisation de json_encode pour les montants facturés
                                $factureData = array_map(function ($chantier) {
                                    return $chantier['montant_devis'] ?? 0;
                                }, $chantiers);
                                echo json_encode($factureData);
                                ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        barThickness: 30
                    },
                    {
                        label: 'Montant Dépensé',
                        data: <?php
                                // Utilisation de json_encode pour les montants dépensés
                                $depenseData = array_map(function ($chantier) use ($depenseObj) {
                                    return $depenseObj->getTotalDepensesParChantier($chantier['id_chantier']) ?? 0;
                                }, $chantiers);
                                echo json_encode($depenseData);
                                ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        barThickness: 30
                    },
                    {
                        label: 'Marge (Bénéfice)',
                        data: <?php
                                // Utilisation de json_encode pour les marges calculées
                                $margeData = array_map(function ($chantier) use ($depenseObj) {
                                    $montantFacture = $chantier['montant_devis'] ?? 0;
                                    $montantDepense = $depenseObj->getTotalDepensesParChantier($chantier['id_chantier']) ?? 0;
                                    return $montantFacture - $montantDepense;
                                }, $chantiers);
                                echo json_encode($margeData);
                                ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        barThickness: 30
                    },
                    {
                        label: 'État d\'avancement (%)',
                        data: <?php
                                // Utilisation de json_encode pour les avancements calculés
                                $avancementData = array_map(function ($chantier) use ($depenseObj) {
                                    $montantFacture = $chantier['montant_devis'] ?? 0;
                                    $montantDepense = $depenseObj->getTotalDepensesParChantier($chantier['id_chantier']) ?? 0;
                                    return ($montantFacture > 0) ? ($montantDepense / $montantFacture) * 100 : 0;
                                }, $chantiers);
                                echo json_encode($avancementData);
                                ?>,
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
                        stacked: false,
                        barPercentage: 0.8
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

    <script>
        $(document).ready(function() {
            $('#chantierTable').DataTable({
                dom: 'Bfrtip', // Permet de placer les boutons d'exportation
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Exporter Excel',
                        className: 'bg-green-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300',
                        title: 'Détails par Chantier'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> Exporter PDF',
                        className: 'bg-red-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300',
                        title: 'Détails par Chantier'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fas fa-file-csv"></i> Exporter CSV',
                        className: 'bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300',
                        title: 'Détails par Chantier'
                    }
                ],
                responsive: true, // Active la responsivité pour masquer les colonnes sur petit écran
                language: {
                    search: "Recherche:", // Texte du champ de recherche
                    lengthMenu: "Afficher _MENU_ lignes par page",
                    info: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    paginate: {
                        previous: "Précédent",
                        next: "Suivant"
                    }
                }
            });
        });
    </script>



</body>

</html>