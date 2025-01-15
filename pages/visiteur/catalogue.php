<?php include '../../includes/header.php'; ?>
<main class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-8">Catalogue des cours</h1>
        <!-- Filtres -->
        <div class="mb-8">
            <input type="text" placeholder="Rechercher un cours..." class="w-full md:w-1/2 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <!-- Liste des cours -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Cours 1 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Cours 1" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Introduction à la programmation</h3>
                    <p class="text-gray-600 mb-4">Apprenez les bases de la programmation avec Python.</p>
                    <a href="cours_detail.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Voir le cours</a>
                </div>
            </div>
            <!-- Cours 2 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Cours 2" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Design UX/UI</h3>
                    <p class="text-gray-600 mb-4">Maîtrisez les principes du design d'interface utilisateur.</p>
                    <a href="cours_detail.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Voir le cours</a>
                </div>
            </div>
            <!-- Cours 3 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Cours 3" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Marketing Digital</h3>
                    <p class="text-gray-600 mb-4">Découvrez les stratégies de marketing en ligne.</p>
                    <a href="cours_detail.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Voir le cours</a>
                </div>
            </div>
        </div>
        <!-- Pagination -->
        <div class="flex justify-center mt-8">
            <nav class="inline-flex rounded-md shadow">
                <a href="#" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">1</a>
                <a href="#" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">2</a>
                <a href="#" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">3</a>
            </nav>
        </div>
    </div>
</main>
<?php include '../../includes/footer.php'; ?>