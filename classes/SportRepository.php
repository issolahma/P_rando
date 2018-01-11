<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

include_once 'database/DataBaseQuery.php';


class SportRepository {

    /**
     * Find all entries for the list
     *
     * @return array|resource
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlentities($post["search"]["value"]);

        $query = 'SELECT * FROM t_sport ';

        //Make the search on name
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE spoName LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 0:
                    $orderCol = 'spoName';
                    break;
                default:
                    $orderCol = 'spoName';

            }

            //Order direction Asc or Desc
            $orderDir = htmlentities($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY spoName ASC '; //By default order by name asc
        }

        if($post["length"] != -1){
            $start = htmlentities($post['start']);
            $length = htmlentities($post['length']);

            $query .= 'LIMIT ' . $start . ', ' . $length;
        }

        $request =  new DataBaseQuery();

        return $request->rawQuery($query, null); //Null for no dataArray

    }

    /**
     * Find one sport by name
     *
     * @param $name
     * @return array
     */
    public function findSport($name){
        $query = 'SELECT * FROM t_sport WHERE spoName=:name';

        $dataArray = array(
            'name' => $name
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }


    /**
     *  Find one sport by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_sport WHERE idSport=:id LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }

    /*
	* Hide sport instead of deleting it
	*
	* @param $id of the sport
	* @return
	*/
    public function hideOne($id){
        $query = 'UPDATE t_sport SET spoActive=0 WHERE idSport=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }

    /*
	* add new sport
	*
	* @param $values
	* @return
	*/
    public function addSport($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);

        $query = 'INSERT INTO t_sport (spoName, spoCreateBy) VALUES (:sName, :createBy)';

        $dataArray = array(
            'sName' => $name,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->insert($query, $dataArray);
    }

    /**
     * List all sport
     *
     * @return array
     */
    public function listSport(){
        $request = new DataBaseQuery();

        $query = 'SELECT * FROM t_sport';

        return $request->rawQuery($query, null);
    }

    /**
     * Update a sport
     *
     * @param $values
     * @return bool
     */
    public function updateSport($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        $id = htmlentities($values['id']);

        $query = 'UPDATE t_sport SET spoName=:sName, spoCreateBy=:createBy WHERE idSport=:id';

        $dataArray = array(
            'sName' => $name,
            'id' => $id,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->update($query, $dataArray);
    }
}