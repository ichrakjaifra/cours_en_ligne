<?php include '../../includes/header.php'; ?>
<main class="bg-gray-100 min-h-screen">
    <!-- Hero Section -->
    <section class="bg-blue-600 text-white py-20">
        <div class="container mx-auto text-center">
            <h1 class="text-5xl font-bold mb-4">Bienvenue sur Youdemy</h1>
            <p class="text-xl mb-8">Apprenez de nouvelles compétences avec nos cours en ligne interactifs.</p>
            <a href="catalogue.php" class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-blue-50 transition duration-300">Explorer les cours</a>
        </div>
    </section>

    <!-- Featured Courses -->
    <section class="container mx-auto py-12">
        <h2 class="text-3xl font-bold text-center mb-8">Cours populaires</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Cours 1 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Cours 1" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Introduction à la programmation</h3>
                    <p class="text-gray-600 mb-4">Apprenez les bases de la programmation avec Python.</p>
                    <a href="cours_detail.php" class="text-blue-500 hover:underline">En savoir plus</a>
                </div>
            </div>
            <!-- Cours 2 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Cours 2" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Design UX/UI</h3>
                    <p class="text-gray-600 mb-4">Maîtrisez les principes du design d'interface utilisateur.</p>
                    <a href="cours_detail.php" class="text-blue-500 hover:underline">En savoir plus</a>
                </div>
            </div>
            <!-- Cours 3 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Cours 3" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Marketing Digital</h3>
                    <p class="text-gray-600 mb-4">Découvrez les stratégies de marketing en ligne.</p>
                    <a href="cours_detail.php" class="text-blue-500 hover:underline">En savoir plus</a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include '../../includes/footer.php'; ?>