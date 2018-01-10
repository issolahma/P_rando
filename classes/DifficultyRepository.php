<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 10.01.2018
 */

include_once 'database/DataBaseQuery.php';

class DifficultyRepository {

    /**
     * Query to find all data for the accompanist list
     *
     * @return array of accompanist
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlentities($post["search"]["value"]);

        $query = 'SELECT * FROM t_difficulty ';

        //Make the search on name
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE difLevel LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 1:
                    $orderCol = 'difLevel';
                    break;
                default:
                    $orderCol = 'difLevel';
            }

            //Order direction Asc or Desc
            $orderDir = htmlentities($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY difLevel ASC '; //By default order by level asc
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
     * Find one difficulty by name
     *
     * @param $name
     * @return array
     */
    public function findDifficulty($name){
        $query = 'SELECT * FROM t_difficulty WHERE difLevel=:dName';

        $dataArray = array(
            'dName' => $name
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }

    /**
     *  Find one difficulty by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_difficulty WHERE idDifficulty=:id LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }

    /**
     * Add a new difficulty
     *
     * @param $values
     * @return string
     */
    public function addDifficulty($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);


        $query = 'INSERT INTO t_difficulty (difLevel, difCreateBy) VALUES (:dLevel, :createBy)';

        $dataArray = array(
            'dLevel' => $name,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->insert($query, $dataArray);
    }

    public function updateDifficulty($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        $id = htmlentities($values['id']);

        $query = 'UPDATE t_difficulty SET difLevel=:dLevel, difCreateBy=:createBy WHERE idDifficulty=:id';

        $dataArray = array(
            'dLevel' => $name,
            'id' => $id,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->update($query, $dataArray);
    }

    /*
* Hide difficulty instead of deleting it
*
* @param $id of the difficulty
* @return
*/
    public function hideOne($id){
        $query = 'UPDATE t_difficulty SET difActive=0 WHERE idDifficulty=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }
}