<?php
session_start();
require_once '../../classes/Database.php';
require_once '../../classes/Utilisateur.php';
require_once '../../classes/Administrateur.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /cours_en_ligne/cours_en_ligne/auth/login.php");
    exit();
}

// Récupérer tous les utilisateurs avec leurs rôles
$db = Database::getInstance()->getConnection();
$stmt = $db->query("
    SELECT u.*, r.nom AS role_nom 
    FROM utilisateurs u 
    JOIN roles r ON u.role_id = r.id_role
");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement des actions (valider, activer, suspendre, supprimer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $utilisateurId = $_POST['utilisateur_id'];

    try {
        $admin = new Administrateur($_SESSION['user']['id'], $_SESSION['user']['nom'], $_SESSION['user']['prenom'], $_SESSION['user']['email'], null, $_SESSION['user']['role']);

        switch ($action) {
            case 'valider':
                $message = $admin->validerCompteEnseignant($utilisateurId);
                break;
            case 'activer':
            case 'suspendre':
            case 'supprimer':
                $message = $admin->gererUtilisateur($utilisateurId, $action);
                break;
            default:
                throw new Exception("Action non reconnue.");
        }

        $_SESSION['success'] = $message;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    // Recharger la page pour afficher les modifications
    header("Location: utilisateur.php");
    exit();
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
                <a href="dashboord.php" class="flex items-center space-x-4 px-6 py-4 bg-white bg-opacity-10 rounded-xl">
                    <i class="fas fa-th-large text-lg"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="utilisateur.php" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl">
                    <i class="fas fa-users text-lg"></i>
                    <span class="font-medium">Utilisateurs</span>
                </a>
                <a href="reserv.php" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl">
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
                    <span class="font-medium">Settings</span>
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
                            <a href="#" class="block px-4 py-2 text-red-600 hover:bg-slate-50 transition-all duration-300">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestion des Utilisateurs -->
            <div class="flex justify-between items-center mb-12 bg-white rounded-2xl p-6 shadow-sm">
                <h1 class="text-2xl font-bold text-slate-800">Gestion des Utilisateurs</h1>
                <a href="ajouter_utilisateur.php" class="px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>Ajouter un Utilisateur
                </a>
            </div>

            <!-- Messages de succès ou d'erreur -->
            <?php if (isset($_SESSION['success'])) : ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= $_SESSION['success'] ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])) : ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Tableau des utilisateurs -->
            <div class="bg-white rounded-2xl shadow-sm">
                <div class="overflow-x-auto p-4">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left bg-slate-50">
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Nom</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Email</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Rôle</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Statut</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Validé</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($utilisateurs as $utilisateur) : ?>
                                <tr class="hover:bg-slate-50 transition-all duration-300">
                                    <td class="px-6 py-4">
                                        <?= htmlspecialchars($utilisateur['nom'] . ' ' . $utilisateur['prenom']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        <?= htmlspecialchars($utilisateur['email']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        <?= htmlspecialchars($utilisateur['role_nom']) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge <?= $utilisateur['statut'] == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' ?>">
                                            <?= ucfirst($utilisateur['statut']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge <?= ($utilisateur['role_nom'] === 'etudiant' || $utilisateur['est_valide']) ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' ?>">
                                            <?= ($utilisateur['role_nom'] === 'etudiant' || $utilisateur['est_valide']) ? 'Oui' : 'Non' ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" class="flex space-x-3">
                                            <input type="hidden" name="utilisateur_id" value="<?= $utilisateur['id_utilisateur'] ?>">
                                            <?php if ($utilisateur['role_nom'] === 'enseignant' && !$utilisateur['est_valide']) : ?>
                                                <button type="submit" name="action" value="valider" class="text-green-500 hover:text-green-700" title="Valider">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button type="submit" name="action" value="activer" class="text-blue-500 hover:text-blue-700" title="Activer">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            <button type="submit" name="action" value="suspendre" class="text-yellow-500 hover:text-yellow-700" title="Suspendre">
                                                <i class="fas fa-pause-circle"></i>
                                            </button>
                                            <button type="submit" name="action" value="supprimer" class="text-red-500 hover:text-red-700" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
</body>
</html>