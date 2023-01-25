<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Membre extends \Core\Model
{

    protected static $fillable = ['nom', 'prenom', 'phone', 'adresse', 'zipCode', 'username', 'password'];
    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        $pdo = static::getDB();
        $stmt = $pdo->query('SELECT * FROM membre');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMembre($id)
    {
        $pdo = static::getDB();
        $stmt = $pdo->query("SELECT * FROM membre WHERE idMembre = $id");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function checkMembre($data) {
        extract($data);
        $pdo = static::getDB();
        $stmt = $pdo->prepare('SELECT * FROM Membre WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();

        if($count == 1){
            if(password_verify($password, $user['password'])){

                session_regenerate_id();
                $_SESSION['user_id'] = $user['idMembre'];
                //$_SESSION['privilege_id'] = $user_info['privilege_id'];
                $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);

                return true;

            }else{
               return "<ul><li>Verifier le mot de passe</li></ul>";
            }
        }else{
            return "<ul><li>Le nom d'utilisateur n'exist pas</li></ul>";
        }
    }

    public static function insert($data){
        $pdo = static::getDB();

        $data_keys = array_fill_keys(Membre::$fillable, '');
        $data_map = array_intersect_key($data, $data_keys);
        $nomChamp = implode(", ",array_keys($data_map));
        $valeurChamp = ":".implode(", :", array_keys($data_map));
        $stmt = $pdo->prepare("INSERT INTO Membre ($nomChamp) VALUES ($valeurChamp)");

        foreach($data_map as $key=>$value){
            $stmt->bindValue(":$key", $value);
        }
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            die();
        }
    }
}
