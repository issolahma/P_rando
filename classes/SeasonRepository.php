<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 10.01.2018
 */

include_once 'database/DataBaseQuery.php';

class SeasonRepository {

    /**
     * Find all entries for the list
     *
     * @return array|resource
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlentities($post["search"]["value"]);

        $query = 'SELECT * FROM t_season ';


        if(!empty($post["search"]["value"])){
            //Make the search on name
            $query .= 'WHERE seaName LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 1:
                    $orderCol = 'seaName';
                    break;
                default:
                    $orderCol = 'seaName';

            }

            //Order direction Asc or Desc
            $orderDir = htmlentities($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY seaName ASC '; //By default order by name asc
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
     * Find one season by name
     *
     * @param $name
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
     * Find one season by id
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

    /**
     * Update season name
     *
     * @param $values
     * @return bool
     */
    public function updateSeason($values) {
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        $id = htmlentities($values['id']);

        $query = 'UPDATE t_season SET seaName=:sName WHERE idSeason=:id';

        $dataArray = array(
            'sName' => $name,
            'id' => $id
        );

        return $request->update($query, $dataArray);
    }

    /**
     * Add a new season
     *
     * @param $values
     * @return array
     */
    public function addSeason($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        
        $query = 'INSERT INTO t_season (seaName, seaCreateBy) VALUES (:sName, :createBy)';

        $dataArray = array(
            'sName' => $name,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->insert($query, $dataArray);
    }

    /**
     * Hide season instead of deleting it
     *
     * @param $id
     * @return bool
     */
    public function hideOne($id){
        $query = 'UPDATE t_season SET seaActive=0 WHERE idSeason=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }
}