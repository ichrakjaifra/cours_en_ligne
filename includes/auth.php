<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/Utilisateur.php';
require_once '../classes/Administrateur.php';
require_once '../classes/Etudiant.php';

$action = $_GET['action'] ?? '';

// Fonction pour valider les entrées utilisateur
function validateInput($data) {
    $data = trim($data); // Supprime les espaces inutiles
    $data = stripslashes($data); // Supprime les antislashes
    $data = htmlspecialchars($data); // Convertit les caractères spéciaux en entités HTML
    return $data;
}

// Gestion de la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
  $email = validateInput($_POST['email']);
  $password = validateInput($_POST['password']);

  try {
      // Vérifier si l'email et le mot de passe sont fournis
      if (empty($email) || empty($password)) {
          throw new Exception("Veuillez remplir tous les champs.");
      }

      // Tenter de connecter l'utilisateur
      $user = Utilisateur::login($email, $password);

      // Vérifier si l'utilisateur est un enseignant non validé
      if ($user->getRoleId() === 2 && !$user->estValide()) {
          throw new Exception("Votre compte enseignant n'a pas encore été validé par un administrateur.");
      }

      // Enregistrer l'utilisateur dans la session
      $_SESSION['user'] = [
          'id' => $user->getId(),
          'nom' => $user->getNom(),
          'prenom' => $user->getPrenom(),
          'email' => $user->getEmail(),
          'role' => $user->getRoleId() === 1 ? 'etudiant' : ($user->getRoleId() === 2 ? 'enseignant' : 'admin'),
          'est_valide' => $user->estValide() 
          
      ];

      // Rediriger vers le tableau de bord approprié
      header("Location: /cours_en_ligne/cours_en_ligne/pages/{$_SESSION['user']['role']}/dashboard2.php");
      exit();
  } catch (Exception $e) {
      $_SESSION['error'] = $e->getMessage();
      header("Location: /cours_en_ligne/cours_en_ligne/auth/login.php");
      exit();
  }
}

// Gestion de l'inscription
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'register') {
  $nom = validateInput($_POST['nom']);
  $prenom = validateInput($_POST['prenom']);
  $email = validateInput($_POST['email']);
  $password = validateInput($_POST['password']);
  $role = validateInput($_POST['role']);

  try {
      // Vérifier si tous les champs sont remplis
      if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($role)) {
          throw new Exception("Veuillez remplir tous les champs.");
      }

      // Convertir le rôle en ID
      $role_id = $role === 'etudiant' ? 1 : ($role === 'enseignant' ? 2 : 3);

      // Si l'utilisateur est un étudiant, il est validé par défaut
      $est_valide = ($role === 'etudiant') ? true : false;

      // Hacher le mot de passe
      $passwordHash = password_hash($password, PASSWORD_BCRYPT);

      // Créer un nouvel utilisateur ou étudiant selon le rôle
      if ($role === 'etudiant') {
          $user = new Etudiant(null, $nom, $prenom, $email, $passwordHash, 'active', $est_valide);
      } else {
          $user = new Utilisateur(null, $nom, $prenom, $email, $passwordHash, $role_id, 'active', $est_valide);
      }

      // Enregistrer l'utilisateur dans la base de données
      $user->save();

      // Message de succès selon le rôle
      if ($role === 'enseignant') {
          $_SESSION['success'] = "Votre inscription a été soumise. Un administrateur doit valider votre compte avant que vous puissiez accéder au tableau de bord.";
      } else {
          $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
      }

      // Rediriger vers la page de connexion
      header("Location: /cours_en_ligne/cours_en_ligne/auth/login.php");
      exit();
  } catch (Exception $e) {
      $_SESSION['error'] = $e->getMessage();
      header("Location: /cours_en_ligne/cours_en_ligne/auth/register.php");
      exit();
  }
}

// Gestion de la déconnexion
elseif ($action === 'logout') {
    // Détruire la session
    session_destroy();
    // Rediriger vers la page de connexion
    header("Location: /cours_en_ligne/cours_en_ligne/auth/login.php");
    exit();
}

// Redirection par défaut si aucune action n'est spécifiée
else {
    header("Location: /cours_en_ligne/cours_en_ligne/auth/login.php");
    exit();
}
?>