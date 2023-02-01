<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Timbre;
use \App\Models\Favoris;
use Core\Validation;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class LoadDB extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $table = Timbre::getAll();
        $json = json_decode(file_get_contents('php://input'));
        $text = file_get_contents('php://input');
        echo('Input: '.$text);
        echo('<pre>');
        print_r($table);
        echo('</pre>');
    }


    // Filtres de Pays
    public function FiltreParPaysAction()
    {
        $table = Timbre::getAll();

        $json = json_decode(file_get_contents('php://input'));
        $text = file_get_contents('php://input');
        echo('Input: '.$text);
        echo('<pre>');
        print_r($table);
        echo('</pre>');
    }

    public function FiltreAction()
    {
        $json = json_decode(file_get_contents('php://input'));
        $encheres = Timbre::filtrageTimbre($json, 'ASC');
        echo(json_encode($encheres));
    }

    public function rechercheAction()
    {
        $table = Timbre::getAll();

        $json = json_decode(file_get_contents('php://input'));
        $text = file_get_contents('php://input');
        echo('Input: '.$text);
        echo('<pre>');
        print_r($table);
        echo('</pre>');
    }

    public function mettreFavorisAction() {
        $text = file_get_contents('php://input');
        $data = [
            'Membre_id' => $_SESSION['user_id'],
            'Enchere_id' => $text
        ];
        Favoris::insert($data);
        echo ($text);
    }

    public function getTimbresAction() {
        unset($_GET['LoadDB/getTimbres']);
        $valid = new Validation;
        if (isset($_GET['trie'])) {
            $valid->name('trie')->value($_GET['trie'])->pattern('alpha')->required()->max(4);
            $trie = ($valid->isSuccess()) ? $_GET['trie'] : "ASC";
            unset($_GET['trie']);
        } else {
            $trie = 'ASC';
        }

        if(isset($_GET['recherche'])) {
            $timbres = Timbre::recherche($_GET['recherche']);
            $vars['recherche'] = $_GET['recherche'];
        } else if(isset($_GET)) {
            $vars = $_GET;
            echo('FFFFFFF');
            $timbres = Timbre::filtrageTimbre($vars, $trie);
        }
        echo (json_encode($timbres));
    }
}
