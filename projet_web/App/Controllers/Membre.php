<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Membre as model;
use \App\Models\Timbre as modelTimbre;
use \Core\Validation;
use \Core\CheckSession;

/**
 * Membre controller
 *
 * PHP version 7.0
 */
class Membre extends \Core\Controller
{


    static $directory;
    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        CheckSession::sessionAuth();

        $membre = model::getMembre($_SESSION['user_id']);
        $mises = modelTimbre::getAllMises($_SESSION['user_id']);
        $timbres = modelTimbre::getTimbres($_SESSION['user_id']);
        View::renderTemplate('Membre/index.html', ['membre' => $membre, 'timbres' => $timbres, 'mises' => $mises]);
    }

    public function signUpAction()
    {
        $membres = model::getAll();
        View::renderTemplate('Membre/creation.html');
    }

    public function loginAction()
    {
        View::renderTemplate('Membre/connexion.html');
    }

    public function authAction() {
        $validation = new \Core\Validation;
        extract($_POST);
        $validation->name('username')->value($username)->pattern('email')->required()->max(50);
        $validation->name('password')->value($password)->required();

        if($validation->isSuccess()){


            $checkUser = model::checkMembre($_POST);
            if($checkUser){
                header('Location: http://'.$_SERVER['SERVER_NAME'].'/projet_web/public/');
            } else {
                View::renderTemplate('Membre/connexion.html', ['errors' => $checkUser]);
            }
        }else{
            $errors = $validation->displayErrors();
            View::renderTemplate('Membre/connexion.html', ['errors' => $errors]);
        }
    }

    public function storeAction() {
        $validation = new Validation;
        extract($_POST);
        $validation->name('nom')->value($nom)->pattern('alpha')->required()->max(45);
        $validation->name('prenom')->value($prenom)->pattern('alpha')->required()->max(45);
        $validation->name('zipCode')->value($zipCode)->pattern('alphanum')->required()->max(8);
        $validation->name('adresse')->value($adresse)->pattern('address')->required()->max(70);
        $validation->name('username')->value($username)->pattern('email')->required()->max(50);
        $validation->name('password')->value($password)->max(20)->min(3);

        if($validation->isSuccess()){
            $options = [
                'cost' => 10,
            ];
            $_POST['password']= password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
            $userInsert = model::insert($_POST);
            header('Location: http://'.$_SERVER['SERVER_NAME'].'/projet_web/public/membre/login');
        }else{
            $errors = $validation->displayErrors();
            View::renderTemplate('Membre/creation.html', ['errors' => $errors]);
        }
    }

    public function logoutAction() {
        session_destroy();
        header('Location: http://'.$_SERVER['SERVER_NAME'].'/projet_web/public/');
    }
}
