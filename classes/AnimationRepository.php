<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

include_once 'database/DataBaseQuery.php';

class AnimationRepository {

    /**
     * Find all entries for the list
     *
     * @return array|resource
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlentities($post["search"]["value"]);

        $query = 'SELECT * FROM t_animation ';

        //Make the search on name
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE aniName LIKE "%'.$searchValue.'%" ';
            $query .= 'OR aniOwner LIKE "%'.$searchValue.'%" ';
            $query .= 'OR aniDuration LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 1:
                    $orderCol = 'aniName';
                    break;
                case 2:
                    $orderCol = 'aniOwner';
                    break;
                case 3:
                    $orderCol = 'aniDuration';
                    break;
                default:
                    $orderCol = 'aniName';
            }

            //Order direction Asc or Desc
            $orderDir = htmlentities($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY aniName ASC '; //By default order by name asc
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
     * Find one animation by name
     *
     * @param $name
     * @return array
     */
    public function findAnimation($name){
        $query = 'SELECT * FROM t_animation WHERE aniName=:aName';

        $dataArray = array(
            'aName' => $name
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }


    /**
     * Find one animation by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_animation WHERE idAnimation=:id LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }

    /**
     * Update animation
     *
     * @param $values
     * @return bool
     */
    public function updateAnimation($values) {
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        $duration = htmlentities($values['duration']);
        $list = htmlentities($values['matList']);
        $owner = htmlentities($values['owner']);

        $query = 'UPDATE t_animation SET aniName=:aName, aniDuration=:duration, aniMatList=:matList, aniCreateBy=:createBy, aniOwner=:owner';

        $dataArray = array(
            'aName' => $name,
            'duration' => $duration,
            'matList' => $list,
            'owner' => $owner,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->update($query, $dataArray);
    }

    /**
     * Add new animation
     *
     * @param $values
     * @return array
     */
    public function addAnimation($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        $duration = htmlentities($values['duration']);
        $list = htmlentities($values['matList']);
        $owner = htmlentities($values['owner']);

        $query = 'INSERT INTO t_animation (aniName, aniDuration, aniMatList, aniCreateBy, aniOwner) VALUES (:aName, :duration, :matList, :createBy, :owner)';

        $dataArray = array(
            'aName' => $name,
            'duration' => $duration,
            'matList' => $list,
            'owner' => $owner,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->insert($query, $dataArray);
    }

    /*
    * Hide animation instead of deleting it
    *
	* @param $id of the animation
	* @return
    */
    public function hideOne($id){
        $query = 'UPDATE t_animation SET aniActive=0 WHERE idAnimation=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }
}