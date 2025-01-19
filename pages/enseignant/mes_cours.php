<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un enseignant
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'enseignant') {
    header("Location: /cours_en_ligne/auth/login.php");
    exit();
}

require_once '../../classes/Cours.php';
require_once '../../classes/CoursVideo.php';
require_once '../../classes/CoursDocument.php';
require_once '../../classes/Utilisateur.php';
require_once '../../classes/Tag.php';
require_once '../../classes/Categorie.php';

// Récupérer tous les tags et catégories pour les sélecteurs
$tags = Tag::getAllTags();
$categories = Categorie::getAllCategorie();

// Gestion des requêtes POST (ajout, modification, suppression)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ajouter un cours
    if (isset($_POST['add_course'])) {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $categorie_id = $_POST['categorie_id'];
        $selected_tags = $_POST['tags'] ?? [];
        $type = $_POST['type']; // 'video' ou 'document'
        $enseignant_id = $_SESSION['user']['id'];

        // Upload de l'image
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = '../../uploads/';
            $image_name = basename($_FILES['image']['name']);
            $image_path = $upload_dir . $image_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        }

        // Gérer les champs spécifiques en fonction du type de cours
        if ($type === 'video') {
            // Upload du fichier vidéo
            if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] == 0) {
                $upload_dir = '../../uploads/';
                $video_name = basename($_FILES['video_file']['name']);
                $video_path = $upload_dir . $video_name;
                move_uploaded_file($_FILES['video_file']['tmp_name'], $video_path);
                $contenu = $video_path; // Stocker le chemin du fichier vidéo dans le contenu
            } else {
                $contenu = ''; // Si aucun fichier n'est téléversé
            }
        } elseif ($type === 'document') {
            // Récupérer le texte du document
            $contenu = $_POST['document_text']; // Stocker le texte dans le contenu
        }

        // Créer le cours en fonction du type
        if ($type === 'video') {
            $cours = new CoursVideo($titre, $description, $image_path, $contenu, $categorie_id, $enseignant_id);
        } else {
            $cours = new CoursDocument($titre, $description, $image_path, $contenu, $categorie_id, $enseignant_id);
        }

        // Ajouter le cours et récupérer l'ID du cours ajouté
        $cours_id = $cours->ajouterCours();

        // Ajouter les tags au cours
        foreach ($selected_tags as $tag_id) {
            $cours->addTag($cours_id, $tag_id);
        }

        // Rediriger ou afficher un message de succès
        header("Location: mes_cours.php");
        exit();
    }

    // Modifier un cours
    if (isset($_POST['update_course'])) {
      $course_id = $_POST['course_id'];
      $titre = $_POST['titre'];
      $description = $_POST['description'];
      $categorie_id = $_POST['categorie_id'];
      $selected_tags = $_POST['tags'] ?? [];

      // Upload de l'image (si une nouvelle image est fournie)
      $image_path = $_POST['existing_image'];
      if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
          $upload_dir = '../../uploads/';
          $image_name = basename($_FILES['image']['name']);
          $image_path = $upload_dir . $image_name;
          move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
      }

      // Récupérer le type et le contenu du cours depuis la base de données
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare("SELECT type, contenu FROM courses WHERE id_course = :id_course");
      $stmt->execute([':id_course' => $course_id]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($row) {
          $type = $row['type'];
          $contenu = $row['contenu'] ?? ''; // Utilisation de l'opérateur null coalescent pour une valeur par défaut
      } else {
          throw new Exception("Cours non trouvé.");
      }

      // Si le cours est de type "video" et qu'un nouveau fichier vidéo est téléversé
      if ($type === 'video' && isset($_FILES['video_file']) && $_FILES['video_file']['error'] == 0) {
          $upload_dir = '../../uploads/';
          $video_name = basename($_FILES['video_file']['name']);
          $video_path = $upload_dir . $video_name;
          move_uploaded_file($_FILES['video_file']['tmp_name'], $video_path);
          $contenu = $video_path; // Mettre à jour le contenu avec le nouveau fichier vidéo
      }

      // Si le cours est de type "document" et qu'un nouveau texte est fourni
      if ($type === 'document' && isset($_POST['document_text'])) {
          $contenu = $_POST['document_text']; // Mettre à jour le contenu avec le nouveau texte
      }

      // Instancier la classe appropriée en fonction du type
      if ($type === 'video') {
          $cours = new CoursVideo($titre, $description, $image_path, $contenu, $categorie_id, $_SESSION['user']['id']);
      } else {
          $cours = new CoursDocument($titre, $description, $image_path, $contenu, $categorie_id, $_SESSION['user']['id']);
      }

      // Mettre à jour le cours
      $cours->modifierCours($course_id);

      // Mettre à jour les tags du cours
      $cours->updateTags($course_id, $selected_tags);

      // Rediriger ou afficher un message de succès
      header("Location: mes_cours.php");
      exit();
  }


    // Supprimer un cours
    if (isset($_POST['delete_course'])) {
        $course_id = $_POST['course_id'];
        Cours::supprimerCours($course_id);

        // Rediriger ou afficher un message de succès
        header("Location: mes_cours.php");
        exit();
    }
}

