<?php
$errorMSG = "";

// Vérifier les données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $errorMSG .= "Le nom est obligatoire ";
    } else {
        $name = $_POST["name"];
    }

    if (empty($_POST["email"])) {
        $errorMSG .= "L'e-mail est obligatoire ";
    } else {
        $email = $_POST["email"];
    }

    if (empty($_POST["subject"])) {
        $errorMSG .= "Le sujet de contact est obligatoire ";
    } else {
        $subject = $_POST["subject"];
    }

    if (empty($_POST["message"])) {
        $errorMSG .= "Le message est obligatoire ";
    } else {
        $message = $_POST["message"];
    }

    if ($errorMSG == "") {
        // Paramètres de connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "contacter";

        // Créer une connexion à la base de données
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("La connexion a échoué : " . $conn->connect_error);
        }

        // Préparer et exécuter la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO contact_form (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            // Envoyer l'e-mail (code pour l'envoi d'e-mail ici)
            $EmailTo = "armanmia7@gmail.com";
            $Subject = "New Message Received";
            $Body = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message";
            $success = mail($EmailTo, $Subject, $Body, "From:".$email);

            echo "success"; // Ou un message de succès personnalisé si nécessaire
        } else {
            echo "Erreur lors de l'enregistrement dans la base de données : " . $stmt->error;
        }

        // Fermer la connexion à la base de données
        $stmt->close();
        $conn->close();
    } else {
        echo $errorMSG;
    }
}
?>
