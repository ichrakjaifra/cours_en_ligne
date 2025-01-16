<?php
require_once 'Database.php';

abstract class Cours {
    protected $id_course;
    protected $titre;
    protected $description;
    protected $image; 
    protected $contenu;
    protected $categorie_id;
    protected $enseignant_id;

    // Constructeur
    public function __construct($titre, $description, $image, $contenu, $categorie_id, $enseignant_id) {
        $this->titre = $titre;
        $this->description = $description;
        $this->image = $image; 
        $this->contenu = $contenu;
        $this->categorie_id = $categorie_id;
        $this->enseignant_id = $enseignant_id;
    }

    // Getters
    public function getIdCourse() {
        return $this->id_course;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImage() {
        return $this->image;
    }

    public function getContenu() {
        return $this->contenu;
    }

    public function getCategorieId() {
        return $this->categorie_id;
    }

    public function getEnseignantId() {
        return $this->enseignant_id;
    }

    // Méthode abstraite pour ajouter un cours
    abstract public function ajouterCours();

    // Méthode pour modifier un cours
    public function modifierCours($id_course) {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->prepare("UPDATE courses SET titre = :titre, description = :description, image = :image, contenu = :contenu, categorie_id = :categorie_id WHERE id_course = :id_course");
            $stmt->execute([
                ':titre' => $this->titre,
                ':description' => $this->description,
                ':image' => $this->image, // Ajout de l'image
                ':contenu' => $this->contenu,
                ':categorie_id' => $this->categorie_id,
                ':id_course' => $id_course
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la modification du cours : " . $e->getMessage());
            throw new Exception("Erreur lors de la modification du cours.");
        }
    }

    // Méthode pour supprimer un cours
    public static function supprimerCours($id_course) {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->prepare("DELETE FROM courses WHERE id_course = :id_course");
            $stmt->execute([':id_course' => $id_course]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du cours : " . $e->getMessage());
            throw new Exception("Erreur lors de la suppression du cours.");
        }
    }

    // Méthode pour récupérer tous les cours
  public static function getAllCours() {
    $db = Database::getInstance()->getConnection();
    try {
        $stmt = $db->query("SELECT * FROM courses");
        $courses = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Créer un objet Cours en fonction du type
            if ($row['type'] === 'video') {
                $course = new CoursVideo($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
            } else {
                $course = new CoursDocument($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
            }
            // Définir l'ID du cours
            $course->id_course = $row['id_course'];
            $courses[] = $course;
        }
        return $courses;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des cours : " . $e->getMessage());
        throw new Exception("Erreur lors de la récupération des cours.");
    }
}

    // Méthode pour ajouter un tag à un cours
    public function addTag($course_id, $tag_id) {
      $db = Database::getInstance()->getConnection();
      try {
          $stmt = $db->prepare("INSERT INTO course_tags (course_id, tag_id) VALUES (:course_id, :tag_id)");
          $stmt->execute([
              ':course_id' => $course_id,
              ':tag_id' => $tag_id
          ]);
      } catch (PDOException $e) {
          error_log("Erreur lors de l'ajout du tag : " . $e->getMessage());
          throw new Exception("Erreur lors de l'ajout du tag.");
      }
  }

  // Méthode pour mettre à jour les tags d'un cours
  public function updateTags($course_id, $selected_tags) {
      $db = Database::getInstance()->getConnection();
      try {
          // Supprimer les tags existants pour ce cours
          $stmt = $db->prepare("DELETE FROM course_tags WHERE course_id = :course_id");
          $stmt->execute([':course_id' => $course_id]);

          // Ajouter les nouveaux tags sélectionnés
          foreach ($selected_tags as $tag_id) {
              $this->addTag($course_id, $tag_id);
          }
      } catch (PDOException $e) {
          error_log("Erreur lors de la mise à jour des tags : " . $e->getMessage());
          throw new Exception("Erreur lors de la mise à jour des tags.");
      }
  }

  // Méthode pour récupérer les tags d'un cours
  public function getTags($course_id) {
      $db = Database::getInstance()->getConnection();
      try {
          $stmt = $db->prepare("SELECT tag_id FROM course_tags WHERE course_id = :course_id");
          $stmt->execute([':course_id' => $course_id]);
          return $stmt->fetchAll(PDO::FETCH_COLUMN);
      } catch (PDOException $e) {
          error_log("Erreur lors de la récupération des tags : " . $e->getMessage());
          throw new Exception("Erreur lors de la récupération des tags.");
      }
  }

  
}
?>