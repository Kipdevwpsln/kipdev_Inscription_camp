<?
if (isset($_FILES) && $_FILES["error"] === 0) {

    $autorisationPhoto = $_FILES['autorisation_photo'];
    $certMedeffbb = $_FILES['cert_mede_ffbb'];
    $securiteSocial = $_FILES['securite_social'];
    $ficheSanitaire = $_FILES['fiche_sanitaire'];
    $justificatifmutuelle= $_FILES['mutuelle'];

//Si le fichier n'est pas trop volumineux(1Mo accepté)
if ($autorisationPhoto['size'] <= (1000000) &&
    ($certMedeffbb['size'] <= (1000000))&&
    ($securiteSocial['size'] <= (1000000))&&
    ($ficheSanitaire['size'] <= (1000000))&&
    ($justificatifmutuelle['size'] <= (1000000))) {

    $extensionAutorisees = array(
        "pdf" => "application/pdf");
    }

    //strtolower pour être sûr que l'extension est en minuscules
    $extension = strtolower(pathinfo($autorisationPhoto["name"], PATHINFO_EXTENSION));

    //Si l'extension/MIME ok
    if (array_key_exists($extension, $extensionAutorisees)
     && in_array($autorisationPhoto["type"], $extensionAutorisees)
     && in_array($certMedeffbb["type"], $extensionAutorisees)
     && in_array($securiteSocial["type"], $extensionAutorisees)
     && in_array($ficheSanitaire["type"], $extensionAutorisees)
     && in_array($justificatifmutuelle["type"], $extensionAutorisees)) {

        //déplacer le fichier du dossier temporaire vers le dossier uploads/propositions
        move_uploaded_file($autorisationPhoto["tmp_name"], './wp-content/uploads/camps/images/' . basename($autorisationPhoto["name"]));
        $cheminDocAautorisationPhoto = 'https://magalimendy.fr//wp-content/uploads/camps/images/' . $autorisationPhoto["name"] . '';

        move_uploaded_file($certMedeffbb["tmp_name"], './wp-content/uploads/camps/images/' . basename($certMedeffbb["name"]));
        $cheminCertMedeffbb = 'https://magalimendy.fr//wp-content/uploads/camps/images/' . $certMedeffbb["name"] . '';

        move_uploaded_file($securiteSocial["tmp_name"], './wp-content/uploads/camps/images/' . basename($securiteSocial["name"]));
        $cheminDocSecuriteSocial = 'https://magalimendy.fr//wp-content/uploads/camps/images/' . $securiteSocial["name"] . '';

        move_uploaded_file($ficheSanitaire["tmp_name"], './wp-content/uploads/camps/images/' . basename($ficheSanitaire["name"]));
        $cheminFicheSanitaire = 'https://magalimendy.fr//wp-content/uploads/camps/images/' . $ficheSanitaire["name"] . '';

        move_uploaded_file($justificatifmutuelle["tmp_name"], './wp-content/uploads/camps/images/' . basename($justificatifmutuelle["name"]));
        $cheminDocjustificatifMutuelle = 'https://magalimendy.fr//wp-content/uploads/camps/images/' . $justificatifmutuelle["name"] . '';
    }
} else if ($_FILES["error"] != 0) {
echo  "error avec téléchargement d'image";

}

