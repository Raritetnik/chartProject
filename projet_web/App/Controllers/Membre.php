<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Membre as model;
use \Core\Validation;

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
        View::renderTemplate('Membre/connexion.html');
    }

    public function signUpAction()
    {
        $membres = model::getAll();
        View::renderTemplate('Membre/creation.html', ['membre' => $membres[0]]);
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
                header('Location: http://localhost:8080/projet_web/public/');
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
            header('Location: http://localhost:8080/projet_web/public/membre/login');
        }else{
            $errors = $validation->displayErrors();
            View::renderTemplate('Membre/creation.html', ['errors' => $errors]);
        }
    }

    public function logoutAction() {
        session_destroy();
        header('Location: http://localhost:8080/projet_web/public/');
    }
}
