<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

include_once 'database/DataBaseQuery.php';
include_once 'classes/DifficultyRepository.php';
include_once 'classes/SportRepository.php';

class RouteRepository {

    /**
     * Query to find all data for the route list
     *
     * @return array|resource
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlspecialchars($post["search"]["value"]);

        $query = 'SELECT * FROM t_route ';

        //For the search input
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE rouName LIKE "%'.$searchValue.'%" ';
            $query .= 'OR rouLocation LIKE "%'.$searchValue.'%" ';
            $query .= 'OR rouNbClient LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlspecialchars($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 1:
                    $orderCol = 'rouName';
                    break;
                case 2:
                    $orderCol = 'rouLocation';
                    break;
                case 3:
                    $orderCol = 'rouNbClient';
                    break;
                default:
                    $orderCol = 'rouName';

            }

            //Order direction Asc or Desc
            $orderDir = htmlspecialchars($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY rouName ASC '; //By default order by name asc
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
     * Find one route by name
     *
     * @param $name
     * @return array
     */
    public function findRoute($name){
        $query = 'SELECT * FROM t_route WHERE rouName=:rName';

        $dataArray = array(
            'rName' => $name
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }


    /**
     *  Find one route by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_route WHERE idRoute=:id LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }

    /*
	* Hide route instead of deleting it
	*
	* @param $id of the route
	* @return
	*/
    public function hideOne($id){
        $query = 'UPDATE t_route SET rouActive=0 WHERE idRoute=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }


    /*
	* Add new route, and his sport and difficulty
	*
	* @param $values
	* @return
	*/
    public function addRoute($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlspecialchars($values['name']);
        $description = htmlspecialchars($values['describ']);
        $dropPos = htmlspecialchars($values['dropPos']);
        $dropNeg = htmlspecialchars($values['dropNeg']);
        $maxElev = htmlspecialchars($values['maxAlt']);
        $duration = htmlspecialchars($values['duration']);
        $nbClient = htmlspecialchars($values['nbClient']);
        $danger = htmlspecialchars($values['danger']);
        $gps = htmlspecialchars($values['gps']);
        $location = htmlspecialchars($values['place']);
        $altern = htmlspecialchars($values['altern']);
        $diffId = htmlspecialchars($values['ddDiff']);
        $sportId = htmlspecialchars($values['ddSport']);

        $query = 'INSERT INTO t_route (rouName, rouDescription, rouPosDrop, rouNegDrop, rouMaxElevation, rouDuration, rouNbClient, rouDanger, rouGpsFile, rouLocation, rouAltern, idSport, idDifficulty, rouCreateBy) VALUES (:rName, :description, :posDrop, :negDrop, :maxElevation, :duration, :nbClient, :danger, :gps, :location, :altern, :idSport, :idDiff, :createBy)';

        $dataArray = array(
            'rName' => $name,
            'description' => $description,
            'posDrop' => $dropPos,
            'negDrop' => $dropNeg,
            'maxElevation' => $maxElev,
            'duration' => $duration,
            'nbClient' => $nbClient,
            'danger' => $danger,
            'gps' => $gps,
            'location' => $location,
            'altern' => $altern,
            'idDiff' => $diffId,
            'idSport' => $sportId,
            'createBy' => $_SESSION['user']['id']
        );

        $request->insert($query, $dataArray);
    }


    /**
     * Update one route
     *
     * @param $values
     */
    public function updateRoute($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlspecialchars($values['name']);
        $description = htmlspecialchars($values['describ']);
        $dropPos = htmlspecialchars($values['dropPos']);
        $dropNeg = htmlspecialchars($values['dropNeg']);
        $maxElev = htmlspecialchars($values['maxAlt']);
        $duration = htmlspecialchars($values['duration']);
        $nbClient = htmlspecialchars($values['nbClient']);
        $danger = htmlspecialchars($values['danger']);
        $gps = htmlspecialchars($values['gps']);
        $location = htmlspecialchars($values['place']);
        $altern = htmlspecialchars($values['altern']);
        $diffId = htmlspecialchars($values['ddDiff']);
        $sportId = htmlspecialchars($values['ddSport']);
        $id = htmlspecialchars($values['id']);

        $query = 'UPDATE t_route SET rouName=:rName, rouDescription=:description, rouPosDrop=:posDrop, rouNegDrop=:negDrop, rouMaxElevation=:maxElevation, rouDuration=:duration, rouNbClient=:nbClient, rouDanger=:danger, rouGpsFile=:gps, rouLocation=:location, rouAltern=:altern, idSport=:idSport, idDifficulty=:idDiff, rouCreateBy=:createBy WHERE idRoute=:id';

        $dataArray = array(
            'rName' => $name,
            'description' => $description,
            'posDrop' => $dropPos,
            'negDrop' => $dropNeg,
            'maxElevation' => $maxElev,
            'duration' => $duration,
            'nbClient' => $nbClient,
            'danger' => $danger,
            'gps' => $gps,
            'location' => $location,
            'altern' => $altern,
            'idDiff' => $diffId,
            'idSport' => $sportId,
            'id' => $id,
            'createBy' => $_SESSION['user']['id']
        );

        $request->update($query, $dataArray);
    }
}