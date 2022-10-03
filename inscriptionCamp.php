<?php
/**
 * Plugin Name:       kipdev_inscriptionCamp
 * description_camp:  instal to create and manage camps. also helps to generate an article from the information available
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            KIPDEV
 * Author URI:        denniskip.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       do not install bfore contacting the auther
 * Domain Path:       /languages
 **/

function inscriptionCamp($idCpt)
{
    //les variable
    global $post;
    $age_stagiare = '';
    $idCpt = get_queried_object_id();
    $categorie = get_the_category($idCpt->ID);
    var_dump($categorie);
    var_dump($idCpt);
    print_r($idCpt);

    $nomStagiaire = '';
    $prenomStagiaire = '';
    $taillesSelectioner = '';
    $dateNaissance = '';
    $nomCamp = '';
    $prenomResponsablelegal = '';
    $telResponsablelegal = '';
    $nomResponsablelegal = '';
    $emailResponsablelegal = '';
    $adresseResponsablelegal = '';
    $adresseStagiaire = '';
    $lienPhotoPassport = '';

    //when the button btn_register_camp is clicked and there is something in POST
    if (isset($_POST) && isset($_POST['btn_register_camp'])) {
        //id responsable legal

        // Recover variables from table
        $nomStagiaire = $_POST['nom_stagiaire'];
        $PrenomStagiaire = $_POST['Prenom_stagiaire'];
        $adresseStagiaire = $_POST['adresse_stagiaire'];
        $taillesSelectioner = $_POST['tailles_selectioner'];
        $dateNaissance = $_POST['date_naissance'];
        $idCamp = $_POST['camp_selectioner'];
        $demande = $_POST['demande'];

        $nomResponsablelegal = $_POST['nom_responsable_legal'];
        $prenomResponsablelegal = $_POST['prenom_responsable_legal'];
        $telResponsablelegal = $_POST['tel_responsable_legal'];
        $emailResponsablelegal = $_POST['email_responsable_legal'];
        $adresseResponsablelegal = $_POST['adresse_responsable_legal'];

        //mise à jour de responsable legal

        $sqlInsertRespLegal = "INSERT INTO mm_responsable_legal (nom_responsable_legal, prenom_responsable_legal, tel_responsable_legal, email_responsable_legal, adresse_responsable_legal)
        VALUES (:nom_responsable_legal,
                :prenom_responsable_legal,
                :tel_responsable_legal,
                :email_responsable_legal,
                :adresse_responsable_legal)";

        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmp = $conn->prepare($sqlInsertRespLegal);

            $stmp->bindValue('nom_responsable_legal', $nomResponsablelegal, PDO::PARAM_STR);
            $stmp->bindValue('prenom_responsable_legal', $prenomResponsablelegal, PDO::PARAM_STR);
            $stmp->bindValue('tel_responsable_legal', $telResponsablelegal, PDO::PARAM_STR);
            $stmp->bindValue('email_responsable_legal', $emailResponsablelegal, PDO::PARAM_STR);
            $stmp->bindValue('adresse_responsable_legal', $adresseResponsablelegal, PDO::PARAM_STR);

            $stmp->execute();

            $idRespLegal = $conn->lastInsertId();
        } catch (PDOException $e) {
            echo "error while inserting into responsable legal" . $e;
        }

        if (isset($_FILES) && $_FILES['autorisation_photo']["error"] === 0
                           && $_FILES['cert_mede_ffbb']["error"] === 0
                           && $_FILES['securite_social']["error"] === 0
                           && $_FILES['autorisation_photo']["error"] === 0
                           && $_FILES['mutuelle']["error"] === 0) {

            $autorisationPhoto = $_FILES['autorisation_photo'];
            $certMedeffbb = $_FILES['cert_mede_ffbb'];
            $securiteSocial = $_FILES['securite_social'];
            $ficheSanitaire = $_FILES['fiche_sanitaire'];
            $justificatifmutuelle = $_FILES['mutuelle'];

            //nested condition check if the doc type is pdf
            //Si le fichier n'est pas trop volumineux(1Mo accepté)
            if ($autorisationPhoto['size'] <= (1000000) &&
                ($certMedeffbb['size'] <= (1000000)) &&
                ($securiteSocial['size'] <= (1000000)) &&
                ($ficheSanitaire['size'] <= (1000000)) &&
                ($justificatifmutuelle['size'] <= (1000000))) {

                $extensionAutorisees = array(
                    "pdf" => "application/pdf");

                //strtolower pour être sûr que l'extension est en minuscules
                $extension = strtolower(pathinfo($autorisationPhoto["name"], PATHINFO_EXTENSION));

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
                } else {
                    echo "extention not autirised";
                }
            } else {
                echo "some or one of the files uploaded are too heavy";
            }

        } else {
            echo "there was an error uploading the documents";
        }
        //select camp where id_cpt= $idCpt
        //PDO connection to the DB
        $sql = "SELECT  * FROM mm_camp WHERE id_camp= :id_camp";
        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare($sql);
            $stmt->execute(['id_camp' => $idCamp]);
            $camp = $stmt->fetch(PDO::FETCH_ASSOC);

                $nomCamp = $camp['nom_camp'];
                $idCamp = $camp['id_camp'];
                $numMax = $camp['max_participants'];
                $nombreInscrits = $camp['nombre_inscrits'];

                var_dump($camp);
        } catch (PDOException $e) {
            echo "Something went wrong while selecting a camp'.$e.'";
        }

        $conn = null;

        if ($numMax > $nombreInscrits) {
            $sqlInsert = "INSERT INTO mm_stagiaire (
            date_inscription, id_camp, id_responsbal_legal, nom_stagiaire, prenom_stagiaire,
            date_naissance, adresse_stagiaire, lien_cert_med_licence_FBB, lien_justification_qf,
            lien_consentement_photo, lien_securite_social, lien_mutuelle, lien_fiche_sanitaire, demande, lien_photo_passport, tailles_vêtment)
             value= (
               :date_inscription,
               :id_camp,
               :id_responsbal_legal,
               :nom_stagiaire,
               :prenom_stagiaire,
               :adresse_stagiaire,
               :date_naissance,
               :lien_cert_med_licence_FBB,
               :lien_justification_qf,
               :lien_consentement_photo,
               :lien_securite_social,
               :lien_mutuelle,
               :lien_fiche_sanitaire,
               :demande
               :lien_photo_passport,
               :tailles_vêtment)";
            try {
                $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $smt = $conn->prepare($sqlInsert);

                $smt->bindValue(':date_inscription', date("Y-m-d"));
                $smt->bindValue(':id_camp', $idCamp, PDO::PARAM_INT);
                $smt->bindValue(':id_responsable', $idRespLegal, PDO::PARAM_INT);
                $smt->bindValue(':id_responsbal_legal', $nomStagiaire, PDO::PARAM_STR);
                $smt->bindValue(':prenom_stagiaire', $PrenomStagiaire, PDO::PARAM_STR);
                $smt->bindValue(':date_naissance', $dateNaissance);
                $smt->bindValue(':adresse_stagiaire', $adresseStagiaire, PDO::PARAM_STR);
                $smt->bindValue(':lien_cert_med_licence_FBB', $cheminCertMedeffbb, PDO::PARAM_STR);
                $smt->bindValue(':lien_consentement_photo', $cheminDocAautorisationPhoto, PDO::PARAM_STR);
                $smt->bindValue(':lien_securite_social', $cheminDocSecuriteSocial, PDO::PARAM_STR);
                $smt->bindValue(':lien_mutuelle', $cheminDocjustificatifMutuelle, PDO::PARAM_STR);
                $smt->bindValue(':lien_fiche_sanitaire', $cheminFicheSanitaire, PDO::PARAM_STR);
                $smt->bindValue('demande', $demande, PDO::PARAM_STR);
                $smt->bindValue('lien_photo_passport', $lienPhotoPassport, PDO::PARAM_STR);
                $smt->bindValue('tailles_vêtment', $taillesSelectioner, PDO::PARAM_STR);


                $smt->execute();

            } catch (PDOException $e) {
                echo "insertion to the tabel mm_stagiaire failed '.$e.';";
            }
        } else {
            //complete
            echo "no more slots available for this camp";
        }

        //update the number of subscribers in the camp
        $sql = "UPDATE mm_camp
        SET `nombre_inscrits` = nombre_inscrits
        WHERE id_camp = :id_camp";

        $nombreInscrits = $nombreInscrits + 1;

        echo $nombreInscrits;
        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $smt = $conn->prepair($sql);
            $stmp->bindValue('id_camp', $idCamp, PDO::PARAM_INT);
            $stmp->bindValue('nombre_inscrits', $nombreInscrits);

        } catch (\Throwable$th) {
            //throw $th;
        }
    }

    $content_inscription = '
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <div class="container">
    <h3>inscription camp</h3>
    <div class="form_group">
    <form method = "post" action="/inscription_camp" action="/inscription_camp" accept-charset="utf-8" enctype="multipart/form-data">

    <div class="row">
    <div class="col">
    <label for="nom_stagiaire">Votre NOM *</label>
    <input type="text" name="nom_stagiaire" id="nom_stagiaire" class="form-control" required="required" value="' . $nomStagiaire . '">
    </div>
    <div class="col">
    <label for="prenom_stagiaire">Votre Prenom*</label>
    <input type="text" name="prenom_stagiaire" id="nom_stagiaire" class="form-control" required="required" value="' . $prenomStagiaire . '">
    </div>
    </div>
    <br>

    <div class="row">
    <div class="col">
    <label for="adresse_stagiaire">Votre adresse *</label>
     <input type="text" name="adresse_stagiaire" id="nom_stagiaire" class="form-control" required="required" value="' . $adresseStagiaire . '">
    </div>
    <div class="col">
    <label for="tailles_selectionne">tailles de vêtements  *</label>
    <select class="form-select" aria-label="Camp" name="tailles_selectioner" required="required" placeholder="xs">
    <option selected>' . $taillesSelectioner . '</option>
    <option value="xs">xs</option>
    <option value="s">s</option>
    <option value="m">m</option>
    <option value="l">l</option>
    <option value="xl">xl</option>
    </select>
    </div>
    </div>

    <br>
    <div class="row">
    <div class="col">
    <label for="date_naissance">Date de naissance *</label>
    <input type="date" name="date_naissance" class="form-control" required="required" value=" ' . $dateNaissance . '">
    </div>
    <div class="col">
    <label for="camp_selectionne">Sélectionnez votre camp *</label>
    <select class="form-select" aria-label="Camp" name="camp_selectioner" required="required">
    <option value ' . $idCamp . ' selected  >' . $nomCamp . '</option>';

    //seelect all the camps partcipants can register
    //the camps that has status "publié"

    $sqlSelectCamp = "SELECT * FROM mm_camp WHERE 'status' = 'publié' and 'date-debut'> CURRDATE()";
    global $wpdb;
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $resultat = $conn->query($sqlSelectCamp);
        var_dump($resultat);
        //si il y a plus que Zéro ligne dans resultat
        if ($resultat) {
            while ($row = $resultat->fetch()) {
                $thisCamp = $row['nom_camp'];
                $thisCampId = $row['id_camp'];

                $content_inscription .= '<option value="' . $thisCampId . '">' . $thisCamp . '</ option>';
            }
            $content_inscription .= '</select>';

        }
    } catch (PDOException $e) {
        echo "error while selecting camps to a drop down menu '.$e.'";
    }

    $content_inscription .= '
    </select>
    </div>
    </div>
    <br>
    <h4>Télécharger les documents</h4>
    <br>
    <p> Les document type peut être télécharger <a href="https://www.magalimendy.fr/les-document-et-exemplaire/">ICI</a></p>
    <br>
    <p>! seuls les documents de type PDF sont acceptés</p>
    <br>
    <div class="row">
    <div class="col">
    <label for="cert_mede_ffbb">Certificat medical ou Licence FBB *</label>
    <input type="file" name="cert_mede_ffbb" class="form-control" accept=".pdf" required="required">
    </div>
    <div class="col">
    <label for="autorisaion_photo">Consentement à la publication de pohto *</label>
    <input type="file" name="autorisation_photo" class="form-control" accept=".pdf" required="required">
    </div>
    </div>
    <br>
    <div class="row">
    <div class="col">
    <label for="securite_social">Attestation de sécurité sociale *</label>
    <input type="file" name="securite_social" class="form-control" accept=".pdf" required="required">
    </div>
    <div class="col">
    <label for="mutuelle">Justificatif de mutuelle </label>
    <input type="file" name=" mutuelle" class="form-control" accept=".pdf">
    </div>
    <div class="col">
    <label for="fiche_sanitaire">Fiche sanitaire *</label>
    <input type="file" name="fiche_sanitaire" class="form-control" accept=".pdf" required="required">
    </div>
    </div>
    <br>
    <div class="row">
    <div class="col">
    <label for="demande">message *</label>
    <textarea class="form-control" name="Demande" id="demande" rows="6">Votre demande personnelle</textarea>
    </div>
    <div class="col">

    <h4> Info responsable legal</h4>
    <br>
    <p> compléter cette partie saulment si vous êtes mineur.e</p>
    <div class="row">
    <div class="col">
    <label for="nom_responsable_legal">Nom </label>
    <input type="text" name="nom_responsable_legal" class="form-control" value=" ' . $nomResponsablelegal . '">
    </div>
    <div class="col">
    <label for="prenom_responsable_legal">prénom </label>
    <input type="text" name="prenom_responsable_legal" class="form-control" value=" ' . $prenomResponsablelegal . '">
    </div>
    </div>
    <div class="row">
    <div class="col">
    <label for="tel_responsable_legal">Numéro tel</label>
    <input type="text" name="tel_responsable_legal" class="form-control" value=" ' . $telResponsablelegal . '">
    </div>
    <div class="col">
    <label for="email_responsable_legal">E-mail </label>
    <input type="email" name="email_responsable_legal" class="form-control" value=" ' . $emailResponsablelegal . '">
    </div>
    </div>
    <label for="adresse_responsable_legal">Adresse </label>
    <input type="text" name="adresse_responsable_legal" class="form-control" value=" ' . $adresseResponsablelegal . '">

    </div>
    </div>
    <br>
    <input type="submit" class="btn btn-primary" value="Soumtre" name = "btn_register_camp">

    </form>
    </div>

    </div>';

    return $content_inscription;
}
add_shortcode('kipdev_inscription', 'inscriptionCamp');
