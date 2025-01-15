<?php include '../../includes/header.php'; ?>
<main class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-8">Ajouter un cours</h1>
        <form action="/includes/cours.php?action=ajouter" method="POST" class="bg-white p-8 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="titre" class="block text-gray-700">Titre du cours</label>
                <input type="text" name="titre" id="titre" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Description</label>
                <textarea name="description" id="description" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="contenu" class="block text-gray-700">Contenu</label>
                <textarea name="contenu" id="contenu" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
            </div>
            <div class="mb-6">
                <label for="categorie" class="block text-gray-700">Catégorie</label>
                <select name="categorie" id="categorie" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="1">Programmation</option>
                    <option value="2">Design</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Ajouter le cours</button>
        </form>
    </div>
</main>
<?php include '../../includes/footer.php'; ?>