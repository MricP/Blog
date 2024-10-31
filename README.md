## PHP - Blog

Ce projet est un blog développé en PHP dans le cadre d'un TP noté pour le BUT 2. L’objectif est de mettre en pratique les connaissances acquises en PHP, de travailler en équipe et de savoir communiquer efficacement autour du projet.  

# Objectifs du TP

• Mettre en pratique des concepts PHP : sessions, CRUD, gestion des formulaires.  
• Développer un projet complet de A à Z en collaboration (groupe de 2 à 3 personnes).  
• Présenter et communiquer autour d'un projet technique.  
# Fonctionnalités

**Utilisateurs**
• Page de Connexion : Permet la connexion via email et mot de passe. Si l’utilisateur n’a pas de compte, il sera créé à la volée.  
• Page d’Accueil : Affiche tous les articles disponibles avec possibilité de filtrer par pseudo d'auteur.  
• Affichage d’un Article : Affiche un article unique avec ses commentaires et inclut un formulaire pour ajouter un commentaire.  
• Création d’Articles : Page pour ajouter un article, choisir une ou plusieurs catégories, et renseigner les attributs de l’article.  
• CRUD pour les Catégories : Accessible uniquement pour l'administrateur, situé sous le préfixe « /admin ».  
• Suppression : Les utilisateurs peuvent supprimer leurs propres articles ou commentaires.  
• Gestion de la Base de Données : Base de données avec table User pour gérer les informations des utilisateurs et leurs rôles.  

# Détails sur les utilisateurs et accès

Voici les utilisateurs et le compte administrateur pré-configurés pour le projet :

**Liste des comptes déjà créés :**  
    (Mot de passe par défaut : azerty123)  
    • user1@localhost.fr : 2 articles, aucun commentaire  
    • user2@localhost.fr : 2 articles, 2 commentaires  
    • user3@localhost.fr : 1 article, 1 commentaire  
    • user4@localhost.fr : 1 article, 2 commentaires  
    • user5@localhost.fr : Aucun article, 1 commentaire  

**Compte Admin**  
    • Email : admin@localhost.fr  
    • Mot de passe : admin69IUT;  

# Base de Données

• Nom: blog  
• Contenu: importer via le fichier blog.sql dans le dossier database  