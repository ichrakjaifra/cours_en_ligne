<?php
require_once 'Cours.php';

class CoursDocument extends Cours {
    // Constructeur
    public function __construct($titre, $description, $image, $contenu, $categorie_id, $enseignant_id) {
        parent::__construct($titre, $description, $image, $contenu, $categorie_id, $enseignant_id);
    }

    // Implémentation de la méthode abstraite ajouterCours
    public function ajouterCours() {
      error_log("Enseignant ID: " . $this->enseignant_id);
      $db = Database::getInstance()->getConnection();
      try {
          $stmt = $db->prepare("INSERT INTO courses (titre, description, image, contenu, type, categorie_id, enseignant_id) VALUES (:titre, :description, :image, :contenu, 'document', :categorie_id, :enseignant_id)");
          $stmt->execute([
              ':titre' => $this->titre,
              ':description' => $this->description,
              ':image' => $this->image, 
              ':contenu' => $this->contenu,
              ':categorie_id' => $this->categorie_id,
              ':enseignant_id' => $this->enseignant_id
          ]);
          $this->id_course = $db->lastInsertId(); // Récupérer l'ID du cours ajouté
          return $this->id_course; // Retourner l'ID du cours
      } catch (PDOException $e) {
          error_log("Erreur lors de l'ajout du cours document : " . $e->getMessage());
          throw new Exception("Erreur lors de l'ajout du cours document.");
      }
  }
}
?>