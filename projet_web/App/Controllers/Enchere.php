<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Enchere as modelEnch;
use \App\Models\Timbre;

use \Core\Validation;
use \Core\CheckSession;

/**
 * Catalogue controller
 *
 * PHP version 7.0
 */
class Enchere extends \Core\Controller
{

    public function indexAction()
    {
        $encheres = modelEnch::getAll();
        View::renderTemplate('Enchere/index.html', ['encheres' => $encheres]);
    }

    public function createAction() {
        CheckSession::sessionAuth();

        $timbres = Timbre::getAll();
        View::renderTemplate('Enchere/creation.html', ['timbres' => $timbres]);
    }

    public function storeAction() {
        CheckSession::sessionAuth();

        $validation = new Validation;
        extract($_POST);
        $validation->name('prixPlancher')->value($prixPlancher)->pattern('float')->required()->max(15);
        $validation->name('quantiteMise')->value($quantiteMise)->pattern('int')->required()->max(2);

        if($validation->isSuccess()){
            $_POST['Membre_id'] = $_SESSION['user_id'];
            $enchereId = modelEnch::insert($_POST);

            Timbre::updateEnchereDeTimbre($enchereId, $Timbre_id);
            header('Location: http://'.$_SERVER['SERVER_NAME'].':8080/projet_web/public/');
        }else{
            $errors = $validation->displayErrors();
            print_r($errors);
            View::renderTemplate('Enchere/creation.html', ['errors' => $errors]);
        }
    }
}
