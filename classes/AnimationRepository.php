<?php
/**
 * Created by PhpStorm.
 * User: issolahma
 * Date: 06.12.2017
 * Time: 15:43
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
        }

        //Order by the choosen column
        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 1:
                    $orderCol = 'aniName';
                    break;
                default:
                    $orderCol = 'aniName';
            }

            //Order dirrection Asc or Desc
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
     * @param $firstname
     * @param $lastname
     * @return array
     */
    public function findAnimation($name){
        $query = 'SELECT * FROM t_animation WHERE aniName=:name';

        $dataArray = array(
            'name' => $name
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
    * @return  
    */
    public function updateAnimation($values) {
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        $duration = htmlentities($values['duration']);
        $list = htmlentities($values['list']);
        $owner = htmlentities($values['owner']);

        $query = 'UPDATE t_animation SET (aniName, aniDuration, aniMatList, aniCreateBy, aniOwner) VALUES (:name, :duration, :matList, :createBy, :owner)';

        $dataArray = array(
            'name' => $name,
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
	* @return
    */
    public function addAnimation($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        $duration = htmlentities($values['duration']);
        $list = htmlentities($values['list']);
        $owner = htmlentities($values['owner']);

        $query = 'INSERT INTO t_animation (aniName, aniDuration, aniMatList, aniCreateBy, aniOwner) VALUES (:name, :duration, :matList, :createBy, :owner)';

        $dataArray = array(
            'name' => $name,
            'duration' => $duration,
            'matList' => $list,
            'owner' => $owner,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->rawQuery($query, $dataArray);
    }

    /*
    * Hide animation instead of deleting it
    *
	* @param $id of the animation
	* @return
    */
    public function hideOne($id){
        $query = 'UPDATE t_animation SET accActive=0 WHERE idAnimation=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }
}