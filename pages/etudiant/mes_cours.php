<?php
session_start();
require_once '../../classes/Database.php';
require_once '../../classes/Etudiant.php';

// Vérifier si l'utilisateur est connecté et est un étudiant
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
    header("Location: /cours_en_ligne/cours_en_ligne/auth/login.php");
    exit();
}

// Créer une instance de l'étudiant
$etudiant = new Etudiant($_SESSION['user']['id'], $_SESSION['user']['nom'], $_SESSION['user']['prenom'], $_SESSION['user']['email'], '');

// Récupérer les cours de l'étudiant
try {
    $mesCours = $etudiant->accederMesCours();
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    $mesCours = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Cours - Youdemy</title>
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
                    <a href="/cours_en_ligne/cours_en_ligne/includes/auth.php?action=logout" class="hover:text-gray-200">Déconnexion</a>
                <?php else: ?>
                    <a href="/cours_en_ligne/cours_en_ligne/auth/login.php" class="hover:text-gray-200">Connexion</a>
                    <a href="/cours_en_ligne/cours_en_ligne/auth/register.php" class="hover:text-gray-200">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <!-- Titre de la page -->
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Mes Cours</h1>

        <!-- Liste des cours -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($mesCours)): ?>
                <p class="text-gray-600">Vous n'êtes inscrit à aucun cours pour le moment.</p>
            <?php else: ?>
                <?php foreach ($mesCours as $cours): ?>
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group">
                        <div class="relative">
                            <img src="<?php echo $cours['image']; ?>" alt="Cours" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-6">
                            <!-- Titre et description -->
                            <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo $cours['titre']; ?></h3>
                            <p class="text-gray-600 mb-4 line-clamp-2"><?php echo $cours['description']; ?></p>
                            <!-- Bouton pour voir le cours -->
                            <a href="cours_detail.php?id=<?php echo $cours['id_course']; ?>" class="mt-4 block w-full text-center bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                                Voir le cours
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>
</body>
</html>