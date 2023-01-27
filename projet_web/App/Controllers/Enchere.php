<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Enchere as modelEch;
use \App\Models\Timbre as modelTimb;

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
        $encheres = modelEch::getAll();
        View::renderTemplate('Enchere/index.html', ['encheres' => $encheres]);
    }

    public function createAction() {
        CheckSession::sessionAuth();

        $timbres = modelTimb::getAll();
        View::renderTemplate('Enchere/creation.html', ['timbres' => $timbres]);
    }

    public function storeAction() {
        CheckSession::sessionAuth();

        $validation = new Validation;
        extract($_POST);
        $validation->name('prixPlancher')->value($prixPlancher)->pattern('float')->required()->max(15);
        $validation->name('quantiteMise')->value($quantiteMise)->pattern('int')->required()->max(2);

        if($validation->isSuccess()){
            $enchereId = modelEch::insert($_POST);
            $timbreId = $_POST['Timbre_id'];
            modelTimb::updateEnchereDeTimbre($enchereId, $timbreId);
            header('Location: http://localhost/projet_web/public/');
        }else{
            $errors = $validation->displayErrors();
            print_r($errors);
            View::renderTemplate('Enchere/creation.html', ['errors' => $errors]);
        }
    }
}
