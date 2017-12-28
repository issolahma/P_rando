<?php
/**
 * ETML
 * Date: 01.06.2017
 * Shop
 */

include_once 'database/DataBaseQuery.php';


class MedicRepository {

    /**
     * Find all entries for the list
     *
     * @return array|resource
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlentities($post["search"]["value"]);

        $query = 'SELECT * FROM t_medicament ';

        //Make the search on name
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE medName LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 0:
                    $orderCol = 'medName';
                    break;
                default:
                    $orderCol = 'medName';

            }

            //Order direction Asc or Desc
            $orderDir = htmlentities($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY medName ASC '; //By default order by name asc
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
     * Find one medicament by name
     *
     * @param $name
     * @return array
     */
    public function findMedic($name){
        $query = 'SELECT * FROM t_medicament WHERE medName=:name';

        $dataArray = array(
            'name' => $name
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }


    /**
     *  Find one medicament by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_medicament WHERE idMedicament=:id LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }     

    /*
	* Hidde client instead of deleting it
	*
	* @param $id of the cllient
	* @return
	*/
    public function hideOne($id){
        $query = 'UPDATE t_medicament SET medActive=0 WHERE idClient=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }

    /*
	* add new medicament
	*
	* @param $values
	* @return
	*/ 
    public function addMedicament($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values);  

        $query = 'INSERT INTO t_medicament (medName, medCreateBy) VALUES (:name, :createBy)';

        $dataArray = array(
            'name' => $name,
            'createBy' => $_SESSION['user']['id']
        );
        
        return $request->insert($query, $dataArray);
    }
    
    /**
    * List all medicaments
    *
    * @return
    */
    public function listMedicament(){
        $request = new DataBaseQuery();
        
        $query = 'SELECT * FROM t_medicament';
        
        return $request->rawQuery($query, null);
    }
}