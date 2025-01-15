<?php include '../../includes/header.php'; ?>
<main class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-8">Catégories de cours</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Catégorie 1 -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-2">Programmation</h2>
                <p class="text-gray-600 mb-4">Cours sur les langages de programmation.</p>
                <a href="/cours_en_ligne/pages/visiteur/catalogue.php?categorie=programmation" class="text-blue-500 hover:underline">Voir les cours</a>
            </div>
            <!-- Catégorie 2 -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-2">Design</h2>
                <p class="text-gray-600 mb-4">Cours sur le design UX/UI.</p>
                <a href="/cours_en_ligne/pages/visiteur/catalogue.php?categorie=design" class="text-blue-500 hover:underline">Voir les cours</a>
            </div>
        </div>
    </div>
</main>
<?php include '../../includes/footer.php'; ?>