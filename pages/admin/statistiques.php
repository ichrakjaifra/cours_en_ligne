<?php include '../../includes/header.php'; ?>
<main class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-8">Statistiques globales</h1>
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-bold mb-4">Nombre total de cours</h2>
                    <p class="text-gray-600">20 cours</p>
                </div>
                <div>
                    <h2 class="text-xl font-bold mb-4">Nombre total d'utilisateurs</h2>
                    <p class="text-gray-600">100 utilisateurs</p>
                </div>
                <div>
                    <h2 class="text-xl font-bold mb-4">Top 3 enseignants</h2>
                    <ul class="list-disc list-inside text-gray-600">
                        <li>John Doe</li>
                        <li>Jane Smith</li>
                        <li>Alice Johnson</li>
                    </ul>
                </div>
                <div>
                    <h2 class="text-xl font-bold mb-4">Cours le plus populaire</h2>
                    <p class="text-gray-600">Introduction à la programmation</p>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include '../../includes/footer.php'; ?>