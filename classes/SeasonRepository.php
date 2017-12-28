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
                case 0:
                    $orderCol = 'seaName';
                    break;
                default:
                    $orderCol = 'seaName';

            }

            //Order dirrection Asc or Desc
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

    /**
    * update season datas
    *
    * @param $values
    * @return
    */
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

    /**
    * Add a new season
    *
    * @param $values
    * @return
    */
    public function addSeason($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        
        $query = 'INSERT INTO t_season (seaName, seaCreateBy) VALUES (:name, :createBy)';
        error_log('LOL: '.$firstname.' '.$lastname.' '.$right.' '.$login);
        $dataArray = array(
            'name' => $name,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->rawQuery($query, $dataArray);
    }

    /**
    * Hide season instead of deleting it
    *
    * @param $id
    * @return
    */
    public function hideOne($id){
        $query = 'UPDATE t_season SET accActive=0 WHERE idSeason=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }
}