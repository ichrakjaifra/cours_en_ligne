<?php
require_once 'Database.php'; 

class Categorie {
    private $id_categorie;
    private $nom;

    // Constructeur
    public function __construct($id_categorie = null, $nom = null) {
        $this->id_categorie = $id_categorie;
        $this->nom = $nom;
    }

    // Getters
    public function getIdCategorie() {
        return $this->id_categorie;
    }

    public function getNom() {
        return $this->nom;
    }

    // Setters
    public function setNom($nom) {
        $this->nom = $nom;
    }

    // Méthode pour insérer une catégorie
    public function insertCategorie() {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->prepare("INSERT INTO categories (nom) VALUES (:nom)");
            $stmt->execute([
                ':nom' => $this->nom
            ]);
            $this->id_categorie = $db->lastInsertId();
            return $this;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion de la catégorie : " . $e->getMessage());
            throw new Exception("Erreur lors de l'insertion de la catégorie.");
        }
    }

    // Méthode pour mettre à jour une catégorie
    public function updateCategorie($categorieId) {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->prepare("UPDATE categories SET nom = :nom WHERE id_categorie = :id_categorie");
            $stmt->execute([
                ':nom' => $this->nom,
                ':id_categorie' => $categorieId
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la catégorie : " . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour de la catégorie.");
        }
    }

    // Méthode pour supprimer une catégorie
    public static function deleteCategorie($categorieId) {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->prepare("DELETE FROM categories WHERE id_categorie = :id_categorie");
            $stmt->execute([
                ':id_categorie' => $categorieId
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de la catégorie : " . $e->getMessage());
            throw new Exception("Erreur lors de la suppression de la catégorie.");
        }
    }

    // Méthode pour récupérer toutes les catégories
    public static function getAllCategorie() {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->query("SELECT * FROM categories");
            $categories = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categorie = new Categorie($row['id_categorie'], $row['nom']);
                $categories[] = $categorie;
            }
            return $categories;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des catégories : " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des catégories.");
        }
    }
}
?>