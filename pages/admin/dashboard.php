<?php

include '../../includes/header.php';
?>

<main class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-8">Tableau de bord admin</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Utilisateurs -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Utilisateurs</h2>
                <p class="text-gray-600">Nombre total : 100</p>
                <a href="gerer_utilisateurs.php" class="text-blue-500 hover:underline">Gérer les utilisateurs</a>
            </div>
            <!-- Cours -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Cours</h2>
                <p class="text-gray-600">Nombre total : 20</p>
                <a href="gerer_cours.php" class="text-blue-500 hover:underline">Gérer les cours</a>
            </div>
            <!-- Tags -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Tags</h2>
                <p class="text-gray-600">Nombre total : 50</p>
                <a href="gerer_tags.php" class="text-blue-500 hover:underline">Gérer les tags</a>
            </div>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>