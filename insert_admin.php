<?php
// Inclure les classes nécessaires
require_once 'classes/Database.php';
require_once 'classes/Utilisateur.php';

// Informations de l'administrateur
$nom = "Admin";
$prenom = "Admin";
$email = "admin@youdemy.com";
$password = "admin123"; // Mot de passe en clair
$role_id = 3; // ID du rôle admin

try {
    // Créer un nouvel utilisateur administrateur
    $user = new Utilisateur($nom, $prenom, $email, $password, $role_id);
    
    // Enregistrer l'utilisateur dans la base de données
    $user->save();
    
    // Afficher un message de succès
    echo "Administrateur créé avec succès.";
} catch (Exception $e) {
    // Afficher un message d'erreur en cas de problème
    echo "Erreur : " . $e->getMessage();
}
?>