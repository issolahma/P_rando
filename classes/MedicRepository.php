<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
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
        $searchValue = htmlspecialchars($post["search"]["value"]);

        $query = 'SELECT * FROM t_medicament ';

        //Make the search on name
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE medName LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlspecialchars($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 0:
                    $orderCol = 'medName';
                    break;
                default:
                    $orderCol = 'medName';

            }

            //Order direction Asc or Desc
            $orderDir = htmlspecialchars($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY medName ASC '; //By default order by name asc
        }

        if($post["length"] != -1){
            $start = htmlspecialchars($post['start']);
            $length = htmlspecialchars($post['length']);

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
        $query = 'UPDATE t_medicament SET medActive=0 WHERE idMedicament=:id';

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
        $name = htmlspecialchars($values['name']);

        $query = 'INSERT INTO t_medicament (medName, medCreateBy) VALUES (:mName, :createBy)';

        $dataArray = array(
            'mName' => $name,
            'createBy' => $_SESSION['user']['id']
        );
        
        return $request->insert($query, $dataArray);
    }

    /**
     * List all medicaments
     *
     * @return array
     */
    public function listMedicament(){
        $request = new DataBaseQuery();
        
        $query = 'SELECT * FROM t_medicament';
        
        return $request->rawQuery($query, null);
    }

    /**
     * Update a medicament
     *
     * @param $values
     * @return bool
     */
    public function updateMedicament($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlspecialchars($values['name']);
        $id = htmlspecialchars($values['id']);

        $query = 'UPDATE t_medicament SET medName=:mName, medCreateBy=:createBy WHERE idMedicament=:id';

        $dataArray = array(
            'mName' => $name,
            'id' => $id,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->update($query, $dataArray);
    }
}