<?php
class Utilisateur {
    private $id ;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $role_id;
    private $statut;
    private $est_valide;

    // Constructeur
    public function __construct($id,$nom, $prenom, $email, $password, $role_id, $statut = 'active', $est_valide = false) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password; // Le mot de passe doit déjà être hashé avant d'être passé ici
        $this->role_id = $role_id;
        $this->statut = $statut;
        $this->est_valide = $est_valide;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; } 
    public function getRoleId() { return $this->role_id; }
    public function getStatut() { return $this->statut; }
    public function estValide() { return $this->est_valide; }

    // Méthode pour enregistrer un utilisateur dans la base de données
    public function save() {
        $db = Database::getInstance()->getConnection();
        try {
            $stmt = $db->prepare("INSERT INTO utilisateurs (nom, prenom, email, password, role_id, statut) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$this->nom, $this->prenom, $this->email, $this->password, $this->role_id, $this->statut]);
            $this->id = $db->lastInsertId();
            return $this->id;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'enregistrement de l'utilisateur : " . $e->getMessage());
            throw new Exception("Erreur lors de l'enregistrement de l'utilisateur.");
        }
    }

    // Méthode pour trouver un utilisateur par email
    public static function findByEmail($email) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new Utilisateur(
                $result['id_utilisateur'],
                $result['nom'],
                $result['prenom'],
                $result['email'],
                $result['password'], 
                $result['role_id'],
                $result['statut'],
                $result['est_valide']
            );
        }

        return null;
    }

    // Méthode pour connecter un utilisateur
    public static function login($email, $password) {
        $user = self::findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
          // Vérifier si l'utilisateur est suspendu
        if ($user->getStatut() === 'suspendu') {
          throw new Exception("Votre compte est suspendu. Veuillez contacter l'administrateur.");
      }

      // Vérifier si l'utilisateur est un enseignant non validé
      if ($user->getRoleId() === 2 && !$user->estValide()) {
          throw new Exception("Votre compte enseignant n'a pas encore été validé par un administrateur.");
      }
            return $user;
        }

        throw new Exception("Email ou mot de passe incorrect.");
    }

    // Méthode pour déconnecter un utilisateur
    public static function logout() {
        session_destroy();
        header("Location: /cours_en_ligne/auth/login.php");
        exit();
    }

    // Méthode pour vérifier si l'utilisateur est un admin
    public function isAdmin() {
        return $this->role_id === 3; // Supposons que 3 est l'ID du rôle admin
    }

    // Méthode pour vérifier si l'utilisateur est un enseignant
    public function isEnseignant() {
        return $this->role_id === 2; // Supposons que 2 est l'ID du rôle enseignant
    }

    // Méthode pour vérifier si l'utilisateur est un étudiant
    public function isEtudiant() {
        return $this->role_id === 1; // Supposons que 1 est l'ID du rôle étudiant
    }
}
?>