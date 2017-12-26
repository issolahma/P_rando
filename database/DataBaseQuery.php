<?php
/**
 * ETML
 * Date: 01.06.2017
 * Shop
 */

include_once 'config.ini.php';

class DataBaseQuery
{

    /** @var \PDO $connection */
    private $connection;


    /**
     * Constructor
     */
    public function __construct() {

        $user   = $GLOBALS['MM_CONFIG']['database']['username'];
        $pass   = $GLOBALS['MM_CONFIG']['database']['password'];
        $dbname = $GLOBALS['MM_CONFIG']['database']['dbname'];
        $host   = $GLOBALS['MM_CONFIG']['database']['host'];
        $port   = $GLOBALS['MM_CONFIG']['database']['port'];
        $charset = $GLOBALS['MM_CONFIG']['database']['charset'];

        try
        {
            $this->connection = new \PDO(
                'mysql:host=' . $host .
                ';port=' . $port .
                ';dbname=' . $dbname .
                ";charset=". $charset, $user, $pass,array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Select request
     *
     * @param $query
     * @param $dataArray
     * @param int $mode
     * @return array
     */
    public function rawQuery($query, $dataArray, $mode=PDO::FETCH_ASSOC) {

        $req = $this->connection->prepare($query);

        if(isset($dataArray)) {
            $req->execute($dataArray);
        }
        else{
            $req->execute();
        }
        return $req->fetchAll($mode);

    }

    public function delete($query, $dataArray){
        $req = $this->connection->prepare($query);

        $req->execute($dataArray);
    }

    /**
     * Insert data, and return last id
     *
     * @param $query
     * @param $array
     * @return string
     */
    public function insert($query, $array) {

        $req = $this->connection->prepare($query);

        $req->execute($array);

        return $this->connection->lastInsertId();
    }

    /**
     * @param $query
     * @param $array
     * @return bool
     */
    public function update($query, $array) {

        $req = $this->connection->prepare($query);

        return $req->execute($array);
    }
}
