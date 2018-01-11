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
        $searchValue = htmlentities($post["search"]["value"]);

        $query = 'SELECT * FROM t_route ';

        //For the search input
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE rouName LIKE "%'.$searchValue.'%" ';
            $query .= 'OR rouDescription LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 1:
                    $orderCol = 'rouName';
                    break;
                case 2:
                    $orderCol = 'rouNbClient';
                    break;
                case 3:
                    $orderCol = 'rouDuration';
                    break;
                default:
                    $orderCol = 'rouName';

            }

            //Order direction Asc or Desc
            $orderDir = htmlentities($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY rouName ASC '; //By default order by name asc
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
        $diffRepo = new DifficultyRepository();
        $sportRepo = new SportRepository();

        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        $description = htmlentities($values['description']);
        $dropPos = htmlentities($values['dropPos']);
        $dropNeg = htmlentities($values['dropNeg']);
        $maxElev = htmlentities($values['maxElev']);
        $duration = htmlentities($values['duration']);
        $nbClient = htmlentities($values['nbClient']);
        $danger = htmlentities($values['danger']);
        $gps = htmlentities($values['gps']);
        $location = htmlentities($values['location']);
        $altern = htmlentities($values['altern']);

        $diff = htmlentities($values['diff']);
        $sport = htmlentities($values['sport']);

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
            'idDiff' => $diff,
            'idSport' => $sport,
            'createBy' => $_SESSION['user']['id']
        );

        $lastRouteId = $request->insert($query, $dataArray);

        //If sickness checkboxes are checked
        if(isset($values['sickness'])){
            //Add each sickness checked
            foreach ($values['sickness'] as $item){
                $sick = htmlentities($item);

                $sickId = $sportRepo->findSickness($sick)[0]['idSickness'];

                $this->addIsSick($lastRouteId, $sickId);
            }
        }

        //If 'other' sick is checked
        if(isset($values['sicknessInput'])){
            // New sick name
            $sick = htmlentities($values['otherSick']);

            $sickId = $sportRepo->findSickness($sick)[0]['idSickness'];
            if ($sportRepo->findSickness($sick) == null) {
                // Last id
                $sickId = $sportRepo->addSickness($sick);
            }

            // Add link route <-> sick
            $this->addIsSick($lastRouteId, $sickId);
        }

        //If medicament checkboxes are checked
        if(isset($values['medicament'])){

            foreach ($values['medicament'] as $item){
                $medic = htmlentities($item);

                $medicId = $diffRepo->findMedic($medic)[0]['idMedicament'];

                $this->addTakeMedic($lastRouteId, $medicId);
            }
        }

        //If other medic is checked
        if(isset($values['medicamentInput'])){
            // New medic name
            $medic = htmlentities($values['otherMed']);

            $medicId = $diffRepo->findMedic($medic)[0]['idMedicament'];

            if ($diffRepo->findMedic($medic) == null) {
                // Last id
                $medicId = $diffRepo->addMedicament($medic);
            }

            // Add link route <-> sick
            $this->addTakeMedic($lastRouteId, $medicId);
        }
    }

    /**
     * Add link route <-> sickness
     *
     * @param $idRoute
     * @param $idSick
     */
    private function addIsSick($idRoute, $idSick){
        $request = new DataBaseQuery();

        $query = 'INSERT INTO t_issick (idRoute, idSickness) VALUES (:route, :sick)';

        $dataArray = array(
            'sick' => $idSick,
            'route' => $idRoute,
        );

        $request->insert($query, $dataArray);
    }

    /**
     * Remove link route <-> sickness
     *
     * @param $idRoute
     */
    private function removeIsSick($idRoute){
        $request = new DataBaseQuery();

        $query = 'DELETE FROM t_issick WHERE idRoute=:id';

        $dataArray = array(
            'id' => $idRoute
        );

        $request->delete($query, $dataArray);
    }

    /**
     * Remove link route <-> medicament
     *
     * @param $idRoute
     */
    private function removeTakeMeds($idRoute){
        $request = new DataBaseQuery();

        $query = 'DELETE FROM t_takemeds WHERE idRoute=:id';

        $dataArray = array(
            'id' => $idRoute
        );

        $request->delete($query, $dataArray);
    }

    /**
     * Add link route <-> medicament
     *
     * @param $idRoute
     * @param $idMedic
     */
    private function addTakeMedic($idRoute, $idMedic){
        $request = new DataBaseQuery();

        $query = 'INSERT INTO t_takemeds (idRoute, idMedicament) VALUES (:route, :medic)';

        $dataArray = array(
            'medic' => $idMedic,
            'route' => $idRoute,
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
        $diffRepo = new DifficultyRepository();
        $sportRepo = new SportRepository();

        //Values from $_Post
        $firstname = htmlentities($values['firstname']);
        $lastname = htmlentities($values['lastname']);
        $street = htmlentities($values['street']);
        $streetNb = htmlentities($values['streetNb']);
        $npa = htmlentities($values['npa']);
        $city = htmlentities($values['city']);
        $phone = htmlentities($values['cliPhone']);
        $urgencyPh = htmlentities($values['urgencyPhone']);
        $email = htmlentities($values['email']);
        $idRoute = htmlentities($values['route_id']);

        $query = 'UPDATE t_route SET cliFirstName=:firstname, cliLastName=:lastname, cliMobilePhone=:mobile, cliUrgencyPhone=:urgency, cliEmail=:email, cliStreet=:street, cliStreetNum=:streetnum, cliNPA=:npa, cliCity=:city WHERE idRoute=:id';

        $dataArray = array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'mobile' => $phone,
            'urgency' => $urgencyPh,
            'email' => $email,
            'street' => $street,
            'streetnum' => $streetNb,
            'npa' => $npa,
            'city' => $city,
            'id' => $idRoute
        );

        $request->update($query, $dataArray);

        //Delete isSick and takemeds
        $this->removeIsSick($idRoute);
        $this->removeTakeMeds($idRoute);

        //If sickness checkboxes are checked
        if(isset($values['sickness'])){
            //Add each sickness checked
            foreach ($values['sickness'] as $item){
                $sick = htmlentities($item);

                $sickId = $sportRepo->findSickness($sick)[0]['idSickness'];

                $this->addIsSick($idRoute, $sickId);
            }
        }

        //If 'other' sick is checked
        if(isset($values['sicknessInput'])){
            // New sick name
            $sick = htmlentities($values['otherSick']);

            $sickId = $sportRepo->findSickness($sick)[0]['idSickness'];
            if ($sportRepo->findSickness($sick) == null) {
                // Last id
                $sickId = $sportRepo->addSickness($sick);
            }

            // Add link route <-> sick
            $this->addIsSick($idRoute, $sickId);
        }

        //If medicament checkboxes are checked
        if(isset($values['medicament'])){

            foreach ($values['medicament'] as $item){
                $medic = htmlentities($item);

                $medicId = $diffRepo->findMedic($medic)[0]['idMedicament'];
                $this->addTakeMedic($idRoute, $medicId);
            }
        }

        //If other medic is checked
        if(isset($values['medicamentInput'])){
            // New medic name
            $medic = htmlentities($values['otherMed']);

            $medicId = $diffRepo->findMedic($medic)[0]['idMedicament'];

            if ($diffRepo->findMedic($medic) == null) {
                // Last id
                $medicId = $diffRepo->addMedicament($medic);
            }

            // Add link route <-> sick
            $this->addTakeMedic($idRoute, $medicId);
        }
    }

    /**
     * Find route sickness
     *
     * @param $idRoute
     *
     */
    public function findRouteSickness($idRoute){
        $request = new DataBaseQuery();

        $query = 'SELECT * FROM t_sickness NATURAL JOIN t_issick NATURAL JOIN t_route WHERE t_route.idRoute=:id';

        $dataArray = array(
            'id' => $idRoute
        );

        return $request->rawQuery($query, $dataArray);
    }

    /**
     * Find route medicament
     *
     * @param $idRoute
     */
    public function findRouteMedic($idRoute){
        $request = new DataBaseQuery();

        $query = 'SELECT * FROM t_medicament NATURAL JOIN t_takemeds NATURAL JOIN t_route WHERE t_route.idRoute=:id';

        $dataArray = array(
            'id' => $idRoute
        );

        return $request->rawQuery($query, $dataArray);
    }
}