<?php
require_once 'Utilisateur.php'; 

class Administrateur extends Utilisateur {
    // Constructeur
    public function __construct($id, $nom, $prenom, $email, $passwordHash = null, $role_id, $statut = 'active', $est_valide = false) {
        parent::__construct($id, $nom, $prenom, $email, $passwordHash, $role_id, $statut, $est_valide);
    }

    // Méthode pour valider un compte enseignant
    public function validerCompteEnseignant($enseignantId) {
      $db = Database::getInstance()->getConnection();
      try {
          // Vérifiez d'abord si l'utilisateur existe et est un enseignant
          $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ? AND role_id = 2");
          $stmt->execute([$enseignantId]);
          $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
  
          if (!$utilisateur) {
              throw new Exception("Aucun enseignant trouvé avec cet ID.");
          }
  
          // Valider le compte enseignant
          $stmt = $db->prepare("UPDATE utilisateurs SET est_valide = TRUE WHERE id_utilisateur = ?");
          $stmt->execute([$enseignantId]);
  
          if ($stmt->rowCount() > 0) {
              return "Le compte enseignant a été validé avec succès.";
          } else {
              throw new Exception("Erreur lors de la validation du compte enseignant.");
          }
      } catch (PDOException $e) {
          error_log("Erreur dans validerCompteEnseignant : " . $e->getMessage());
          throw new Exception("Erreur lors de la validation du compte enseignant : " . $e->getMessage());
      }
  }
    // public function validerCompteEnseignant($enseignantId) {
    //     $db = Database::getInstance()->getConnection();
    //     try {
    //         $stmt = $db->prepare("UPDATE utilisateurs SET est_valide = TRUE WHERE id_utilisateur = ? AND role_id = 2");
    //         $stmt->execute([$enseignantId]);

    //         if ($stmt->rowCount() > 0) {
    //             return "Le compte enseignant a été validé avec succès.";
    //         } else {
    //             throw new Exception("Aucun enseignant trouvé avec cet ID.");
    //         }
    //     } catch (PDOException $e) {
    //         throw new Exception("Erreur lors de la validation du compte enseignant : " . $e->getMessage());
    //     }
    // }

    // Méthode pour gérer les utilisateurs (activer, suspendre, supprimer)
    public function gererUtilisateur($utilisateurId, $action) {
      $db = Database::getInstance()->getConnection();
      try {
          // Vérifiez d'abord si l'utilisateur existe
          $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
          $stmt->execute([$utilisateurId]);
          $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
  
          if (!$utilisateur) {
              throw new Exception("Aucun utilisateur trouvé avec cet ID.");
          }
  
          switch ($action) {
              case 'activer':
                  $stmt = $db->prepare("UPDATE utilisateurs SET statut = 'active' WHERE id_utilisateur = ?");
                  break;
              case 'suspendre':
                  $stmt = $db->prepare("UPDATE utilisateurs SET statut = 'suspendu' WHERE id_utilisateur = ?");
                  break;
              case 'supprimer':
                  $stmt = $db->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
                  break;
              default:
                  throw new Exception("Action non reconnue.");
          }
  
          $stmt->execute([$utilisateurId]);
  
          if ($stmt->rowCount() > 0) {
              return "Action '$action' effectuée avec succès sur l'utilisateur.";
          } else {
              throw new Exception("Aucun utilisateur trouvé avec cet ID.");
          }
      } catch (PDOException $e) {
          throw new Exception("Erreur lors de la gestion de l'utilisateur : " . $e->getMessage());
      }
  }
    // public function gererUtilisateur($utilisateurId, $action) {
    //     $db = Database::getInstance()->getConnection();
    //     try {
    //         switch ($action) {
    //             case 'activer':
    //                 $stmt = $db->prepare("UPDATE utilisateurs SET statut = 'active' WHERE id_utilisateur = ?");
    //                 break;
    //             case 'suspendre':
    //                 $stmt = $db->prepare("UPDATE utilisateurs SET statut = 'suspendu' WHERE id_utilisateur = ?");
    //                 break;
    //             case 'supprimer':
    //                 $stmt = $db->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
    //                 break;
    //             default:
    //                 throw new Exception("Action non reconnue.");
    //         }

    //         $stmt->execute([$utilisateurId]);

    //         if ($stmt->rowCount() > 0) {
    //             return "Action '$action' effectuée avec succès sur l'utilisateur.";
    //         } else {
    //             throw new Exception("Aucun utilisateur trouvé avec cet ID.");
    //         }
    //     } catch (PDOException $e) {
    //         throw new Exception("Erreur lors de la gestion de l'utilisateur : " . $e->getMessage());
    //     }
    // }

    // Méthode pour gérer les contenus (cours, catégories, tags)
    public function gererContenus($contenuId, $type, $action) {
        $db = Database::getInstance()->getConnection();
        try {
            switch ($type) {
                case 'cours':
                    $table = 'courses';
                    break;
                case 'categorie':
                    $table = 'categories';
                    break;
                case 'tag':
                    $table = 'tags';
                    break;
                default:
                    throw new Exception("Type de contenu non reconnu.");
            }

            switch ($action) {
                case 'ajouter':
                    // Exemple : Ajouter un cours (nécessite des données supplémentaires)
                    throw new Exception("L'ajout de contenu nécessite une implémentation spécifique.");
                case 'modifier':
                    // Exemple : Modifier un cours (nécessite des données supplémentaires)
                    throw new Exception("La modification de contenu nécessite une implémentation spécifique.");
                case 'supprimer':
                    $stmt = $db->prepare("DELETE FROM $table WHERE id = ?");
                    break;
                default:
                    throw new Exception("Action non reconnue.");
            }

            $stmt->execute([$contenuId]);

            if ($stmt->rowCount() > 0) {
                return "Action '$action' effectuée avec succès sur le contenu de type '$type'.";
            } else {
                throw new Exception("Aucun contenu trouvé avec cet ID.");
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la gestion du contenu : " . $e->getMessage());
        }
    }

    // Méthode pour accéder aux statistiques globales
    public function accederStatistiquesGlobales() {
        $db = Database::getInstance()->getConnection();
        try {
            $statistiques = [];

            // Nombre total d'utilisateurs
            $stmt = $db->query("SELECT COUNT(*) as total_utilisateurs FROM utilisateurs");
            $statistiques['total_utilisateurs'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_utilisateurs'];

            // Nombre total de cours
            $stmt = $db->query("SELECT COUNT(*) as total_cours FROM courses");
            $statistiques['total_cours'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_cours'];

            // Nombre total d'enseignants
            $stmt = $db->query("SELECT COUNT(*) as total_enseignants FROM utilisateurs WHERE role_id = 2");
            $statistiques['total_enseignants'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_enseignants'];

            // Nombre total d'étudiants
            $stmt = $db->query("SELECT COUNT(*) as total_etudiants FROM utilisateurs WHERE role_id = 1");
            $statistiques['total_etudiants'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_etudiants'];

            return $statistiques;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des statistiques : " . $e->getMessage());
        }
    }
}
?>