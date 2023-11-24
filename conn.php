<?php
// insert_data.php

// ... (Connexion à la base de données et autres configurations)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];

    // Traitement du fichier image
    $target_dir = "uploads/"; // Répertoire où les images seront stockées
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Vérifier si le fichier image est une image réelle ou une fausse image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            echo "Le fichier est une image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "Le fichier n'est pas une image.";
            $uploadOk = 0;
        }
    }

    // Vérifier si le fichier image existe déjà
    if (file_exists($target_file)) {
        echo "Désolé, le fichier existe déjà.";
        $uploadOk = 0;
    }

    // Vérifier la taille maximale du fichier
    if ($_FILES["image"]["size"] > 500000) {
        echo "Désolé, votre fichier est trop volumineux.";
        $uploadOk = 0;
    }

    // Autoriser certains formats de fichiers
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        $uploadOk = 0;
    }

    // Vérifier si $uploadOk est défini à 0 par une erreur
    if ($uploadOk == 0) {
        echo "Désolé, votre fichier n'a pas été téléchargé.";
    // Si tout est correct, essayez de télécharger le fichier
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "Le fichier ". htmlspecialchars(basename($_FILES["image"]["name"])) . " a été téléchargé.";
            
            // Insertion des données dans la table des annonces
            $image_path = $target_file;
            $sql = "INSERT INTO annonces (titre, description, prix, image_path) VALUES ('$titre', '$description', '$prix', '$image_path')";

            if ($conn->query($sql) === TRUE) {
                echo "Données insérées avec succès";
            } else {
                echo "Erreur lors de l'insertion des données: " . $conn->error;
            }

        } else {
            echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    }
}

// Fermer la connexion
$conn->close();
?>
