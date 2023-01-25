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

    protected static $fillable = ['idTimbre','titre', 'dateCreation', 'couleur', 'pays', 'etat', 'tirage', 'dimensions', 'certifier', 'Membre_id'];
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

    public static function getAllwithEnchere()
    {
        $pdo = static::getDB();
        $stmt = $pdo->query('SELECT * FROM Timbre
        INNER JOIN Enchere ON Timbre.Enchere_id = Enchere.idEnchere
        INNER JOIN Image ON Timbre.idTimbre = Image.Timbre_id;');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTimbres($id)
    {
        $pdo = static::getDB();
        $stmt = $pdo->query("SELECT * FROM Timbre
        INNER JOIN Image ON Image.Timbre_id = Timbre.idTimbre
        WHERE Timbre.Membre_id = '$id' AND Image.estPrincip = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTimbre($id)
    {
        $pdo = static::getDB();
        $stmt = $pdo->query("SELECT * FROM Timbre
        INNER JOIN Image ON Image.Timbre_id = Timbre.idTimbre
        INNER JOIN Enchere ON Enchere.idEnchere = Timbre.Enchere_id
        WHERE Timbre.idTimbre = '$id' AND Image.estPrincip = 1");
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

    public static function updateEnchereDeTimbre($idEnchere, $idTimbre) {
        $pdo = static::getDB();
        $stmt = $pdo->prepare("UPDATE Timbre SET Timbre.Enchere_id = '$idEnchere' WHERE Timbre.idTimbre = '$idTimbre'");

        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            die();
        }
    }

    public static function save($data) {
        $pdo = static::getDB();
        extract($data);
        $stmt = $pdo->prepare("UPDATE Timbre SET `titre` = '$titre', `couleur` = '$couleur', `pays` = '$pays', `dimensions` = '$dimensions' WHERE Timbre.idTimbre = '$idTimbre'");

        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            die();
        }
    }

    public static function delete($id){
        $pdo = static::getDB();
        $stmt = $pdo->prepare("DELETE FROM Timbre
        WHERE Timbre.idTimbre = '$id'");

        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            die();
        }
    }
}
