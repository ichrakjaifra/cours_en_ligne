<?php
require_once 'Database.php'; // Assurez-vous d'inclure votre classe Database

class Tag {
    private $id_tag;
    private $nom;

    // Constructeur
    public function __construct($id_tag = null, $nom = null) {
        $this->id_tag = $id_tag;
        $this->nom = $nom;
    }

    // Getters
    public function getIdTag() {
        return $this->id_tag;
    }

    public function getNom() {
        return $this->nom;
    }

    // Setters
    public function setNom($nom) {
        $this->nom = $nom;
    }

    // Méthode pour insérer un tag
    public function insertTag() {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->prepare("INSERT INTO tags (nom) VALUES (:nom)");
            $stmt->execute([
                ':nom' => $this->nom
            ]);
            $this->id_tag = $db->lastInsertId();
            return $this;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion du tag : " . $e->getMessage());
            throw new Exception("Erreur lors de l'insertion du tag.");
        }
    }

    // Méthode pour mettre à jour un tag
    public function updateTag($tagId) {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->prepare("UPDATE tags SET nom = :nom WHERE id_tag = :id_tag");
            $stmt->execute([
                ':nom' => $this->nom,
                ':id_tag' => $tagId
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du tag : " . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour du tag.");
        }
    }

    // Méthode pour supprimer un tag
    public static function deleteTag($tagId) {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->prepare("DELETE FROM tags WHERE id_tag = :id_tag");
            $stmt->execute([
                ':id_tag' => $tagId
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du tag : " . $e->getMessage());
            throw new Exception("Erreur lors de la suppression du tag.");
        }
    }

    // Méthode pour récupérer tous les tags
    public static function getAllTags() {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->query("SELECT * FROM tags");
            $tags = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tags[] = new Tag($row['id_tag'], $row['nom']);
            }
            return $tags;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des tags : " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des tags.");
        }
    }
}
?>