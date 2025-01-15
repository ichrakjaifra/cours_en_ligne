<?
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /cours_en_ligne/cours_en_ligne/auth/login.php");
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
                    <span class="font-medium">Tableau de Bord</span>
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
                            <a href="#" class="block px-4 py-2 text-red-600 hover:bg-slate-50 transition-all duration-300">
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
                            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Users</h3>
                            <p class="text-3xl font-bold text-slate-800">3,842</p>
                            <div class="flex items-center mt-2 text-sm">
                                <span class="text-emerald-500">
                                    <i class="fas fa-arrow-up mr-1"></i>12%
                                </span>
                                <span class="text-slate-400 ml-2">vs last month</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-hover bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center">
                        <div class="bg-emerald-100 p-4 rounded-xl">
                            <i class="fas fa-book text-emerald-600 text-2xl"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-slate-500 text-sm font-medium mb-1">Active Courses</h3>
                            <p class="text-3xl font-bold text-slate-800">526</p>
                            <div class="flex items-center mt-2 text-sm">
                                <span class="text-emerald-500">
                                    <i class="fas fa-arrow-up mr-1"></i>18%
                                </span>
                                <span class="text-slate-400 ml-2">vs last month</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-hover bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center">
                        <div class="bg-violet-100 p-4 rounded-xl">
                            <i class="fas fa-tags text-violet-600 text-2xl"></i>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Categories</h3>
                            <p class="text-3xl font-bold text-slate-800">148</p>
                            <div class="flex items-center mt-2 text-sm">
                                <span class="text-emerald-500">
                                    <i class="fas fa-plus mr-1"></i>8 new
                                </span>
                                <span class="text-slate-400 ml-2">this week</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Courses Table -->
            <div class="bg-white rounded-2xl shadow-sm">
                <div class="p-8 border-b border-slate-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-slate-800">Recent Courses</h2>
                        <button class="px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-all duration-300">
                            Add Course
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto p-4">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left">
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">User</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Course</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Date</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Status</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50 transition-all duration-300">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 font-medium mr-3">
                                            JD
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">John Doe</p>
                                            <p class="text-sm text-slate-500">john@example.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">Introduction to Programming</td>
                                <td class="px-6 py-4 text-slate-600">Dec 28, 2024</td>
                                <td class="px-6 py-4">
                                    <span class="status-badge bg-emerald-100 text-emerald-700">Active</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-3">
                                        <button class="text-blue-500 hover:text-blue-700" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-slate-500 hover:text-slate-700" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-red-500 hover:text-red-700" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- More table rows... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>