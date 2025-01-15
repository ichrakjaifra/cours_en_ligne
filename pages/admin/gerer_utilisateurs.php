<?php include '../../includes/header.php'; ?>
<main class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-8">Gérer les utilisateurs</h1>
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left py-2">Nom</th>
                        <th class="text-left py-2">Email</th>
                        <th class="text-left py-2">Rôle</th>
                        <th class="text-left py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-2">John Doe</td>
                        <td class="py-2">john@example.com</td>
                        <td class="py-2">Étudiant</td>
                        <td class="py-2">
                            <a href="#" class="text-blue-500 hover:underline">Modifier</a>
                            <a href="#" class="text-red-500 hover:underline ml-4">Supprimer</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-2">Jane Smith</td>
                        <td class="py-2">jane@example.com</td>
                        <td class="py-2">Enseignant</td>
                        <td class="py-2">
                            <a href="#" class="text-blue-500 hover:underline">Modifier</a>
                            <a href="#" class="text-red-500 hover:underline ml-4">Supprimer</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include '../../includes/footer.php'; ?>