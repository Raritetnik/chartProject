<?php

namespace App\Controllers;

use \Core\View;
use \Core\CheckSession;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Affichage de la page accueil
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html');
    }
}
