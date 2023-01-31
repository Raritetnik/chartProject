<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Enchere extends \Core\Model
{

    protected static $fillable = ['dateDebut', 'dateFin', 'prixPlancher', 'quantiteMise'];
    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $pdo = static::getDB();
        $stmt = $pdo->query('SELECT * FROM Enchere');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getEncheres($id)
    {
        $pdo = static::getDB();
        $stmt = $pdo->prepare("SELECT * FROM Enchere
        WHERE Enchere.Membre_id = :id");

        $stmt->bindValue(":id", $id);
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            die();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * DO THE Comments
     */
    public static function insert($data){
        $pdo = static::getDB();

        $data_keys = array_fill_keys(Enchere::$fillable, '');
        $data_map = array_intersect_key($data, $data_keys);
        $nomChamp = implode(", ",array_keys($data_map));
        $valeurChamp = ":".implode(", :", array_keys($data_map));
        $stmt = $pdo->prepare("INSERT INTO Enchere ($nomChamp) VALUES ($valeurChamp)");

        foreach($data_map as $key=>$value){
            $stmt->bindValue(":$key", $value);
        }
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            die();
        }
        return $pdo->lastInsertId();
    }

    public static function deleteTimbre($id){
        $pdo = static::getDB();
        $stmt = $pdo->prepare("DELETE FROM Enchere
        WHERE Enchere.Timbre_id = :id");

        $stmt->bindValue(":id", $id);
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            die();
        }
    }
}
