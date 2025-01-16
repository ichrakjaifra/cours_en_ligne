<?php
require_once 'Cours.php';

class CoursVideo extends Cours {
    // Constructeur
    public function __construct($titre, $description, $image, $contenu, $categorie_id, $enseignant_id) {
        parent::__construct($titre, $description, $image, $contenu, $categorie_id, $enseignant_id);
    }

    // Implémentation de la méthode abstraite ajouterCours
    public function ajouterCours() {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->prepare("INSERT INTO courses (titre, description, image, contenu, type, categorie_id, enseignant_id) VALUES (:titre, :description, :image, :contenu, 'video', :categorie_id, :enseignant_id)");
            $stmt->execute([
                ':titre' => $this->titre,
                ':description' => $this->description,
                ':image' => $this->image, 
                ':contenu' => $this->contenu,
                ':categorie_id' => $this->categorie_id,
                ':enseignant_id' => $this->enseignant_id
            ]);
            $this->id_course = $db->lastInsertId();
            return $this;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du cours vidéo : " . $e->getMessage());
            throw new Exception("Erreur lors de l'ajout du cours vidéo.");
        }
    }
}
?>