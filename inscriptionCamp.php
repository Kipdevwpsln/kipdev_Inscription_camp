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
 */
function inscriptionCamp($id_cpt)
{
    //les variable
    global $post;
    $age_stagiare = '';
    $idCpt = get_the_ID();
    $categorie = get_the_category($post->ID);

    if (isset($_POST) && isset($_POST['btn_register_camp'])) {
        //id responsable legal

        // Recover variables from url
        $nomStagiaire = $_POST['nom_stagiaire'];
        $prenomStagiaire = $_POST['prenom_stagiaire'];
        $adresseStagiaire = $_POST['adresse_stagiaire'];
        $dateNaissance = $_POST['date_naissance'];
        $emailResponsablelegal = $_POST['mail_stagiaire'];
        $tailles_haut = $_POST['tailles_haut'];
        $sexStagiaire = $_POST['sex_stagiaire'];
        $idCamp = $_POST['camp_selectioner'];
        $demande = $_POST['demande'];
        $mailStagiaire = $_POST['mail_stagiaire'];
        $telStagiaire = $_POST['tel_stagiaire'];
        $lien_photo_passport ="";
        $tailles_bas = $_POST['tailles_bas'];

        //today

        $today = date('Y-m-d');
        echo $today;

        echo ("the selected campis of id: " . $idCamp);

        $nomResponsablelegal = $_POST['nom_responsable_legal'];
        $prenomResponsablelegal = $_POST['prenom_responsable_legal'];
        $telResponsablelegal = $_POST['tel_responsable_legal'];
        $emailResponsablelegal = $_POST['email_responsable_legal'];
        $adresseResponsablelegal = $_POST['adresse_responsable_legal'];

        if (isset($_POST['nom_responsable_legal'])) {
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

            } catch (PDO $e) {
                echo "problem encountered while connection to the DB:" . $e;
            }
        }
        //other variables

        //treatment images
        if (isset($_FILES)) {
            $urlAutorisationPhoto ="";
            $urlCertmedeffbb ="";
            $urlficheSanitaire ="";
            $urlJustificatifmutuelle ="";
            $urlSecuriteSocial ="";

            $docCertMedLicence = $_FILES['cert_mede_ffbb'];
            $docAutorisationPhoto = $_FILES['autorisation_photo'];
            $docSecuriteSocial = $_FILES['securite_social'];

            $docMutuelle = $_FILES['mutuelle'];
            $docFicheSanitaire = $_FILES['fiche_sanitaire'];

            //names
            $nameCertMedLicence = $_FILES['cert_mede_ffbb']['name'];
            $nameAutorisationPhoto = $_FILES['autorisation_photo']['name'];
            $nameSecuriteSocial = $_FILES['securite_social']['name'];
            $nameMutuelle = $_FILES['mutuelle']['name'];
            $nameFicheSanitaire = $_FILES['fiche_sanitaire']['name'];

            //traitement de cert medical ou licence ffb
            if ($docCertMedLicence['error'] === UPLOAD_ERR_OK) {
                $destination = './wp-content/uploads/camps/docs/' . $nameCertMedLicence;
                $tmpName = $docCertMedLicence['tmp_name'];
                $sizeDoc = $docCertMedLicence['size'];

                $extention = pathinfo($nameCertMedLicence, PATHINFO_EXTENSION);

                if ($sizeDoc < 1000000) {
                    if (in_array($extention, ['pdf', 'PDF'])) {
                        if (move_uploaded_file($tmpName, $destination)) {
                            $urlCertmedeffbb = 'https://www.magalimendy.fr/wp-content/uploads/camps/docs/' . $nameCertMedLicence;
                            echo "cert medical  was successfully uploaded.";
                        } else {
                            echo "problem while moving uploaded file to destination";
                        }
                    } else {
                        echo "que les document de type pdf sont accepter";
                    }
                } else {
                    echo " votre certificat m??dical ou de votre licence ffbb est trop lourd";
                }
            } else {
                var_dump($docCertMedLicence);
                echo "il y a eu une erreur lors du t??l??chargement de votre certificat m??dical ou de votre licence ffbb";
            }
            //treatment of file autorisation_photo
            if ($docAutorisationPhoto['error'] === UPLOAD_ERR_OK) {
                if ($docAutorisationPhoto['size'] <= 1000000) {
                    $tempName = $docAutorisationPhoto['tmp_name'];
                    $destination = './wp-content/uploads/camps/docs/' . $nameAutorisationPhoto;

                    $extention = pathinfo($nameAutorisationPhoto, PATHINFO_EXTENSION);

                    if (in_array($extention, ['pdf', 'PDF'])) {
                        if (move_uploaded_file($tempName, $destination)) {
                            $urlAutorisationPhoto = 'https://www.magalimendy.fr/wp-content/uploads/camps/docs/' . $nameAutorisationPhoto;
                            echo $nameAutorisationPhoto . " was successfully uploaded.";
                        } else {
                            echo "moving of " . $nameAutorisationPhoto . " failed.";
                        }
                    } else {
                        echo $extention . " format is not allowed. please upload a file with pdf extension for autorisation_photo";
                    }

                } else {
                    echo "the uploaded file autorisation_photo is too large. please try compressing";
                }
            } else {
                echo "there was an error uploading the autorisation_photo file to the server";
            }
            //treatment of securite_social file.
            if ($docSecuriteSocial['error'] === UPLOAD_ERR_OK) {
                if ($docSecuriteSocial['size'] <= 1000000) {
                    // this function below gets the extension from the file before going to the loop,
                    $extention = pathinfo($nameSecuriteSocial, PATHINFO_EXTENSION);
                    $tempName = $docSecuriteSocial['tmp_name'];
                    $destination = "./wp-content/uploads/camps/docs/" . $nameSecuriteSocial;
                    if (in_array($extention, array('pdf', 'PDF'))) {
                        if (move_uploaded_file($tempName, $destination)) {
                            $urlSecuriteSocial = "https://www.magalimendy.fr/wp-content/uploads/camps/docs/" . $nameSecuriteSocial;
                            echo " file securite_social was successfully uploaded";

                        } else {
                            echo "there was a problem moving this file";
                        }

                    } else {
                        echo "upload the correct extension in securite_social";
                    }
                } else {
                    echo "file securite_social is too large to be uploaded";
                }
            } else {
                echo "there was an error uploading the securte_social file to the server";
            }

            if($docMutuelle['error']=== UPLOAD_ERR_OK){
                if($docMutuelle['size']<= (1000000)) {
                    $tempName = $docMutuelle['tmp_name'];
                    $destination ='./wp-content/uploads/camps/docs/'.$nameMutuelle;
                    $extention = pathinfo($nameMutuelle, PATHINFO_EXTENSION);
                    if(in_array($extention,['pdf', 'PDF'])){
                        if(move_uploaded_file($tempName,$destination)){
                            $urlJustificatifmutuelle = 'https://www.magalimendy.fr/wp-content/uploads/camps/docs/' . $nameMutuelle;
                            echo "doc mutual  was successfully uploaded.";
                        }
                        else{
                        echo "moving uploaded file 'mutual' failed: ";
                        }
                    }
                    else {
                        echo "the file type ".$extention." is not supported";
                    }
                }
                else {
                    echo "the file justification mutual is too heavy";
                }

            }
            else {
                echo " there was an error uploading justification mutual";
            }
            //treatment of of fiche sanitaire
            if ($docFicheSanitaire ['error'] === UPLOAD_ERR_OK) {
                if ($docFicheSanitaire ['size'] <= 1000000) {
                    $extention =pathinfo($nameFicheSanitaire, PATHINFO_EXTENSION);
                    $tempName = $docFicheSanitaire['tmp_name'];
                    $destination = "./wp-content/uploads/camps/docs/" . $nameFicheSanitaire;

                    if(in_array($extention, array('pdf', 'PDF'))){
                        if(move_uploaded_file($tempName, $destination)){
                            $urlficheSanitaire = "https://www.magalimendy.fr/wp-content/uploads/camps/docs/" .$nameFicheSanitaire;
                            echo "doc mutual  was successfully uploaded.";
                        }
                        else{
                            echo "error moving fiche_sanitaire to the destination";
                        }

                    }
                    else{
                        echo "file extension is not supported";
                    }
        }
        else{
            echo "the file fiche sanitaire is too large";
        }
    }
    else {
        echo " there was an error uploading fiche sanitaire to the server";
    }
}
        //check if the selected camp is available
        //PDO connection to the DB
        $sql = "SELECT * FROM mm_camp
            WHERE id_camp = :id_camp";
        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare($sql);
            $stmp->bindValue(':id_camp', $idCamp);
            $stmt->execute([':id_camp' => $idCamp]);

            $camp = $stmt->fetch(PDO::FETCH_ASSOC);

            $nomCamp = $camp['nom_camp'];
            $numMax = $camp['max_participants'];
            $nombreInscrits = $camp['nombre_inscrits'];
        } catch (PDOException $e) {
            echo "Something went wrong while selectin a camp'.$e.'";
        }

        $conn = null;
        //PDO connection to the DB to insert the participants' information
        //check if there is more space for participants to register
        if ($numMax > $nombreInscrits) {
            $sqlInsert = "INSERT INTO mm_stagiaire (
            date_inscription,
            id_camp,
            id_responsable_legal,
            nom_stagiaire,
            prenom_stagiaire,
            sex_stagiaire,
            mail_stagiaire,
            tel_stagiaire,
            adresse_stagiaire,
            date_naissance,
            lien_cert_med_licence_ffbb,
            lien_consentement_photo,
            lien_securite_social,
            lien_mutuelle,
            lien_fiche_sanitaire,
            demande,
            lien_photo_passport,
            tailles_haut,
            tailles_bas)
             value(
               :date_inscription,
               :id_camp,
               :id_responsable_legal,
               :nom_stagiaire,
               :prenom_stagiaire,
               :sex_stagiaire,
               :mail_stagiaire,
               :tel_stagiaire,
               :adresse_stagiaire,
               :date_naissance,
               :lien_cert_med_licence_ffbb,
               :lien_consentement_photo,
               :lien_securite_social,
               :lien_mutuelle,
               :lien_fiche_sanitaire,
               :demande,
               :lien_photo_passport,
               :tailles_haut,
               :tailles_bas
               )";
            try {
                $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $smt = $conn->prepare($sqlInsert);

                $smt->bindValue(":date_inscription", $today);
                $smt->bindValue(":id_camp", $idCamp, PDO::PARAM_INT);
                $smt->bindValue(":id_responsable_legal",$idRespLegal, PDO::PARAM_INT);
                $smt->bindValue(":nom_stagiaire", $nomStagiaire, PDO::PARAM_STR);
                $smt->bindValue(":prenom_stagiaire", $prenomStagiaire, PDO::PARAM_STR);
                $smt->bindValue(":sex_stagiaire", $sexStagiaire, PDO::PARAM_STR);
                $smt->bindValue(":mail_stagiaire",$mailStagiaire, PDO::PARAM_STR);
                $smt->bindValue(":tel_stagiaire", $telStagiaire, PDO::PARAM_STR);
                $smt->bindValue(":adresse_stagiaire",$adresseStagiaire, PDO::PARAM_STR);
                $smt->bindValue(":date_naissance", $dateNaissance);
                $smt->bindValue(":lien_cert_med_licence_ffbb", $urlCertmedeffbb, PDO::PARAM_STR);
                $smt->bindValue(":lien_consentement_photo",$urlAutorisationPhoto, PDO::PARAM_STR);
                $smt->bindValue(":lien_securite_social", $urlSecuriteSocial, PDO::PARAM_STR);
                $smt->bindValue(":lien_mutuelle", $urlJustificatifmutuelle, PDO::PARAM_STR);
                $smt->bindValue(":lien_fiche_sanitaire", $urlficheSanitaire, PDO::PARAM_STR);
                $smt->bindValue(":demande", $demande, PDO::PARAM_STR);
                $smt->bindValue(":lien_photo_passport", $lien_photo_passport, PDO::PARAM_STR);
                $smt->bindValue(":tailles_haut", $tailles_haut, PDO::PARAM_STR);
                $smt->bindValue(":tailles_bas", $tailles_bas, PDO::PARAM_STR);
                
                $smt->execute();

            } catch (PDOException $e) {
                echo "insertion to the tabel mm_stagiaire failed '.$e.';";
            }
        } else {
            //complete
            echo "there is slots available for this camp";
        }
        $conn = null;

        try {
            $updateCamp = "UPDATE mm_camp 
            SET `nombre_inscrits` = :nombre_inscrits
            WHERE id_camp = :id_camp";

            $nombreInscrits = $nombreInscrits + 1;

            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $smt = $conn->prepare($updateCamp);
            $smt->bindValue(":nombre_inscrits", $nombreInscrits);
            $smt->bindValue(":id_camp", $idCamp );
            $smt->execute();
            header("location: www.magalimendy.fr/welcome");
            exit();

        } catch (PDOException $e) {
            echo "erro updationg mm_camp  ". $e;
        }
      $conn=null;

        
        //update the number of subscriber
    

    }

    $content_inscription = '

    <style>
  
    </style>
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
        <div class="col">
        <label for="sex_stagiaire" class label label-#fff>Votre genre  *</label>
        <select class="form-select" aria-label="Sex" name="sex_stagiaire" required="required" placeholder="xs">
        <option selected>' . $sexStagiaire . '</option>
        <option value="gar??on">Gar??on</option>
        <option value="fille">Fille</option>
        </select>
        </div>
        </div>
        <br>

        <div class="row">
        <div class="col">
        <label for="">Votre email *</label>
        <input type="email" name="mail_stagiaire" id="email_stagiaire" class="form-control" required="required" placeholder="email@you.com" value="' . $mailStagiaire . '">
        </div>
        <div class="col">
        <label for="tel_stagiaire">Votre t??l??phone*</label>
        <input type="text" name="tel_stagiaire" id="tel_stagiaire" class="form-control" required="required" value="' . $telStagiaire . '">
        </div>
        </div>
        <br
        <div class="row">
        <div class="col">
        <label for="adresse_stagiaire">Votre adresse *</label>
         <input type="text" name="adresse_stagiaire" id="nom_stagiaire" class="form-control" required="required" value="' . $adresseStagiaire . '" placeholder ="15 Rue st. Vincent 4800 Nantes" >
        </div>
        </div>
        <br>
        <div class="row">
        <div class="col">
        <label for="date_naissance" >Date de naissance *</label>
        <input type="date" name="date_naissance" class="form-control" required="required" value=" ' . $dateNaissance . '">
        </div>
        <div class="col">
        <label for="camp_selectionne" >S??lectionnez votre camp *</label>
        <select class="form-select" aria-label="Camp" name="camp_selectioner" required="required">
        <option selected value="' . $idCamp . '" >' . $nomCamp . '</option>';

    //seelect all the camps partcipants can register
    //the camps that has status "publi??"
    $sqlSelectCamp = "SELECT * FROM mm_camp ORDER BY id_camp DESC LIMIT 8";
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
        $list = $conn->query($sqlSelectCamp);
        if ($list) {
            while ($row = $list->fetch()) {
                $content_inscription .= '<option value="' . $row['id_camp'] . '">' . $row['nom_camp'] . '</option>';
            }
        } else {
            echo "pas de camp ?? s??lectionner";
        }

    } catch (PDOException $e) {
        echo "Error while selecting all camps from the data base: '.$e.'";
    }

    $content_inscription .= '
     </select>
    </div>
    </div>
    <br>

    <div class="row">
        <h4>Tailles de v??tements</h4> 
        <div class="row">
        <div class="col">
        <label for="tailles_haut" >tailles de haut  *</label>
        <select class="form-select" name="tailles_haut" required="required" placeholder="xs">
            <option value="xs">xs</option>
            <option value="s">s</option>
            <option value="m">m</option>
            <option value="l">l</option>
        <option value="xl">xl</option>
        <option value="xxl">xxl</option>
      </select>
      </div>
        <div class="col">
        <label for="tailles_bas"  >tailles de bas  *</label>
        <select class="form-select" name="tailles_bas" required="required" placeholder="xs">
        <option value="xs">xs</option>
        <option value="s">s</option>
        <option value="m">m</option>
        <option value="l">l</option>
        <option value="xl">xl</option>
        <option value="xxl">xxl</option>
      </select>
        </div>
        </div>
        </div>
        <br />



    <h4>T??l??charger les documents</h4>
    <p>! seuls les documents de type PDF sont accept??s</p>
    <p>documents types peuvent ??tre t??l??charg??s 
    <a href="https://www.magalimendy.fr/les-document-et-exemplaire">ICI</a>
    </p>
    <br>
    <div class="row">
    <div class="col">
    <label for="cert_mede_ffbb"  >Certificat medical ou Licence FBB *</label>
    <input type="file" name="cert_mede_ffbb" class="form-control" accept=".pdf" required="required">
    </div>
    <div class="col">
    <label for="autorisation_photo"  >Consentement ?? la publication de pohto *</label>
    <input type="file" name="autorisation_photo" class="form-control" accept=".pdf" required="required">
    </div>
    </div>
    <br>
    <div class="row">
    <div class="col">
    <label for="securite_social" >Attestation de s??curit?? sociale *</label>
    <input type="file" name="securite_social" class="form-control" accept=".pdf" required="required">
    </div>
    <div class="col">
    <label for="mutuelle" >Justificatif de mutuelle </label>
    <input type="file" name=" mutuelle" class="form-control" accept=".pdf">
    </div>
    <div class="col">
    <label for="fiche_sanitaire" >Fiche sanitaire *</label>
    <input type="file" name="fiche_sanitaire" class="form-control" accept=".pdf" required="required">
    </div>
    </div>
    <br>
    <div class="row">
    <div class="col">
    <label for="demande" >message *</label>
    <textarea class="form-control" name="demande" id="demande" rows="6">Votre demande personnelle</textarea>
    </div>
    <div class="col">
    <h4> Info personne ?? contacter en cas d\'urgence</h4>
        <br>
        <p> compl??ter cette partie saulment si vous ??tes minore</p>
        <div class="row">
        <div class="col">
        <label for="nom_responsable_legal" >Nom </label>
        <input type="text" name="nom_responsable_legal" class="form-control" value=" ' . $nomStagiaire . '">
        </div>
        <div class="col">
        <label for="prenom_responsable_legal" pr??nom </label>
        <input type="text" name="prenom_responsable_legal" class="form-control" value=" ' . $prenomResponsablelegal . '">
        </div>
        </div>
        <div class="row">
        <div class="col">
        <label for="nom_responsable_legal" >Num??ro tel</label>
        <input type="text" name="tel_responsable_legal" class="form-control" value=" ' . $nomResponsablelegal . '">
        </div>
        <div class="col">
        <label for="email_responsable_legal" >E-mail </label>
        <input type="email" name="email_responsable_legal" class="form-control" value=" ' . $emailResponsablelegal . '">
        </div>
        </div>
        <label for="adresse_responsable_legal" >Adresse </label>
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
