<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 10.01.2018
 */

include_once 'database/DataBaseQuery.php';

class AccompanistRepository {

    /**
     * Query to find all data for the accompanist list
     *
     * @return array of accompanist
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlentities($post["search"]["value"]);

        $query = 'SELECT * FROM t_accompanist ';

        //Make the search on firstname or lastname
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE accFirstName LIKE "%'.$searchValue.'%" ';
            $query .= 'OR accLastName LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 1:
                    $orderCol = 'accLastName';
                    break;
                case 2:
                    $orderCol = 'accFirstName';
                    break;
                case 3:
                    $orderCol = 'accRight';
                    break;
                default:
                    $orderCol = 'accLastName';
            }

            //Order direction Asc or Desc
            $orderDir = htmlentities($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY accLastName ASC '; //By default order by lastname asc
        }

        if($post["length"] != -1){
            $start = htmlentities($post['start']);
            $length = htmlentities($post['length']);

            $query .= 'LIMIT ' . $start . ', ' . $length;
        }

        $request =  new DataBaseQuery();

        return $request->rawQuery($query, null);//Null for no dataArray
    }

    /**
     * Find one accompanist by name
     *
     * @param $firstname
     * @param $lastname
     * @return array with accompanist details
     */
    public function findAccompanist($firstname, $lastname){
        $query = 'SELECT * FROM t_accompanist WHERE accFirstName=:firstname AND accLastName=:lastname';

        $dataArray = array(
            'firstname' => $firstname,
            'lastname' => $lastname
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }


    /**
     *  Find one accompanist by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_accompanist WHERE idAccompanist=:id LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }

    /**
     * Update accompanist datas
     *
     * @param $values
     * @return bool
     */
    public function updateAccompanist($values) {
        $request = new DataBaseQuery();

        //Values from $_Post
        $firstname = htmlentities($values['firstname']);
        $lastname = htmlentities($values['lastname']);
        $right = htmlentities($values['right']);
        $login = htmlentities($values['login']);
        $id = htmlentities($values['id']);
        $pwd = htmlentities($values['password']);

        $query = 'UPDATE t_accompanist SET accPwd=:pwd, accFirstName=:firstname, accLastName=:lastname, accLogin=:login, accRight=:accRight, accCreateBy=:accCreateBy WHERE idAccompanist=:id';

        $dataArray = array(
            'pwd' => $pwd,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'accRight' => $right,
            'login' => $login,
            'id' => $id,
            'accCreateBy' => $_SESSION['user']['id']
        );

        return $request->update($query, $dataArray);
    }

    /*
	* add new accompanist
	*
	* @param $values
	* @return
	*/    
    public function addAccompanist($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $firstname = htmlentities($values['firstname']);
        $lastname = htmlentities($values['lastname']);
        $right = htmlentities($values['right']);
        $login = htmlentities($values['login']);
        $pwd = htmlentities($values['password']);

        $query = 'INSERT INTO t_accompanist (accPwd, accFirstName, accLastName, accLogin, accRight, accCreateBy) VALUES (:accPwd, :firstname, :lastname, :accLogin, :accRight, :acccreateBy)';

        $dataArray = array(
            'accPwd' => md5($pwd),
            'firstname' => $firstname,
            'lastname' => $lastname,
            'accLogin' => $login,
            'accRight' => $right,
            'acccreateBy' => $_SESSION['user']['id']
        );

        return $request->insert($query, $dataArray);
    }

    public function updatePwd($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $pwd = htmlentities($values['password']);
        $id = htmlentities($values['id']);

        $query = 'UPDATE t_accompanist SET accPwd=:pwd WHERE idAccompanist=:id';

        $dataArray = array(
            'id' => $id,
            'pwd' => md5($pwd)
        );

        return $request->update($query, $dataArray);
    }

    /*
	* Hide accompanist instead of deleting it
	*
	* @param $id of the accompanist
	* @return
	*/
    public function hideOne($id){
        $query = 'UPDATE t_accompanist SET accActive=0 WHERE idAccompanist=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }
}
