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
            <a href="/cours_en_ligne/pages/visiteur/index.php" class="text-2xl font-bold">Youdemy</a>

            <!-- Liens principaux (centrés) -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/cours_en_ligne/pages/visiteur/index.php" class="hover:text-gray-200">Accueil</a>
                <a href="/cours_en_ligne/pages/visiteur/catalogue.php" class="hover:text-gray-200">Cours</a>
                <a href="/cours_en_ligne/pages/visiteur/categories.php" class="hover:text-gray-200">Catégories</a>
                <a href="/cours_en_ligne/pages/visiteur/enseignants.php" class="hover:text-gray-200">Enseignants</a>
            </div>

            <!-- Liens de connexion/déconnexion (à droite) -->
            <div class="flex space-x-4">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/cours_en_ligne/pages/<?= $_SESSION['user']['role'] ?>/dashboard.php" class="hover:text-gray-200">Tableau de bord</a>
                    <a href="/cours_en_ligne/includes/auth.php?action=logout" class="hover:text-gray-200">Déconnexion</a>
                <?php else: ?>
                    <a href="/cours_en_ligne/auth/login.php" class="hover:text-gray-200">Connexion</a>
                    <a href="/cours_en_ligne/auth/register.php" class="hover:text-gray-200">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>