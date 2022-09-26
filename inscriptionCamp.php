<?php
/**
 * Plugin Name:       kipdev_inscription_camp
 * description_camp:       instal to create and manage camps. also helps to generate an article from the information available
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
    $idCpt = $postid = get_the_ID();
    $categorie = get_the_category($post->ID);
    var_dump($categorie);

    if(($_POST)&& isset($_POST['btn_register_camp'])){
        //recover variables from tabble
        $nomstagiaire = $_POST['nom_stagiaire'];
        $Prenomstagiaire = $_POST['Prenomstagiaire'];
        $dateNaissance = $_POST['date_naissance'];

        //treatment images
        if($_FILES){
            $autorisationPhoto = $_FILES['autorisation_photo'];

        }

        //select camp where id_cpt= $idCpt
        //prepare slq
        $sql= "SELECT camp_name FROM camp WHERE id_cpt= $idCpt";

        //PDO connection to the DB

        //binding of data declaire some variables
        //close connection


        //get user data 
        ///nom....prenom....Date_naisance

        //PDO connection_aborted//
        //Insert data into the data base
        //table responsable 
        //get(lastInserted) for responsable legaga

        //insert into the next table stagiaires 
        


    }
    $content_inscription='';
    $content_inscription='
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <div class="container">
        <h3>inscription camp</h3>
        <div class="form_group">
            <form action="/inscription_camp">

                <div class="row">
                    <div class="col">
                        <label for="nom_stagiaire">Votre NOM *</label>
                        <input type="text" name="nom_stagiaire" id="nom_stagiaire" class="form-control" required="required" value="' . $nomstagiaire . '">
                    </div>
                    <div class="col">
                        <label for="prenom_stagiaire">Votre Prénom *</label>
                        <input type="text" name="prenom_stagiaire" class="form-control" required="required" value=" '.$Prenomstagiaire.'">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <label for="date_naissance">Date de naissance *</label>
                        <input type="date" name="date_naissance" class="form-control" required="required" value=" '.$dateNaissance.'">
                    </div>
                    <div class="col">
                        <label for="camp_selectionne">Sélectionnez votre camp *</label>
                        <select class="form-select" aria-label="Camp" name="camp_selectioner" required="required">
                        <option selected>' . $categorie . '</option>
                        <option value="juene">Jeune</option>
                        <option value="pro">Pro</option>
                        <option value="élite">Elite</option>
                      </select>
                    </div>
                </div>
                <br>
                <h4>Télécharger les documents</h4>
                <p>! seuls les documents de type PDF sont acceptés</p>

                <div class="row">
                    <div class="col">
                        <label for="cert_mede_ffbb">Certificat medical ou Licence FBB *</label>
                        <input type="file" name="cert_mede_ffbb" class="form-control" accept=".pdf" required="required">
                    </div>
                    <div class="col">
                        <label for="justificatif_qf">Justificatif de quotient familial *</label>
                        <input type="file" name="justificatif_qf" class="form-control" accept=".pdf" required="required">
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
                        <label for="demande">Fiche sanitaire *</label>
                        <textarea class="form-control" name="Demende" id="demande" rows="6">Votre demande personnelle</textarea>
                    </div>
                    <div class="col">
                        <h4> Info responsable legal</h4>
                        <div class="row">
                            <div class="col">
                                <label for="nom_responsable_legal">Nom </label>
                                <input type="text" name="nom_responsable_legal" class="form-control" value=" '.$Prenomstagiaire.'">
                            </div>
                            <div class="col">
                                <label for="prenom_responsable_legal">prénom </label>
                                <input type="text" name="prenom_responsable_legal" class="form-control" value=" '.$Prenomstagiaire.'">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="nom_responsable_legal">Numéro tel</label>
                                <input type="text" name="tel_responsable_legal" class="form-control" value=" '.$Prenomstagiaire.'">
                            </div>
                            <div class="col">
                                <label for="prenom_responsable_legal">E-mail </label>
                                <input type="email" name="email_responsable_legal" class="form-control" value=" '.$Prenomstagiaire.'">
                            </div>
                        </div>
                        <label for="adresse_responsable_legal">Adresse </label>
                        <input type="text" name="adresse_responsable_legal" class="form-control" value=" '.$Prenomstagiaire.'">
                    </div>
                </div>
                <br>
                <input type="submit" class="btn btn-primary" value="Soumtre" name = "bt_register_camp">

            </form>
        </div>

    </div>
  ';
  return $content_inscription;
}
add_shortcode('kipdev_inscription', 'inscriptionCamp');