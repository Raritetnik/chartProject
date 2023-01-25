<?php

namespace App\Controllers;


use \Core\View;
use \App\Models\Timbre as model;
use \App\Models\Image as modelImage;
use \App\Models\Enchere as modelEnchere;

use \Core\Validation;
use DateTime;
use IntlDateFormatter;


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
        $timbres = model::getAllwithEnchere();
        View::renderTemplate('Timbre/catalogue.html', ['timbres' => $timbres]);
    }

    public function showAction()
    {
        $id = $this->route_params['id'];
        $timbres = model::getTimbre($id);
        setlocale(LC_TIME, 'fr_CA');
        $timbres[0]['dateFin'] = date('d F Y' , strtotime($timbres[0]['dateFin']));
        $timbres[0]['dateDebut'] = date('d F Y' , strtotime($timbres[0]['dateDebut']));
        View::renderTemplate('Timbre/show.html', ['timbre' => $timbres[0]]);
    }

    public function createAction() {
        $timbres = model::getAll();
        View::renderTemplate('Timbre/creation.html', ['timbres' => $timbres]);
    }

    public function storeAction() {
        $validation = new Validation;
        $timbre = $_POST;
        $timbre['idTimbre'] = uniqid();
        $timbre['Membre_id'] = $_SESSION['user_id'];

        extract($timbre);
        $validation->name('tirage')->value($tirage)->pattern('int')->required()->max(3);
        $validation->name('etat')->value($etat)->pattern('alpha')->required()->max(10);

        if($validation->isSuccess()){
            model::insert($timbre);


            $url = Timbre::sauvegarderImage($timbre);
            $data['url'] = "http://localhost:8080/projet_web/public/Assets/img_Timbres/".$url;
            $data['Timbre_id'] = $idTimbre;
            $data['estPrincip'] = 1;
            modelImage::insert($data);
            header('Location: http://localhost:8080/projet_web/public/');
        } else {
            echo ('FFFFFF');
            $errors = $validation->displayErrors();
            print_r($errors);
            View::renderTemplate('Timbre/creation.html', ['errors' => $errors]);
        }
    }

    public function modifierAction()
    {
        $id = $this->route_params['id'];
        $timbres = model::getTimbre($id);
        setlocale(LC_TIME, 'fr_CA');
        $timbres[0]['dateFin'] = date('d F Y' , strtotime($timbres[0]['dateFin']));
        $timbres[0]['dateDebut'] = date('d F Y' , strtotime($timbres[0]['dateDebut']));
        View::renderTemplate('Timbre/edit.html', ['timbre' => $timbres[0]]);
    }

    public function editAction()
    {
        $id = $this->route_params['id'];
        $_POST['idTimbre'] = $id;
        print_r($_POST);
        model::save($_POST);
        header("Location: http://localhost:8080/projet_web/public/timbre/show/$id");
    }

    public function supprimerAction() {
        $id = $this->route_params['id'];
        modelImage::deleteTimbre($id);
        if (count(modelEnchere::getEncheres($id)) > 1) {
            modelEnchere::deleteTimbre($id);
        }
        $timbres = model::delete($id);
    }

    public static function sauvegarderImage($data) {
        $info = pathinfo($_FILES['imageFichier']['name']);
        $ext = $info['extension']; // get the extension of the file

        $filename = tempnam('./Assets/img_Timbres', 'ti_');
        rename($filename, $filename .= ".".$ext);
        unlink($filename);
        move_uploaded_file( $_FILES['imageFichier']['tmp_name'], $filename);

        // Nom fichier
        return (explode('\\', $filename)[count(explode('\\', $filename))-1]);
    }
}
