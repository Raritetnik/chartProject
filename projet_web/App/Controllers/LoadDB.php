<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Timbre;

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
}
