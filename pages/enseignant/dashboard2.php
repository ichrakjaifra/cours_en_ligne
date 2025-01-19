<?php
session_start();
require_once '../../classes/Database.php';
require_once '../../classes/Enseignant.php';

// Vérifier si l'utilisateur est connecté et est un enseignant validé
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'enseignant' || !$_SESSION['user']['est_valide']) {
    header("Location: /cours_en_ligne/cours_en_ligne/auth/login.php");
    exit();
}

// Créer une instance de l'enseignant
$enseignant = new Enseignant($_SESSION['user']['id'], $_SESSION['user']['nom'], $_SESSION['user']['prenom'], $_SESSION['user']['email'], '');

// Récupérer les statistiques des cours
try {
    $statistiques = $enseignant->accederStatistiques();
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    $statistiques = [];
}

// Récupérer le nombre de catégories
try {
  $nombreCategories = $enseignant->getNombreCategories();
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
  $nombreCategories = 0; // Valeur par défaut en cas d'erreur
}

// Récupérer les inscriptions des étudiants pour tous les cours de l'enseignant
try {
  $inscriptions = $enseignant->consulterInscriptions();
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
  $inscriptions = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Enseignant - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
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
                <a href="mes_cours.php" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl">
                    <i class="fas fa-book text-lg"></i>
                    <span class="font-medium">Mes Cours</span>
                </a>
                <!-- <a href="statistiques.php" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl">
                    <i class="fas fa-chart-line text-lg"></i>
                    <span class="font-medium">Statistiques</span>
                </a> -->
                <a href="#" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl">
                    <i class="fas fa-cog text-lg"></i>
                    <span class="font-medium">Paramètres</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-72 p-8">
            <!-- Top Navigation -->
            <div class="flex justify-between items-center mb-12 bg-white rounded-2xl p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="relative">
                        <input type="text" placeholder="Rechercher..." 
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
                                <?php echo strtoupper(substr($_SESSION['user']['nom'], 0, 1)); ?>
                            </div>
                            <span class="font-medium text-slate-700"><?php echo $_SESSION['user']['nom']; ?></span>
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
                <div class="card-hover bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-4 rounded-xl">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-slate-500 text-sm font-medium mb-1">Étudiants Inscrits</h3>
                            <p class="text-3xl font-bold text-slate-800"><?php echo count($inscriptions); ?></p>
                        </div>
                    </div>
                </div>

                <div class="card-hover bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center">
                        <div class="bg-emerald-100 p-4 rounded-xl">
                            <i class="fas fa-book text-emerald-600 text-2xl"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-slate-500 text-sm font-medium mb-1">Cours Actifs</h3>
                            <p class="text-3xl font-bold text-slate-800"><?php echo count($statistiques); ?></p>
                        </div>
                    </div>
                </div>

                <div class="card-hover bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center">
                        <div class="bg-violet-100 p-4 rounded-xl">
                            <i class="fas fa-tags text-violet-600 text-2xl"></i>
                        </div>
                        <div class="ml-6">
            <h3 class="text-slate-500 text-sm font-medium mb-1">Catégories</h3>
            <p class="text-3xl font-bold text-slate-800"><?php echo $nombreCategories; ?></p>
        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des Étudiants Inscrits -->
<div class="bg-white rounded-2xl shadow-sm">
    <div class="p-8 border-b border-slate-100">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-slate-800">Étudiants Inscrits</h2>
        </div>
    </div>
    <div class="overflow-x-auto p-4">
        <table class="w-full">
            <thead>
                <tr class="text-left">
                    <th class="px-6 py-4 text-sm font-semibold text-slate-600">Nom</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-600">Email</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-600">Date d'Inscription</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-600">Cours</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($inscriptions as $inscription): ?>
                    <tr class="hover:bg-slate-50 transition-all duration-300">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($inscription['nom'] . ' ' . $inscription['prenom']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($inscription['email']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($inscription['inscrit_a']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($inscription['cours_titre']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
        </main>
    </div>
</body>
</html>