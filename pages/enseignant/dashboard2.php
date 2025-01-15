<?php
session_start();

// Vérifier si l'utilisateur est un enseignant validé
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'enseignant' || !$_SESSION['user']['est_valide']) {
  header("Location: /cours_en_ligne/auth/login.php");
  exit();
}

include '../../includes/header.php'; ?>
<main class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-8">Tableau de bord enseignant</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Mes cours -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Mes cours</h2>
                <ul class="space-y-2">
                    <li><a href="gerer_cours.php" class="text-blue-500 hover:underline">Introduction à la programmation</a></li>
                    <li><a href="gerer_cours.php" class="text-blue-500 hover:underline">Design UX/UI</a></li>
                </ul>
            </div>
            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Statistiques</h2>
                <p class="text-gray-600">Nombre d'étudiants inscrits : 50</p>
                <p class="text-gray-600">Nombre de cours : 2</p>
            </div>
            <!-- Actions rapides -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Actions rapides</h2>
                <a href="ajouter_cours.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Ajouter un cours</a>
            </div>
        </div>
    </div>
</main>
<?php include '../../includes/footer.php'; ?>