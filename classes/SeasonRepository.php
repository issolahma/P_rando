<?php
/**
 * Created by PhpStorm.
 * User: issolahma
 * Date: 06.12.2017
 * Time: 15:43
 */
include_once 'database/DataBaseQuery.php';

class SeasonRepository {

    /**
     * Find all entries for the list
     *
     * @return array|resource
     */
    public function findAll($post) {
        $searchValue = htmlentities($post["search"]["value"]);

        $query = 'SELECT * FROM t_season ';


        if(!empty($post["search"]["value"])){
            $query .= 'WHERE seaName LIKE "%'.$searchValue.'%" ';
        }

        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); // 0/1/2

            switch($orderCol) {
                case 0:
                    $orderCol = 'seaName';
                    break;
                default:
                    $orderCol = 'seaName';

            }

            $orderDir = htmlentities($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY seaName ASC ';
        }

        if($post["length"] != -1){
            $start = htmlentities($post['start']);
            $length = htmlentities($post['length']);

            $query .= 'LIMIT ' . $start . ', ' . $length;
        }

        $request =  new DataBaseQuery();

        return $request->rawQuery($query, null);

    }

    /**
     * Find one season by name
     *
     * @param $firstname
     * @param $lastname
     * @return array
     */
    public function findSeason($name){
        $query = 'SELECT * FROM t_season WHERE seaName=:name';

        $dataArray = array(
            'name' => $name
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }


    /**
     *  Find one season by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_season WHERE idSeason=:id LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }

//TODO	
	public function updateSeason($values) {
		$request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);

        $query = 'UPDATE t_season SET (seaName) VALUES (:name)';

        $dataArray = array(
            'name' => $name
        );
        
        return $request->update($query, $dataArray);
		}

    public function addSeason($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $firstname = htmlentities($values['firstname']);
        $lastname = htmlentities($values['lastname']);
        $right = htmlentities($values['right']);
			$login = htmlentities($values['login']);
			$pwd = htmlentities($values['password']);
			
        $query = 'INSERT INTO t_season (accPwd, accFirstName, accLastName, accLogin, accRight, accCreateBy) VALUES (:accPwd, :firstname, :lastname, :accLogin, :accRight, :acccreateBy)';
error_log('LOL: '.$firstname.' '.$lastname.' '.$right.' '.$login);
        $dataArray = array(
        		'accPwd' => md5($pwd),
            'firstname' => $firstname,
            'lastname' => $lastname,
            'accLogin' => $login,
            'accRight' => $right,
            'acccreateBy' => $_SESSION['user']['id']
        );
        
        return $request->rawQuery($query, $dataArray);
    }

    public function hideOne($id){
        $query = 'UPDATE t_season SET accActive=0 WHERE idClient=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }
}