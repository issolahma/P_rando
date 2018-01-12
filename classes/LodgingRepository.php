<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

include_once 'database/DataBaseQuery.php';

class LodgingRepository {

    /**
     * Query to find all data for the lodging list
     *
     * @return array of accompanist
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlspecialchars($post["search"]["value"]);

        $query = 'SELECT * FROM t_lodging ';

        //Make the search on name
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE lodName LIKE "%'.$searchValue.'%" ';
            $query .= 'OR lodPlace LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlspecialchars($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 1:
                    $orderCol = 'lodName';
                    break;
                case 2:
                    $orderCol = 'lodPlace';
                    break;
                default:
                    $orderCol = 'lodName';
            }

            //Order direction Asc or Desc
            $orderDir = htmlspecialchars($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY lodName ASC '; //By default order by name asc
        }

        if($post["length"] != -1){
            $start = htmlspecialchars($post['start']);
            $length = htmlspecialchars($post['length']);

            $query .= 'LIMIT ' . $start . ', ' . $length;
        }

        $request =  new DataBaseQuery();

        return $request->rawQuery($query, null);//Null for no dataArray
    }

    /**
     * Find one lodging by name
     *
     * @param $name
     * @return array
     */
    public function findLodging($name){
        $query = 'SELECT * FROM t_lodging WHERE lodName=:lName';

        $dataArray = array(
            'lName' => $name
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }

    /**
     *  Find one lodging by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_lodging WHERE idLodging=:id LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }

    /**
     * Add a new lodging
     *
     * @param $values
     * @return string
     */
    public function addLodging($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlspecialchars($values['name']);
        $place = htmlspecialchars($values['place']);

        $query = 'INSERT INTO t_lodging (lodName, lodPlace, lodCreateBy) VALUES (:dLevel, :place, :createBy)';

        $dataArray = array(
            'dLevel' => $name,
            'place' => $place,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->insert($query, $dataArray);
    }

    /**
     * Update a lodging
     *
     * @param $values
     * @return bool
     */
    public function updateLodging($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlspecialchars($values['name']);
        $place = htmlspecialchars($values['place']);
        $id = htmlspecialchars($values['id']);

        $query = 'UPDATE t_lodging SET lodName=:dLevel, lodPlace=:place, lodCreateBy=:createBy WHERE idLodging=:id';

        $dataArray = array(
            'dLevel' => $name,
            'place' => $place,
            'id' => $id,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->update($query, $dataArray);
    }

    /*
* Hide lodging instead of deleting it
*
* @param $id of the lodging
* @return
*/
    public function hideOne($id){
        $query = 'UPDATE t_lodging SET lodActive=0 WHERE idLodging=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }
}