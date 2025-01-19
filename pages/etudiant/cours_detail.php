<?php
session_start();
require_once '../../classes/Database.php';
require_once '../../classes/Cours.php';
require_once '../../classes/Etudiant.php';

// Vérifier si l'utilisateur est connecté et est un étudiant
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
    header("Location: /cours_en_ligne/cours_en_ligne/auth/login.php");
    exit();
}

// Récupérer l'ID du cours depuis l'URL
if (!isset($_GET['id'])) {
    header("Location: /cours_en_ligne/cours_en_ligne/pages/etudiant/dashboard2.php");
    exit();
}
$coursId = $_GET['id'];

// Créer une instance de l'étudiant
$etudiant = new Etudiant($_SESSION['user']['id'], $_SESSION['user']['nom'], $_SESSION['user']['prenom'], $_SESSION['user']['email'], '');

// Récupérer les détails du cours
try {
    $detailsCours = $etudiant->consulterDetailsCours($coursId);
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: /cours_en_ligne/cours_en_ligne/pages/etudiant/dashboard2.php");
    exit();
}

// Gestion de l'inscription au cours
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscrire'])) {
    try {
        $etudiant->sInscrireCours($coursId);
        $_SESSION['success'] = "Vous êtes maintenant inscrit à ce cours.";
        // Rediriger vers la même page pour afficher le message
        header("Location: cours_detail.php?id=$coursId");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        // Rediriger vers la même page pour afficher l'erreur
        header("Location: cours_detail.php?id=$coursId");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Cours - Youdemy</title>
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
        <!-- Messages de succès ou d'erreur -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <!-- Titre du cours -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo $detailsCours['titre']; ?></h1>

            <!-- Image du cours -->
            <img src="<?php echo $detailsCours['image']; ?>" alt="Image du cours" class="w-full h-64 object-cover rounded-lg mb-6">

            <!-- Description du cours -->
            <p class="text-gray-600 mb-6"><?php echo $detailsCours['description']; ?></p>

            <!-- Contenu du cours -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Contenu du cours</h2>
                <?php if ($detailsCours['type'] === 'video'): ?>
                    <video controls class="w-full rounded-lg">
                        <source src="<?php echo $detailsCours['contenu']; ?>" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                <?php else: ?>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p class="text-gray-800"><?php echo $detailsCours['contenu']; ?></p>
                        <a href="<?php echo $detailsCours['contenu']; ?>" class="mt-2 inline-block text-blue-500 hover:text-blue-700" download>
                            <i class="fas fa-download mr-2"></i>Télécharger le document
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Catégorie et tags -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Informations supplémentaires</h2>
                <div class="flex items-center gap-2 mb-3">
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Catégorie : <?php echo $detailsCours['categorie']; ?></span>
                    <?php foreach ($detailsCours['tags'] as $tag): ?>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded"><?php echo $tag; ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Bouton d'inscription -->
            <form method="POST">
                <button type="submit" name="inscrire" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                    S'inscrire à ce cours
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>
</body>
</html>