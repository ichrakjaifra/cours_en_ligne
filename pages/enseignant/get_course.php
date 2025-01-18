<?php
session_start();

require_once '../../classes/Cours.php';
require_once '../../classes/CoursVideo.php';
require_once '../../classes/CoursDocument.php';

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];
    $cours = Cours::getCoursById($course_id);

    if ($cours) {
        $tags = $cours->getTags($course_id);
        echo json_encode([
            'id_course' => $cours->getIdCourse(),
            'titre' => $cours->getTitre(),
            'description' => $cours->getDescription(),
            'image' => $cours->getImage(),
            'categorie_id' => $cours->getCategorieId(),
            'tags' => $tags,
            'type' => ($cours instanceof CoursVideo) ? 'video' : 'document',
            'contenu' => $cours->getContenu()
        ]);
    } else {
        echo json_encode(['error' => 'Cours non trouvé']);
    }
} else {
    echo json_encode(['error' => 'ID du cours non fourni']);
}