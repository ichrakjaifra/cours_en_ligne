<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Plateforme de cours en ligne</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
  <!-- Navigation -->
  <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/index.php" class="text-2xl font-bold">Youdemy</a>

            <!-- Liens principaux (centrés) -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/index.php" class="hover:text-gray-200">Accueil</a>
                <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/dashboard2.php" class="hover:text-gray-200">Cours</a>
                <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/categories.php" class="hover:text-gray-200">Catégories</a>
                <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/enseignants.php" class="hover:text-gray-200">Enseignants</a>
            </div>

            <!-- Liens de connexion/déconnexion (à droite) -->
            <div class="flex space-x-4">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/cours_en_ligne/cours_en_ligne/pages/<?= $_SESSION['user']['role'] ?>/dashboard.php" class="hover:text-gray-200">Tableau de bord</a>
                    <a href="/cours_en_ligne/cours_en_ligne/includes/auth.php?action=logout" class="hover:text-gray-200">Déconnexion</a>
                <?php else: ?>
                    <a href="/cours_en_ligne/cours_en_ligne/auth/login.php" class="hover:text-gray-200">Connexion</a>
                    <a href="/cours_en_ligne/cours_en_ligne/auth/register.php" class="hover:text-gray-200">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
<main class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-8">Catégories de cours</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Catégorie 1 -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-2">Programmation</h2>
                <p class="text-gray-600 mb-4">Cours sur les langages de programmation.</p>
                <a href="/cours_en_ligne/cours_en_ligne/pages/visiteur/catalogue.php?categorie=programmation" class="text-blue-500 hover:underline">Voir les cours</a>
            </div>
            <!-- Catégorie 2 -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-2">Design</h2>
                <p class="text-gray-600 mb-4">Cours sur le design UX/UI.</p>
                <a href="/cours_en_ligne/cours_en_ligne/pages/visiteur/catalogue.php?categorie=design" class="text-blue-500 hover:underline">Voir les cours</a>
            </div>
        </div>
    </div>
</main>
<?php include '../../includes/footer.php'; ?>