<?php
require_once '../../classes/Categorie.php';

// Gestion des requêtes POST (ajout, modification, suppression)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ajouter une catégorie
    if (isset($_POST['add_category'])) {
        $nom = $_POST['nom'];
        $categorie = new Categorie(null, $nom);
        $categorie->insertCategorie();
    }

    // Modifier une catégorie
    if (isset($_POST['update_category'])) {
        $categoryId = $_POST['categoryId'];
        $nom = $_POST['nom'];
        $categorie = new Categorie($categoryId, $nom);
        $categorie->updateCategorie($categoryId);
    }

    // Supprimer une catégorie
    if (isset($_POST['delete_category'])) {
        $categoryId = $_POST['categoryId'];
        Categorie::deleteCategorie($categoryId);
    }
}

// Récupérer toutes les catégories pour affichage
$categories = Categorie::getAllCategorie();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Catégories</title>
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
                            <a href="/cours_en_ligne/cours_en_ligne/includes/auth.php?action=logout" class="block px-4 py-2 text-red-600 hover:bg-slate-50 transition-all duration-300">
                                <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categorie Table -->
            <div class="bg-white rounded-2xl shadow-sm">
                <div class="p-8 border-b border-slate-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-slate-800">Gestion des Catégories</h2>
                        <button onclick="toggleCategoryModal()" class="px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>Ajouter une Catégorie
                        </button>
                    </div>
                </div>

                <!-- Scrollable Table -->
                <div class="overflow-x-auto p-4">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="text-left bg-slate-50">
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Nom</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($categories as $categorie) : ?>
                                <tr class="hover:bg-slate-50 transition-all duration-300">
                                    <td class="px-6 py-4 text-slate-800 font-medium"><?php echo $categorie->getNom(); ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-3">
                                            <button onclick="editCategory('<?php echo $categorie->getIdCategorie(); ?>', '<?php echo $categorie->getNom(); ?>')" class="px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                                Modifier
                                            </button>
                                            <form method="POST">
                                                <input type="hidden" name="categoryId" value="<?php echo $categorie->getIdCategorie(); ?>">
                                                <button type="submit" name="delete_category" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal for Adding or Editing Category -->
            <div id="categoryModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
                    <h3 id="modalTitle" class="text-xl font-bold text-slate-800 mb-4">Ajouter une Catégorie</h3>
                    <form method="POST">
                        <div class="mb-4">
                            <label for="nom" class="block text-sm font-semibold text-slate-600">Nom</label>
                            <input type="text" id="nom" name="nom" class="mt-2 px-4 py-2 w-full border border-slate-300 rounded-lg" required>
                        </div>
                        <input type="hidden" id="categoryId" name="categoryId">
                        <div class="flex justify-end">
                            <button type="submit" id="submitButton" name="add_category" class="px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600">Ajouter</button>
                            <button type="button" onclick="closeCategoryModal()" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Fonction pour afficher ou masquer le modal
        function toggleCategoryModal() {
            document.getElementById('categoryModal').classList.toggle('hidden');
        }

        // Fonction pour fermer le modal
        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }

        // Fonction pour pré-remplir le formulaire lors de la modification
        function editCategory(id, nom) {
            document.getElementById('nom').value = nom;
            document.getElementById('categoryId').value = id;
            document.getElementById('modalTitle').innerText = 'Modifier une Catégorie';
            document.getElementById('submitButton').innerText = 'Modifier';
            document.getElementById('submitButton').name = 'update_category';
            toggleCategoryModal();
        }
    </script>
</body>
</html>