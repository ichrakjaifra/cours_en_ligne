<?php
session_start();
require_once '../../classes/Administrateur.php';

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /cours_en_ligne/cours_en_ligne/auth/login.php");
    exit();
}

// Créer une instance de l'administrateur
$admin = new Administrateur($_SESSION['user']['id'], $_SESSION['user']['nom'], $_SESSION['user']['prenom'], $_SESSION['user']['email'], null, $_SESSION['user']['role']);

// Récupérer les statistiques
try {
    $statistiquesGlobales = $admin->accederStatistiquesGlobales();
    $coursParCategorie = $admin->getNombreCoursParCategorie();
    $coursPlusPopulaire = $admin->getCoursPlusPopulaire();
    $top3Enseignants = $admin->getTop3Enseignants();
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    $statistiquesGlobales = [];
    $coursParCategorie = [];
    $coursPlusPopulaire = [];
    $top3Enseignants = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .ocean-gradient {
            background: linear-gradient(135deg, #034694 0%, #00a7b3 100%);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-slate-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-72 ocean-gradient text-white py-8 px-6 fixed h-full">
            <div class="flex items-center mb-12">
                <span class="text-2xl font-bold tracking-wider">Youdemy</span>
            </div>

            <nav class="space-y-6">
                <a href="dashboard2.php" class="flex items-center space-x-4 px-6 py-4 bg-white bg-opacity-10 rounded-xl">
                    <i class="fas fa-th-large text-lg"></i>
                    <span class="font-medium">Tableau de Bord</span>
                </a>
                <a href="utilisateur.php" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl">
                    <i class="fas fa-users text-lg"></i>
                    <span class="font-medium">Utilisateurs</span>
                </a>
                <a href="cours.php" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl">
                    <i class="fas fa-book text-lg"></i>
                    <span class="font-medium">Cours</span>
                </a>
                <div class="relative">
                    <a href="#" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl" id="toggleCategories">
                        <i class="fas fa-tags text-lg"></i>
                        <span class="font-medium">Catégories</span>
                    </a>

                    <ul class="absolute left-0 w-full bg-white bg-opacity-10 rounded-xl mt-2 hidden" id="categoriesDropdown">
                        <li>
                            <a href="categories.php" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl">
                                <i class="fas fa-tags text-lg"></i>
                                <span class="font-medium">Catégories</span>
                            </a>
                        </li>
                        <li>
                            <a href="tags.php" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl">
                                <i class="fas fa-hashtag text-lg"></i>
                                <span class="font-medium">Tags</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="#" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl">
                    <i class="fas fa-cog text-lg"></i>
                    <span class="font-medium">Paramètres</span>
                </a>
            </nav>
        </aside>

        <script>
            const toggleButton = document.getElementById('toggleCategories');
            const dropdownMenu = document.getElementById('categoriesDropdown');

            toggleButton.addEventListener('click', function(event) {
                event.preventDefault();
                dropdownMenu.classList.toggle('hidden');
            });

            window.addEventListener('click', function(e) {
                if (!e.target.closest('.relative')) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        </script>

        <!-- Main Content -->
        <main class="flex-1 ml-72 p-8">
            <!-- Top Navigation -->
            <div class="flex justify-between items-center mb-12 bg-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-center">
                    <div class="relative">
                        <input type="text" placeholder="Search..." 
                               class="pl-12 pr-4 py-3 bg-slate-50 rounded-xl w-72 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                        <i class="fas fa-search absolute left-4 top-4 text-slate-400"></i>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <button class="relative p-2 bg-slate-50 rounded-xl hover:bg-slate-100 transition-all duration-300">
                            <i class="fas fa-bell text-slate-600 text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">3</span>
                        </button>
                    </div>
                    <div class="relative group">
                        <button class="flex items-center bg-slate-50 rounded-xl p-2 pr-4 hover:bg-slate-100 transition-all duration-300">
                            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                                TA
                            </div>
                            <span class="font-medium text-slate-700">Admin</span>
                            <i class="fas fa-chevron-down ml-3 text-slate-400 transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 z-50">
                            <hr class="my-2 border-slate-100">
                            <a href="/cours_en_ligne/cours_en_ligne/includes/auth.php?action=logout" class="block px-4 py-2 text-red-600 hover:bg-slate-50 transition-all duration-300">
                                <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Nombre total d'utilisateurs -->
                <div class="card-hover bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-4 rounded-xl">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Utilisateurs</h3>
                            <p class="text-3xl font-bold text-slate-800"><?= $statistiquesGlobales['total_utilisateurs'] ?? 0 ?></p>
                        </div>
                    </div>
                </div>

                <!-- Nombre total de cours -->
                <div class="card-hover bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center">
                        <div class="bg-emerald-100 p-4 rounded-xl">
                            <i class="fas fa-book text-emerald-600 text-2xl"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Cours</h3>
                            <p class="text-3xl font-bold text-slate-800"><?= $statistiquesGlobales['total_cours'] ?? 0 ?></p>
                        </div>
                    </div>
                </div>

                <!-- Nombre total d'enseignants -->
                <div class="card-hover bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center">
                        <div class="bg-violet-100 p-4 rounded-xl">
                            <i class="fas fa-chalkboard-teacher text-violet-600 text-2xl"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Enseignants</h3>
                            <p class="text-3xl font-bold text-slate-800"><?= $statistiquesGlobales['total_enseignants'] ?? 0 ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cours par catégorie -->
            <div class="bg-white rounded-2xl shadow-sm mb-12">
                <div class="p-8 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800">Répartition des cours par catégorie</h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($coursParCategorie as $categorie) : ?>
                            <div class="bg-slate-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($categorie['categorie']) ?></h3>
                                <p class="text-2xl font-bold text-blue-600"><?= $categorie['nombre_cours'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Cours le plus populaire -->
            <div class="bg-white rounded-2xl shadow-sm mb-12">
                <div class="p-8 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800">Cours le plus populaire</h2>
                </div>
                <div class="p-8">
                    <?php if ($coursPlusPopulaire) : ?>
                        <div class="bg-slate-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($coursPlusPopulaire['titre']) ?></h3>
                            <p class="text-2xl font-bold text-blue-600"><?= $coursPlusPopulaire['nombre_inscriptions'] ?> inscriptions</p>
                        </div>
                    <?php else : ?>
                        <p class="text-slate-600">Aucun cours trouvé.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Top 3 enseignants -->
            <div class="bg-white rounded-2xl shadow-sm mb-12">
                <div class="p-8 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800">Top 3 Enseignants</h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($top3Enseignants as $enseignant) : ?>
                            <div class="bg-slate-50 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($enseignant['nom'] . ' ' . $enseignant['prenom']) ?></h3>
                                <p class="text-2xl font-bold text-blue-600"><?= $enseignant['nombre_cours'] ?> cours</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>