<?php
session_start();
require_once '../../classes/Cours.php';
require_once '../../classes/Categorie.php';
require_once '../../classes/Tag.php';

// Récupérer toutes les catégories pour le filtre
$categories = Categorie::getAllCategorie();

// Récupérer tous les cours (ou filtrer par catégorie si une catégorie est sélectionnée)
$selected_category = isset($_GET['categorie']) ? $_GET['categorie'] : null;
$cours = Cours::getAllCours(); // Récupérer tous les cours

// Si une catégorie est sélectionnée, filtrer les cours
if ($selected_category) {
    $cours = array_filter($cours, function($c) use ($selected_category) {
        return $c->getCategorieId() == $selected_category;
    });
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$selected_category = isset($_GET['categorie']) ? $_GET['categorie'] : null;

// Récupérer les cours paginés
$result = Cours::getCoursWithPagination($page, 6, $selected_category);
$cours = $result['courses'];
$totalPages = $result['totalPages'];
$currentPage = $result['currentPage'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des Cours - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-50">
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
                    <!-- <a href="/cours_en_ligne/cours_en_ligne/pages/<?= $_SESSION['user']['role'] ?>/dashboard2.php" class="hover:text-gray-200">Tableau de bord</a> -->
                    <a href="/cours_en_ligne/cours_en_ligne/includes/auth.php?action=logout" class="hover:text-gray-200">Déconnexion</a>
                <?php else: ?>
                    <a href="/cours_en_ligne/cours_en_ligne/auth/login.php" class="hover:text-gray-200">Connexion</a>
                    <a href="/cours_en_ligne/cours_en_ligne/auth/register.php" class="hover:text-gray-200">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-12">
        <div class="container mx-auto px-4">
            <!-- En-tête et recherche -->
            <div class="max-w-7xl mx-auto">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Catalogue des cours</h1>
                <p class="text-gray-600 mb-8">Découvrez notre sélection de cours de qualité</p>

                <!-- Barre de recherche -->
                <div class="flex flex-col md:flex-row gap-4 mb-8">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Rechercher un cours..." 
                                   class="w-full px-4 py-3 pl-12 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 absolute left-3 top-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="flex flex-wrap gap-4 mb-8">
                    <form method="GET" class="flex gap-4">
                        <select name="categorie" class="px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-white">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $categorie) : ?>
                                <option value="<?php echo $categorie->getIdCategorie(); ?>" <?php echo ($selected_category == $categorie->getIdCategorie()) ? 'selected' : ''; ?>>
                                    <?php echo $categorie->getNom(); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                            Filtrer
                        </button>
                    </form>
                </div>

                <!-- Grille des cours -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($cours as $c) : ?>
                        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group">
                            <div class="relative">
                                <img src="<?php echo $c->getImage(); ?>" alt="Cours" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                                <div class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded-full text-sm">
                                    Nouveau
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Tags du cours -->
                                <div class="flex items-center gap-2 mb-3">
                                    <?php
                                    $course_tags = $c->getTags($c->getIdCourse());
                                    foreach ($course_tags as $tag_id) {
                                        $tag = Tag::getTagById($tag_id); // Récupérer le tag par son ID
                                        if ($tag) {
                                            echo '<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">' . $tag->getNom() . '</span>';
                                        }
                                    }
                                    ?>
                                </div>
                                <!-- Titre et description -->
                                <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo $c->getTitre(); ?></h3>
                                <p class="text-gray-600 mb-4 line-clamp-2"><?php echo $c->getDescription(); ?></p>
                                <!-- Instructeur et prix -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <img src="../../images/Capture d'écran 2025-01-19 002041.png" alt="Instructeur" class="w-8 h-8 rounded-full">
                                        <span class="text-sm text-gray-600">Enseignant</span>
                                    </div>
                                    <span class="text-lg font-bold text-blue-500">Gratuit</span>
                                </div>
                                <!-- Bouton pour voir le cours -->
                                <a href="cours_detail.php?id=<?php echo $c->getIdCourse(); ?>" class="mt-4 block w-full text-center bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                                    Voir le cours
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
<div class="flex justify-center mt-12">
    <nav class="flex items-center gap-2">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?php echo ($currentPage - 1); ?><?php echo $selected_category ? '&categorie=' . $selected_category : ''; ?>" 
               class="px-4 py-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition duration-200">
                Précédent
            </a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?><?php echo $selected_category ? '&categorie=' . $selected_category : ''; ?>" 
               class="px-4 py-2 rounded-lg <?php echo $i == $currentPage ? 'bg-blue-500 text-white' : 'border border-gray-200 text-gray-700'; ?> hover:<?php echo $i == $currentPage ? 'bg-blue-600' : 'bg-gray-50'; ?> transition duration-200">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo ($currentPage + 1); ?><?php echo $selected_category ? '&categorie=' . $selected_category : ''; ?>" 
               class="px-4 py-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition duration-200">
                Suivant
            </a>
        <?php endif; ?>
    </nav>
</div>
            </div>
        </div>
    </main>
    <?php include '../../includes/footer.php'; ?>
</body>
</html>