<?php 
require_once '../../classes/Categorie.php';

// Récupérer toutes les catégories
try {
    $categories = Categorie::getAllCategorie();
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    $categories = [];
}

include '../../includes/header.php'; ?>
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
                            <a href="/cours_en_ligne/cours_en_ligne/pages/visiteur/catalogue.php" class="text-blue-500 hover:underline">Voir les cours</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
<?php include '../../includes/footer.php'; ?>