<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 p-6 dark:bg-gray-900 dark:text-white">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Statistiques Générales</h1>
            <button id="theme-toggle" class="p-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                <svg id="theme-icon" class="w-6 h-6 text-black dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-4.34l-.707.707m-13.314 0l-.707-.707M3 12H2m16 0h1M4.34 4.34l.707.707m13.314 0l.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z"></path>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl">Montant Total Facturé</h2>
                <p class="text-2xl font-bold">450,000 XOF</p>
            </div>
            <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl">Total Dépenses</h2>
                <p class="text-2xl font-bold">350,000 XOF</p>
            </div>
            <div class="bg-red-500 text-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl">Taux de Rentabilité</h2>
                <p class="text-2xl font-bold">78%</p>
            </div>
        </div>

        <div class="mt-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Détails par Chantier</h2>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="p-2 border-b">Chantier</th>
                        <th class="p-2 border-b">Montant Facturé</th>
                        <th class="p-2 border-b">Montant Dépensé</th>
                        <th class="p-2 border-b">État d'avancement</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-2 border-b">Chantier A</td>
                        <td class="p-2 border-b">120,000 XOF</td>
                        <td class="p-2 border-b">90,000 XOF</td>
                        <td class="p-2 border-b">
                            <div class="w-full bg-gray-300 rounded-full h-4">
                                <div class="bg-blue-500 h-4 rounded-full" style="width: 75%;"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 border-b">Chantier B</td>
                        <td class="p-2 border-b">200,000 XOF</td>
                        <td class="p-2 border-b">150,000 XOF</td>
                        <td class="p-2 border-b">
                            <div class="w-full bg-gray-300 rounded-full h-4">
                                <div class="bg-green-500 h-4 rounded-full" style="width: 80%;"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 border-b">Chantier C</td>
                        <td class="p-2 border-b">130,000 XOF</td>
                        <td class="p-2 border-b">110,000 XOF</td>
                        <td class="p-2 border-b">
                            <div class="w-full bg-gray-300 rounded-full h-4">
                                <div class="bg-red-500 h-4 rounded-full" style="width: 60%;"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
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
                    label: 'Dépenses',
                    data: [90000, 150000, 110000],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                }]
            }
        });

        const themeToggle = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;

        themeToggle.addEventListener('click', () => {
            if (htmlElement.classList.contains('dark')) {
                htmlElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                htmlElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });

        if (localStorage.getItem('theme') === 'dark') {
            htmlElement.classList.add('dark');
        }
    </script>
</body>

</html>