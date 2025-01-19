<?php 
require_once '../../classes/Database.php';
require_once '../../classes/Enseignant.php';
// Récupérer tous les enseignants
try {
  $enseignants = Enseignant::getAllEnseignants();
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
  $enseignants = [];
}
include '../../includes/header.php'; ?>
<main class="bg-gray-100 min-h-screen py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold mb-8">Liste des Enseignants</h1>

            <!-- Affichage des enseignants -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($enseignants)) : ?>
                    <p class="text-gray-600">Aucun enseignant disponible pour le moment.</p>
                <?php else : ?>
                    <?php foreach ($enseignants as $enseignant) : ?>
                        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                            <h2 class="text-xl font-bold mb-2">
                                <?php echo htmlspecialchars($enseignant->getNom() . ' ' . $enseignant->getPrenom()); ?>
                            </h2>
                            <p class="text-gray-600 mb-4">
                                <i class="fas fa-envelope mr-2"></i>
                                <?php echo htmlspecialchars($enseignant->getEmail()); ?>
                            </p>
                            <p class="text-gray-600 mb-4">
                                <i class="fas fa-user-check mr-2"></i>
                                Statut : <?php echo htmlspecialchars($enseignant->getStatut()); ?>
                            </p>
                            <p class="text-gray-600 mb-4">
                                <i class="fas fa-check-circle mr-2"></i>
                                Validé : <?php echo $enseignant->estValide() ? 'Oui' : 'Non'; ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
<?php include '../../includes/footer.php'; ?>