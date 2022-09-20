<?php

namespace SYRADEV\Utils\Database;

use PDO, PDOException;

class PdoDb {

    private static $connect = null;
    private $conx;

    private function __construct() {

        global $conf;

        try {
            $this->conx = new PDO('mysql:host='.$conf['db']['host'].';dbname='.$conf['db']['database'], $conf['db']['user'], $conf['db']['password'], [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
            $this->conx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            $message = 'Erreur ! ' . $e->getMessage() . '<hr />';
            die($message);
        }
    }

    public static function getInstance()
    {
        if (is_null(self::$connect)) {
            self::$connect = new PdoDb();
        }
        return self::$connect;
    }

    public function requete($sql, $fetchMethod='fetchAll') {
        try {
            $result = $this->conx->query($sql, PDO::FETCH_ASSOC)->{$fetchMethod}();
        } catch(PDOException $e) {
            $message = 'Erreur ! ' . $e->getMessage() . '<hr />';
            die($message);
        }
        return $result;
    }

    // Insert des données dans une table
    public function inserer($table, $data) {
        // On convertit l'objet en tableau
        $dataTab = get_object_vars($data);
        // On récupère les nom de champs dans les clés du tableau
        $fields = array_keys($dataTab);
        // On récupère les valeurs
        $values = array_values($dataTab);

        // On compte le nombre de champ
        $values_count = count($values);

        // On construit la chaine des paramètres ':p0,:p1,:p2,...'
        $params = [];
        foreach ($values as $key => $value) {
            array_push($params, ':p' . $key);
        }
        $params_str = implode(',', $params);

        // On prépare la requête
        $reqInsert = 'INSERT INTO ' . $table . '('. implode(',',$fields).')';
        $reqInsert .= ' VALUES('.$params_str.')';
        $prepared = $this->conx->prepare($reqInsert);

        // On injecte dans la requête les données avec leur type.
        for($i=0;$i<$values_count;$i++) {
            switch(gettype($values[$i])) {
                case 'NULL':
                    $type = PDO::PARAM_NULL;
                    break;
                case 'integer':
                    $type = PDO::PARAM_INT;
                    break;
                case 'boolean':
                    $type = PDO::PARAM_BOOL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
            // On lie une valeur au paramètre :pX
            $prepared->bindParam(':p'.$i, $values[$i], $type);
        }

        // On exécute la requête.
        // Retourne TRUE en cas de succès ou FALSE en cas d'échec.
        return $prepared->execute();
    }

    // Met à jour une donnée dans une table
    public function maj($table, $data) {

    }

    // Retourne l'id de la dernière insertion par auto-incrément dans la base de données
    public function dernierIndex()
    {
        return $this->conx->lastInsertId();
    }

    public function __destruct() {
        $this->conx = null;
    }
}