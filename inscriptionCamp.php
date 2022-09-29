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
    global $post;
    $age_stagiare = '';
    $idCpt = $postid = get_the_ID();
    $categorie = get_the_category($post->ID);
    var_dump($categorie);
    $permalien= get_permalink( $postid , $leavename= false );

    echo $permalien. '<br>';


    if (isset($_POST) && isset($_POST['btn_register_camp'])) {
        //create responsable legal

            $nomResponsablelegal = $_POST['nom_responsable_legal'];
            $prenomResponsablelegal = $_POST['prenom_responsable_legal'];
            $telResponsablelegal = $_POST['tel_responsable_legal'];
            $emailResponsablelegal = $_POST['email_responsable_legal'];
            $adresseResponsablelegal = $_POST['adresse_responsable_legal'];



            printr($_POST);


            $sqlInsertRespLegal = "INSERT INTO mm_responsable_legal (nom_responsable_legal, prenom_responsable_legal, tel_responsable_legal, email_responsable_legal, adresse_responsable_legal)
                                   VALUES (:nom_responsable_legal,
                                           :prenom_responsable_legal,
                                           :tel_responsable_legal,
                                           :email_responsable_legal,
                                           :adresse_responsable_legal)";

            try {
                $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmp = $conn->prepaired($sqlInsertRespLegal);

                $stmp->bindValue('nom_responsable_legal', $nomResponsablelegal, PDO::PARAM_STR);
                $stmp->bindValue('prenom_responsable_legal', $prenomResponsablelegal, PDO::PARAM_STR);
                $stmp->bindValue('tel_responsable_legal', $telResponsablelegal, PDO::PARAM_STR);
                $stmp->bindValue('email_responsable_legal', $emailResponsablelegal, PDO::PARAM_STR);
                $stmp->bindValue('adresse_responsable_legal', $adresseResponsablelegal, PDO::PARAM_STR);

                $stmp->execute();

                $idRespLegal = $conn->lastInsertId();

                echo "<br>";
                echo $id_cpt;

            } catch (PDO $e) {
                echo "problem encountered while connection to the DB:" . $e;
            }
            // the rest of the variables


    }
    if (isset($_GET['id_stagiaire'])) {
        echo "you are now modifying the registration";

    }

    $content_inscription = '
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <div class="container">
    <h3>inscription camp</h3>
    <div class="form_group">
     <form method = "post" action="/inscription_camp" accept-charset="utf-8" enctype="multipart/form-data">

    <div class="row">
    <div class="col">
                        <label for="nom_stagiaire">Votre NOM *</label>
                        <input type="text" name="nom_stagiaire" id="nom_stagiaire" class="form-control" required="required" value="' . $nomstagiaire . '">
                    </div>
                    <div class="col">
                        <label for="prenom_stagiaire">Votre Prenom*</label>
                        <input type="text" name="prenom_stagiaire" id="nom_stagiaire" class="form-control" required="required" value="' . $nomstagiaire . '">
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
                        <option selected> nom camp </option>';

    //seelect all the camps partcipants can register
    //the camps that has status "publié"
    $sqlSelectCamp = "SELECT * FROM mm_camp WHERE 'date_debut'> date(d-m-y) and 'statut' = 'publié'";
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASSWORD);
        $list = $conn->query($sqlSelectCamp);
        if($list->rows) {
        while (mysql_fetch_array($list)) {

            $content_inscription .= '<option value=' . $list['id_camp'] . '>' . $list['nom_camp'] . '</option>';
        }
    }
    echo "no camps to select";

    } catch (PDOException $e) {
        echo "Error while selecting all camps from the data base: '.$e.'";
    }

    $content_inscription .= '
     </select>
    </div>
    </div>
    <br>
    <h4>Télécharger les documents</h4>
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
        <p> compléter cette partie saulment si vous êtes minore</p>
        <div class="row">
        <div class="col">
        <label for="nom_responsable_legal">Nom </label>
        <input type="text" name="nom_responsable_legal" class="form-control" value=" ' . $Prenomstagiaire . '">
        </div>
        <div class="col">
        <label for="prenom_responsable_legal">prénom </label>
        <input type="text" name="prenom_responsable_legal" class="form-control" value=" ' . $Prenomstagiaire . '">
        </div>
        </div>
        <div class="row">
        <div class="col">
        <label for="nom_responsable_legal">Numéro tel</label>
        <input type="text" name="tel_responsable_legal" class="form-control" value=" ' . $Prenomstagiaire . '">
        </div>
        <div class="col">
        <label for="prenom_responsable_legal">E-mail </label>
        <input type="email" name="email_responsable_legal" class="form-control" value=" ' . $Prenomstagiaire . '">
        </div>
        </div>
        <label for="adresse_responsable_legal">Adresse </label>
        <input type="text" name="adresse_responsable_legal" class="form-control" value=" ' . $Prenomstagiaire . '">

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