// Récupérer tous les cours pour affichage
$enseignant_id = $_SESSION['user']['id'];
$cours = Cours::getAllCour($enseignant_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Cours - Youdemy</title>
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

        .modal-content {
            max-height: 80vh; /* Limite la hauteur de la modal à 80% de la hauteur de la vue */
            overflow-y: auto; /* Ajoute un défilement vertical si le contenu dépasse la hauteur maximale */
            padding-right: 16px; /* Ajoute un peu d'espace pour éviter que le contenu ne chevauche la barre de défilement */
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
                <a href="statistiques.php" class="flex items-center space-x-4 px-6 py-4 hover:bg-white hover:bg-opacity-10 rounded-xl">
                    <i class="fas fa-chart-line text-lg"></i>
                    <span class="font-medium">Statistiques</span>
                </a>
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
                            <a href="#" class="block px-4 py-2 text-red-600 hover:bg-slate-50 transition-all duration-300">
                                <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bouton pour afficher le formulaire d'ajout de cours -->
            <div class="mb-6">
                <button onclick="openCourseModal('add')" class="px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>Ajouter un Cours
                </button>
            </div>

            <!-- Tableau des cours -->
            <div class="bg-white rounded-2xl shadow-sm">
                <div class="p-8 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800">Mes Cours</h2>
                </div>
                <div class="overflow-x-auto p-4">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="text-left bg-slate-50">
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Titre</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Description</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Image</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Catégorie</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Tags</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($cours as $c) : ?>
                                <tr class="hover:bg-slate-50 transition-all duration-300">
                                    <td class="px-6 py-4 text-slate-800 font-medium"><?php echo $c->getTitre(); ?></td>
                                    <td class="px-6 py-4 text-slate-800"><?php echo $c->getDescription(); ?></td>
                                    <td class="px-6 py-4">
                                        <img src="<?php echo $c->getImage(); ?>" alt="Image du cours" class="w-16 h-16 object-cover rounded-lg">
                                    </td>
                                    <td class="px-6 py-4 text-slate-800"><?php echo $c->getCategorieId(); ?></td>
                                    <td class="px-6 py-4 text-slate-800">
                                        <?php
                                        $course_tags = $c->getTags($c->getIdCourse());
                                        echo implode(', ', array_map(function($tag_id) use ($tags) {
                                            foreach ($tags as $tag) {
                                                if ($tag->getIdTag() == $tag_id) {
                                                    return $tag->getNom();
                                                }
                                            }
                                            return '';
                                        }, $course_tags));
                                        ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-3">
                                            <button onclick="openCourseModal('edit', '<?php echo $c->getIdCourse(); ?>')" class="px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                                Modifier
                                            </button>
                                            <form method="POST">
                                                <input type="hidden" name="course_id" value="<?php echo $c->getIdCourse(); ?>">
                                                <button type="submit" name="delete_course" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
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
        </main>
    </div>

    <!-- Modal pour ajouter ou modifier un cours -->
    <div id="courseModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/3 modal-content">
            <h3 id="modalTitle" class="text-xl font-bold text-slate-800 mb-4">Ajouter un Cours</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" id="course_id" name="course_id">
                <input type="hidden" id="existing_image" name="existing_image">
                <div class="mb-4">
                    <label for="titre" class="block text-sm font-semibold text-slate-600">Titre</label>
                    <input type="text" id="titre" name="titre" class="mt-2 px-4 py-2 w-full border border-slate-300 rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-semibold text-slate-600">Description</label>
                    <textarea id="description" name="description" class="mt-2 px-4 py-2 w-full border border-slate-300 rounded-lg" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-sm font-semibold text-slate-600">Image</label>
                    <input type="file" id="image" name="image" class="mt-2 px-4 py-2 w-full border border-slate-300 rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="categorie_id" class="block text-sm font-semibold text-slate-600">Catégorie</label>
                    <select id="categorie_id" name="categorie_id" class="mt-2 px-4 py-2 w-full border border-slate-300 rounded-lg" required>
                        <?php foreach ($categories as $categorie) : ?>
                            <option value="<?php echo $categorie->getIdCategorie(); ?>"><?php echo $categorie->getNom(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-600">Tags</label>
                    <div class="mt-2">
                        <?php foreach ($tags as $tag) : ?>
                            <label class="inline-flex items-center mr-4">
                                <input type="checkbox" name="tags[]" value="<?php echo $tag->getIdTag(); ?>" class="form-checkbox">
                                <span class="ml-2"><?php echo $tag->getNom(); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="type" class="block text-sm font-semibold text-slate-600">Type de cours</label>
                    <select id="type" name="type" class="mt-2 px-4 py-2 w-full border border-slate-300 rounded-lg" required onchange="toggleFields()">
                        <option value="video">Vidéo</option>
                        <option value="document">Document</option>
                    </select>
                </div>

                <!-- Champ spécifique pour la vidéo -->
                <div id="videoFields" class="mb-4">
                    <label for="video_file" class="block text-sm font-semibold text-slate-600">Fichier vidéo</label>
                    <input type="file" id="video_file" name="video_file" class="mt-2 px-4 py-2 w-full border border-slate-300 rounded-lg" accept="video/*">
                </div>

                <!-- Champ spécifique pour le document -->
                <div id="documentFields" class="mb-4 hidden">
                    <label for="document_text" class="block text-sm font-semibold text-slate-600">Contenu du document</label>
                    <textarea id="document_text" name="document_text" class="mt-2 px-4 py-2 w-full border border-slate-300 rounded-lg" placeholder="Entrez le contenu du document ici..."></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" id="submitButton" name="add_course" class="px-4 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600">Ajouter</button>
                    <button type="button" onclick="closeCourseModal()" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fonction pour afficher ou masquer les champs spécifiques
        function toggleFields() {
            const type = document.getElementById('type').value;
            const videoFields = document.getElementById('videoFields');
            const documentFields = document.getElementById('documentFields');

            if (type === 'video') {
                videoFields.style.display = 'block';
                documentFields.style.display = 'none';
            } else if (type === 'document') {
                videoFields.style.display = 'none';
                documentFields.style.display = 'block';
            }
        }

        // Appeler la fonction au chargement de la page pour afficher les champs corrects
        document.addEventListener('DOMContentLoaded', toggleFields);

        // Fonction pour ouvrir le modal
        function openCourseModal(action, courseId = null) {
            const modal = document.getElementById('courseModal');
            const modalTitle = document.getElementById('modalTitle');
            const submitButton = document.getElementById('submitButton');

            if (action === 'add') {
                modalTitle.innerText = 'Ajouter un Cours';
                submitButton.innerText = 'Ajouter';
                submitButton.name = 'add_course';
                // Réinitialiser le formulaire
                document.getElementById('course_id').value = '';
                document.getElementById('titre').value = '';
                document.getElementById('description').value = '';
                document.getElementById('existing_image').value = '';
                document.getElementById('categorie_id').selectedIndex = 0;
                document.querySelectorAll('input[name="tags[]"]').forEach(checkbox => checkbox.checked = false);
                document.getElementById('type').selectedIndex = 0;
                document.getElementById('video_file').value = '';
                document.getElementById('document_text').value = '';
                toggleFields(); // Afficher les champs corrects
            } else if (action === 'edit' && courseId) {
                modalTitle.innerText = 'Modifier un Cours';
                submitButton.innerText = 'Modifier';
                submitButton.name = 'update_course';

                // Récupérer les données du cours via une requête AJAX ou les pré-remplir directement
                fetch(`get_course.php?id=${courseId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('course_id').value = data.id_course;
                        document.getElementById('titre').value = data.titre;
                        document.getElementById('description').value = data.description;
                        document.getElementById('existing_image').value = data.image;
                        document.getElementById('categorie_id').value = data.categorie_id;
                        // Pré-remplir les tags sélectionnés
                        data.tags.forEach(tagId => {
                            document.querySelector(`input[name="tags[]"][value="${tagId}"]`).checked = true;
                        });
                        document.getElementById('type').value = data.type;
                        document.getElementById('video_file').value = '';
                        document.getElementById('document_text').value = data.contenu;
                        toggleFields(); // Afficher les champs corrects
                    });
            }

            modal.classList.remove('hidden');
        }

        // Fonction pour fermer le modal
        function closeCourseModal() {
            document.getElementById('courseModal').classList.add('hidden');
        }
    </script>
</body>
</html>