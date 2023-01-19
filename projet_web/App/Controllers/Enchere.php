<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Enchere as modelEch;
use \App\Models\Timbre as modelTimb;

use \Core\Validation;

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
        $timbres = modelTimb::getAll();
        View::renderTemplate('Enchere/creation.html', ['timbres' => $timbres]);
    }

    public function storeAction() {
        $validation = new Validation;
        extract($_POST);
        $validation->name('prixPlancher')->value($prixPlancher)->pattern('int')->required()->max(15);
        $validation->name('quantiteMise')->value($quantiteMise)->pattern('int')->required()->max(2);

        if($validation->isSuccess()){
            $enchere = modelEch::insert($_POST);
            header('Location: http://localhost:8080/projet_web/public/');
        }else{
            $errors = $validation->displayErrors();
            View::renderTemplate('Enchere/creation.html', ['errors' => $errors]);
        }
    }
}
