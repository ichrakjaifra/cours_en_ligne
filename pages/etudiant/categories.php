<?php
session_start();
require_once '../../classes/Database.php';
require_once '../../classes/Categorie.php';

// Récupérer toutes les catégories
try {
    $categories = Categorie::getAllCategorie();
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Plateforme de cours en ligne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
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
                <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/mes_cours.php" class="hover:text-gray-200">Mes Cours</a>
                <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/categories.php" class="hover:text-gray-200">Catégories</a>
                <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/enseignants.php" class="hover:text-gray-200">Enseignants</a>
            </div>

            <!-- Liens de connexion/déconnexion (à droite) -->
            <div class="flex space-x-4">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/cours_en_ligne/cours_en_ligne/includes/auth.php?action=logout" class="hover:text-gray-200">Déconnexion</a>
                <?php else: ?>
                    <a href="/cours_en_ligne/cours_en_ligne/auth/login.php" class="hover:text-gray-200">Connexion</a>
                    <a href="/cours_en_ligne/cours_en_ligne/auth/register.php" class="hover:text-gray-200">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="bg-gray-100 min-h-screen py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-8">Catégories de cours</h1>

            <!-- Affichage des catégories -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php if (empty($categories)) : ?>
                    <p class="text-gray-600">Aucune catégorie disponible pour le moment.</p>
                <?php else : ?>
                    <?php foreach ($categories as $categorie) : ?>
                        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                            <h2 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($categorie->getNom()); ?></h2>
                            <p class="text-gray-600 mb-4">Explorez les cours dans cette catégorie.</p>
                            <a href="/cours_en_ligne/cours_en_ligne/pages/etudiant/dashboard2.php" class="text-blue-500 hover:underline">Voir les cours</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>
</body>
</html>