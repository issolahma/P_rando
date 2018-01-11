<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

include_once 'database/DataBaseQuery.php';


class SickRepository {

    /**
     * Find all entries for the list
     *
     * @return array|resource
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlentities($post["search"]["value"]);

        $query = 'SELECT * FROM t_sickness ';

        //Make the search on name
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE sicName LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 0:
                    $orderCol = 'sicName';
                    break;
                default:
                    $orderCol = 'sicName';

            }

            //Order dirrection Asc or Desc
            $orderDir = htmlentities($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY sicName ASC '; //By default order by name asc
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
     * Find one sickness by name
     *
     * @param $name
     * @return array
     */
    public function findSickness($name){
        $query = 'SELECT * FROM t_sickness WHERE sicName=:name';

        $dataArray = array(
            'name' => $name
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }


    /**
     *  Find one sickness by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_sickness WHERE idSickness=:id LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }     

    /*
	* Hidde sickness instead of deleting it
	*
	* @param $id of the cllient
	* @return
	*/
    public function hideOne($id){
        $query = 'UPDATE t_sickness SET sicActive=0 WHERE idSickness=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }

    /*
	* add new sickness
	*
	* @param $values
	* @return
	*/ 
    public function addsickness($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);

        $query = 'INSERT INTO t_sickness (sicName, sicCreateBy) VALUES (:name, :createBy)';

        $dataArray = array(
            'name' => $name,
            'createBy' => $_SESSION['user']['id']
        );
        
        return $request->insert($query, $dataArray);
    }

    /**
     * List all sickness
     *
     * @return array
     */
    public function listSickness(){
        $request = new DataBaseQuery();
        
        $query = 'SELECT * FROM t_sickness';
        
        return $request->rawQuery($query, null);
    }

    /**
     * Update a sickness
     *
     * @param $values
     * @return bool
     */
    public function updateSickness($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        $id = htmlentities($values['id']);

        $query = 'UPDATE t_sickness SET sicName=:sName, sicCreateBy=:createBy WHERE idSickness=:id';

        $dataArray = array(
            'sName' => $name,
            'id' => $id,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->update($query, $dataArray);
    }
}