<?php
require_once 'Database.php';
require_once 'CoursVideo.php'; // Inclure CoursVideo
require_once 'CoursDocument.php'; // Inclure CoursDocument

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
            if ($row['type'] === 'video') {
                $course = new CoursVideo($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
            } else {
                $course = new CoursDocument($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
            }
            $course->id_course = $row['id_course'];
            $courses[] = $course;
        }
        return $courses;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des cours : " . $e->getMessage());
        throw new Exception("Erreur lors de la récupération des cours.");
    }
}

public static function getAllCour($enseignant_id = null) {
  $db = Database::getInstance()->getConnection();
  try {
      // Construire la requête SQL en fonction de la présence de l'ID de l'enseignant
      $sql = "SELECT * FROM courses";
      if ($enseignant_id !== null) {
          $sql .= " WHERE enseignant_id = :enseignant_id";
      }

      $stmt = $db->prepare($sql);

      // Ajouter le paramètre enseignant_id si nécessaire
      if ($enseignant_id !== null) {
          $stmt->bindParam(':enseignant_id', $enseignant_id, PDO::PARAM_INT);
      }

      $stmt->execute();
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

  public static function getCoursById($id_course) {
    $db = Database::getInstance()->getConnection();
    try {
        $stmt = $db->prepare("SELECT * FROM courses WHERE id_course = :id_course");
        $stmt->execute([':id_course' => $id_course]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Créer un objet Cours en fonction du type
            if ($row['type'] === 'video') {
                $course = new CoursVideo($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
            } else {
                $course = new CoursDocument($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
            }
            // Définir l'ID du cours
            $course->id_course = $row['id_course'];
            return $course;
        } else {
            return null; // Aucun cours trouvé
        }
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération du cours : " . $e->getMessage());
        throw new Exception("Erreur lors de la récupération du cours.");
    }
}

public static function getAllCoursWithEnseignant() {
  $db = Database::getInstance()->getConnection();
  try {
      $stmt = $db->prepare("
          SELECT c.*, u.nom AS enseignant_nom, u.prenom AS enseignant_prenom 
          FROM courses c
          JOIN utilisateurs u ON c.enseignant_id = u.id_utilisateur
      ");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
      error_log("Erreur lors de la récupération des cours avec enseignants : " . $e->getMessage());
      throw new Exception("Erreur lors de la récupération des cours avec enseignants.");
  }
}

public static function getCoursWithPagination($page = 1, $perPage = 6, $categorie_id = null) {
  $db = Database::getInstance()->getConnection();
  try {
      $offset = ($page - 1) * $perPage;

      $sql = "SELECT * FROM courses";
      $countSql = "SELECT COUNT(*) as total FROM courses";

      if ($categorie_id !== null) {
          $sql .= " WHERE categorie_id = :categorie_id";
          $countSql .= " WHERE categorie_id = :categorie_id";
      }

      $sql .= " LIMIT :limit OFFSET :offset";

      $countStmt = $db->prepare($countSql);
      if ($categorie_id !== null) {
          $countStmt->bindParam(':categorie_id', $categorie_id, PDO::PARAM_INT);
      }
      $countStmt->execute();
      $totalCourses = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

      $stmt = $db->prepare($sql);
      if ($categorie_id !== null) {
          $stmt->bindParam(':categorie_id', $categorie_id, PDO::PARAM_INT);
      }
      $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
      $stmt->execute();
      
      $courses = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          if ($row['type'] === 'video') {
              $course = new CoursVideo($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
          } else {
              $course = new CoursDocument($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
          }
          $course->id_course = $row['id_course'];
          $courses[] = $course;
      }

      $totalPages = ceil($totalCourses / $perPage);
      
      return [
          'courses' => $courses,
          'totalPages' => $totalPages,
          'currentPage' => $page,
          'totalCourses' => $totalCourses
      ];
  } catch (PDOException $e) {
      error_log("Erreur lors de la récupération des cours : " . $e->getMessage());
      throw new Exception("Erreur lors de la récupération des cours.");
  }
}

public static function searchCours($searchTerm) {
  $db = Database::getInstance()->getConnection();
  try {
      $searchTerm = "%$searchTerm%";
      $stmt = $db->prepare("
          SELECT * FROM courses 
          WHERE titre LIKE :searchTerm 
          OR description LIKE :searchTerm
      ");
      $stmt->execute([':searchTerm' => $searchTerm]);
      
      $courses = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          if ($row['type'] === 'video') {
              $course = new CoursVideo($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
          } else {
              $course = new CoursDocument($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
          }
          $course->id_course = $row['id_course'];
          $courses[] = $course;
      }
      return $courses;
  } catch (PDOException $e) {
      error_log("Erreur lors de la recherche des cours : " . $e->getMessage());
      throw new Exception("Erreur lors de la recherche des cours.");
  }
}

public static function getCoursByCategory($categorie_id = null) {
  $db = Database::getInstance()->getConnection();
  try {
      if ($categorie_id) {
          $stmt = $db->prepare("SELECT * FROM courses WHERE categorie_id = :categorie_id");
          $stmt->execute([':categorie_id' => $categorie_id]);
      } else {
          $stmt = $db->query("SELECT * FROM courses");
      }
      
      $courses = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          if ($row['type'] === 'video') {
              $course = new CoursVideo($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
          } else {
              $course = new CoursDocument($row['titre'], $row['description'], $row['image'], $row['contenu'], $row['categorie_id'], $row['enseignant_id']);
          }
          $course->id_course = $row['id_course'];
          $courses[] = $course;
      }
      return $courses;
  } catch (PDOException $e) {
      error_log("Erreur lors de la récupération des cours par catégorie : " . $e->getMessage());
      throw new Exception("Erreur lors de la récupération des cours.");
  }
}

  
}
?>