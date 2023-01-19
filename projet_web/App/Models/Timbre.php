<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Timbre extends \Core\Model
{

    protected static $fillable = ['idTimbre','titre', 'dateCreation', 'couleur', 'pays', 'etat', 'tirage', 'dimensions', 'certifier'];
    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $pdo = static::getDB();
        $stmt = $pdo->query('SELECT * FROM Timbre');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTimbre()
    {
        $pdo = static::getDB();
        $stmt = $pdo->query('SELECT * FROM Timbre');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insert($data){
        $pdo = static::getDB();

        $data_keys = array_fill_keys(Timbre::$fillable, '');
        $data_map = array_intersect_key($data, $data_keys);
        $nomChamp = implode(", ",array_keys($data_map));
        $valeurChamp = ":".implode(", :", array_keys($data_map));
        $stmt = $pdo->prepare("INSERT INTO timbre ($nomChamp) VALUES ($valeurChamp)");

        foreach($data_map as $key=>$value){
            $stmt->bindValue(":$key", $value);
        }

        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            die();
        }
    }
}