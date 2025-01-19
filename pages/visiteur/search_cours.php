<?php
require_once '../../classes/Cours.php';
require_once '../../classes/Tag.php';

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $cours = Cours::searchCours($searchTerm);
    
    $response = [];
    foreach ($cours as $c) {
        // Récupérer les tags pour ce cours
        $course_tags = $c->getTags($c->getIdCourse());
        $tags_html = '';
        foreach ($course_tags as $tag_id) {
            $tag = Tag::getTagById($tag_id);
            if ($tag) {
                $tags_html .= '<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded mr-2">' . 
                             htmlspecialchars($tag->getNom()) . '</span>';
            }
        }
        
        // Construire le HTML pour chaque cours
        $response[] = [
            'html' => '
            <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group">
                <div class="relative">
                    <img src="' . htmlspecialchars($c->getImage()) . '" alt="Cours" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute top-4 right-4 bg-blue-500 text-white px-3 py-1 rounded-full text-sm">
                        Nouveau
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        ' . $tags_html . '
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">' . htmlspecialchars($c->getTitre()) . '</h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">' . htmlspecialchars($c->getDescription()) . '</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <img src="../../images/Capture d\'écran 2025-01-19 002041.png" alt="Instructeur" class="w-8 h-8 rounded-full">
                            <span class="text-sm text-gray-600">Enseignant</span>
                        </div>
                        <span class="text-lg font-bold text-blue-500">Gratuit</span>
                    </div>
                    <a href="#" class="mt-4 block w-full text-center bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                        Voir le cours
                    </a>
                </div>
            </div>'
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>