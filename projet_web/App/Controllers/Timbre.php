<?php

namespace App\Controllers;


use \Core\View;
use \App\Models\Timbre as model;
use \App\Models\Image as modelImage;
use \App\Models\Enchere as modelEnchere;
use \App\Models\Mise as modelMise;


use \Core\Validation;
use \Core\CheckSession;


/**
 * Catalogue controller
 *
 * PHP version 7.0
 */
class Timbre extends \Core\Controller
{

    public function indexAction()
    {
        $timbres = model::getAll();
        View::renderTemplate('Timbre/index.html', ['timbres' => $timbres]);
    }

    public function catalogueAction()
    {
        $vars = [];
        unset($_GET['catalogue']);
        $valid = new Validation;
        if (isset($_GET['trie'])) {
            $valid->name('trie')->value($_GET['trie'])->pattern('alpha')->required()->max(4);
            $trie = ($valid->isSuccess()) ? $_GET['trie'] : "ASC";
            unset($_GET['trie']);
        } else {
            $trie = 'ASC';
        }

        if(isset($_GET['recherche'])) {
            $timbres = model::recherche($_GET['recherche']);
            $vars['recherche'] = $_GET['recherche'];
        } else if(isset($_GET) ) {
            $vars = $_GET;
            $timbres = model::filtrageTimbre($vars, $trie);
        }
        $pays = model::getFiltresPaysTimbre();
        $couleurs = model::getFiltresCouleursTimbre();
        $etats = model::getFiltresEtatTimbre();

        View::renderTemplate('Timbre/catalogue.html', ['timbres' => $timbres, 'pays' => $pays, 'couleurs' => $couleurs, 'etats' => $etats, 'vars' => $vars]);
    }

    public function showAction()
    {
        $id = $this->route_params['id'];
        $timbre = model::getTimbre($id);
        $mise = modelMise::getMise($id);
        if(gettype($mise) == 'boolean' ) {
            $mise = [
                "prixMise" => 0,
                "Prenom" => '-',
                "Nom" => 'Personne'
            ];
        }
        setlocale(LC_TIME, 'fr_CA');
        $timbre['dateFinName'] = date('d F Y h:i:s' , strtotime($timbre['dateFin']));
        $timbre['dateDebutName'] = date('d F Y h:i:s' , strtotime($timbre['dateDebut']));

        View::renderTemplate('Timbre/show.html', ['timbre' => $timbre, 'mise' => $mise]);
    }

    public function createAction() {
        CheckSession::sessionAuth();

        $timbres = model::getAll();
        View::renderTemplate('Timbre/creation.html', ['timbres' => $timbres]);
    }

    public function storeAction() {
        CheckSession::sessionAuth();

        $validation = new Validation;
        $timbre = $_POST;
        $timbre['idTimbre'] = uniqid();
        $timbre['Membre_id'] = $_SESSION['user_id'];

        extract($timbre);
        $validation->name('tirage')->value($tirage)->pattern('int')->required()->max(3);
        $validation->name('etat')->value($etat)->pattern('alpha')->required()->max(10);

        if($validation->isSuccess()){
            model::insert($timbre);


            $url = Timbre::sauvegarderImage();
            $data['url'] = "http://".$_SERVER['SERVER_NAME'].":8080/projet_web/public/Assets/img_Timbres/".$url;
            $data['Timbre_id'] = $idTimbre;
            $data['estPrincip'] = 1;
            modelImage::insert($data);
            header('Location: http://'.$_SERVER['SERVER_NAME'].':8080/projet_web/public/');
        } else {
            $errors = $validation->displayErrors();
            print_r($errors);
            View::renderTemplate('Timbre/creation.html', ['errors' => $errors]);
        }
    }

    public function modifierAction()
    {
        CheckSession::sessionAuth();

        $id = $this->route_params['id'];
        $timbre = model::getTimbre($id);

        setlocale(LC_TIME, 'fr_CA');
        $timbre['dateFinName'] = date('d F Y h:i:s' , strtotime($timbre['dateFin']));
        $timbre['dateDebutName'] = date('d F Y h:i:s' , strtotime($timbre['dateDebut']));
        View::renderTemplate('Timbre/edit.html', ['timbre' => $timbre]);
    }

    public function editAction()
    {
        CheckSession::sessionAuth();

        $id = $this->route_params['id'];
        $_POST['idTimbre'] = $id;
        print_r($_POST);
        model::save($_POST);
        header("Location: http://".$_SERVER['SERVER_NAME'].":8080/projet_web/public/timbre/show/$id");
    }

    public function supprimerAction() {
        CheckSession::sessionAuth();

        $id = $this->route_params['id'];
        modelImage::deleteTimbre($id);
        if (count(modelEnchere::getEncheres($id)) > 1) {
            modelEnchere::deleteTimbre($id);
        }
        $timbres = model::delete($id);
    }

    public static function sauvegarderImage() {

        $info = pathinfo($_FILES['imageFichier']['name']);
        $ext = $info['extension']; // get the extension of the file

        $filename = tempnam('./Assets/img_Timbres', 'ti_');
        rename($filename, $filename .= ".".$ext);
        unlink($filename);
        move_uploaded_file( $_FILES['imageFichier']['tmp_name'], $filename);

        // Nom fichier
        return (explode('\\', $filename)[count(explode('\\', $filename))-1]);
    }



    public function miserAction() {
        $_POST['Timbre_id'] = $this->route_params['id'];
        $_POST['dateMise'] = date('y-m-d h:i:s');
        $_POST['Membre_id'] = $_SESSION['user_id'];
        modelMise::insert($_POST);
        header("Location: http://".$_SERVER['SERVER_NAME'].":8080/projet_web/public/timbre/show/".$_POST['Timbre_id']);
    }
}
