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
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des cours : " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des cours.");
        }
    }
}
?>