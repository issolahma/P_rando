<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 08.01.2018
 */

include_once 'database/DataBaseQuery.php';
include_once 'classes/MedicRepository.php';
include_once 'classes/SickRepository.php';

class ClientsRepository {

    /**
     * Query to find all clients datas
     *
     * @return array|resource
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlentities($post["search"]["value"]);

        $query = 'SELECT * FROM t_client ';

        //Make the search on firstname, lastname, or city
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE cliFirstName LIKE "%'.$searchValue.'%" ';
            $query .= 'OR cliLastName LIKE "%'.$searchValue.'%" ';
            $query .= 'OR cliCity LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 0:
                    $orderCol = 'cliLastName';
                    break;
                case 1:
                    $orderCol = 'cliFirstName';
                    break;
                case 2:
                    $orderCol = 'cliCity';
                    break;
                default:
                    $orderCol = 'cliLastName';

            }

            //Order direction Asc or Desc
            $orderDir = htmlentities($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY cliLastName ASC '; //By default order by lastname asc
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
     * Find one client by name
     *
     * @param $firstname
     * @param $lastname
     * @return array
     */
    public function findClient($firstname, $lastname){
        $query = 'SELECT * FROM t_client WHERE cliFirstName=:firstname AND cliLastName=:lastname';

        $dataArray = array(
            'firstname' => $firstname,
            'lastname' => $lastname
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }


    /**
     *  Find one client by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_client WHERE idClient=:id LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }     

    /*
	* Hidde client instead of deleting it
	*
	* @param $id of the client
	* @return
	*/
    public function hideOne($id){
        $query = 'UPDATE t_client SET cliActive=0 WHERE idClient=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }


    /*
	* Add new client, and his sickness and medicament
	*
	* @param $values
	* @return
	*/ 
    public function addClient($values){
        $medicRepo = new MedicRepository();
        $sickRepo = new SickRepository();

        $request = new DataBaseQuery();

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

        $query = 'INSERT INTO t_client (cliFirstName, cliLastName, cliMobilePhone, cliUrgencyPhone, cliEmail, cliStreet, cliStreetNum, cliNPA, cliCity, cliCreateBy) VALUES (:firstname, :lastname, :mobile, :urgency, :mail, :street, :streetnum, :npa, :city, :clicreateBy)';

        $dataArray = array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'mobile' => $phone,
            'urgency' => $urgencyPh,
            'mail' => $email,
            'street' => $street,
            'streetnum' => $streetNb,
            'npa' => $npa,
            'city' => $city,
            'clicreateBy' => $_SESSION['user']['id']
        );

        $lastClientId = $request->insert($query, $dataArray);

        //If sickness checkboxes are checked
        if(isset($values['sickness'])){
            //Add each sickness checked
            foreach ($values['sickness'] as $item){
                $sick = htmlentities($item);

                $sickId = $sickRepo->findSickness($sick)[0]['idSickness'];

                $this->addIsSick($lastClientId, $sickId);
            }
        }

        //If 'other' sick is checked
        if(isset($values['sicknessInput'])){
            // New sick name
            $sick = htmlentities($values['otherSick']);

            $sickId = $sickRepo->findSickness($sick)[0]['idSickness'];
            if ($sickRepo->findSickness($sick) == null) {
                // Last id
                $sickId = $sickRepo->addSickness($sick);
            }

            // Add link client <-> sick
            $this->addIsSick($lastClientId, $sickId);
        }

        //If medicament checkboxes are checked
        if(isset($values['medicament'])){

            foreach ($values['medicament'] as $item){
                $medic = htmlentities($item);

                $medicId = $medicRepo->findMedic($medic)[0]['idMedicament'];

                $this->addTakeMedic($lastClientId, $medicId);
            }
        }

        //If other medic is checked
        if(isset($values['medicamentInput'])){
            // New medic name
            $medic = htmlentities($values['otherMed']);

            $medicId = $medicRepo->findMedic($medic)[0]['idMedicament'];

            if ($medicRepo->findMedic($medic) == null) {
                // Last id
                $medicId = $medicRepo->addMedicament($medic);
            }

            // Add link client <-> sick
            $this->addTakeMedic($lastClientId, $medicId);
        }
    }

    /**
     * Add link client <-> sickness
     *
     * @param $idClient
     * @param $idSick
     */
    private function addIsSick($idClient, $idSick){
        $request = new DataBaseQuery();

        $query = 'INSERT INTO t_issick (idClient, idSickness) VALUES (:client, :sick)';

        $dataArray = array(
            'sick' => $idSick,
            'client' => $idClient,
        );

        $request->insert($query, $dataArray);
    }

    /**
    * Remove link client <-> sickness
    *
    * @param $idClient
    */
    private function removeIsSick($idClient){
        $request = new DataBaseQuery();

        $query = 'DELETE FROM t_issick WHERE idClient=:id';

        $dataArray = array(
            'id' => $idClient
        );

        $request->delete($query, $dataArray);
    }

    /**
    * Remove link client <-> medicament
    *
    * @param $idClient
    */
    private function removeTakeMeds($idClient){
        $request = new DataBaseQuery();

        $query = 'DELETE FROM t_takemeds WHERE idClient=:id';

        $dataArray = array(
            'id' => $idClient
        );

        $request->delete($query, $dataArray);
    }

    /**
    * Add link client <-> medicament
    *
    * @param $idClient
    * @param $idMedic
    */
    private function addTakeMedic($idClient, $idMedic){
        $request = new DataBaseQuery();

        $query = 'INSERT INTO t_takemeds (idClient, idMedicament) VALUES (:client, :medic)';

        $dataArray = array(
            'medic' => $idMedic,
            'client' => $idClient,
        );

        $request->insert($query, $dataArray);
    }

    /**
    * Update one client
    *
    * @param $values
    */
    public function updateClient($values){
        $request = new DataBaseQuery();
        $medicRepo = new MedicRepository();
        $sickRepo = new SickRepository();

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
        $idClient = htmlentities($values['client_id']);

        $query = 'UPDATE t_client SET cliFirstName=:firstname, cliLastName=:lastname, cliMobilePhone=:mobile, cliUrgencyPhone=:urgency, cliEmail=:email, cliStreet=:street, cliStreetNum=:streetnum, cliNPA=:npa, cliCity=:city WHERE idClient=:id';

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
            'id' => $idClient
        );

        $request->update($query, $dataArray);

        //Delete isSick and takemeds
        $this->removeIsSick($idClient);
        $this->removeTakeMeds($idClient);

        //If sickness checkboxes are checked
        if(isset($values['sickness'])){
            //Add each sickness checked
            foreach ($values['sickness'] as $item){
                $sick = htmlentities($item);

                $sickId = $sickRepo->findSickness($sick)[0]['idSickness'];

                $this->addIsSick($idClient, $sickId);
            }
        }

        //If 'other' sick is checked
        if(isset($values['sicknessInput'])){
            // New sick name
            $sick = htmlentities($values['otherSick']);

            $sickId = $sickRepo->findSickness($sick)[0]['idSickness'];
            if ($sickRepo->findSickness($sick) == null) {
                // Last id
                $sickId = $sickRepo->addSickness($sick);
            }

            // Add link client <-> sick
            $this->addIsSick($idClient, $sickId);
        }

        //If medicament checkboxes are checked
        if(isset($values['medicament'])){

            foreach ($values['medicament'] as $item){
                $medic = htmlentities($item);

                $medicId = $medicRepo->findMedic($medic)[0]['idMedicament'];
                $this->addTakeMedic($idClient, $medicId);
            }
        }

        //If other medic is checked
        if(isset($values['medicamentInput'])){
            // New medic name
            $medic = htmlentities($values['otherMed']);

            $medicId = $medicRepo->findMedic($medic)[0]['idMedicament'];

            if ($medicRepo->findMedic($medic) == null) {
                // Last id
                $medicId = $medicRepo->addMedicament($medic);
            }

            // Add link client <-> sick
            $this->addTakeMedic($idClient, $medicId);
        }
    }

    /**
    * Find client sickness
    *
    * @param $idClient
    *
    */
    public function findClientSickness($idClient){
        $request = new DataBaseQuery();

        $query = 'SELECT * FROM t_sickness NATURAL JOIN t_issick NATURAL JOIN t_client WHERE t_client.idClient=:id';

        $dataArray = array(
            'id' => $idClient
        );

        return $request->rawQuery($query, $dataArray);
    }

    /**
    * Find client medicament
    *
    * @param $idClient
    */
    public function findClientMedic($idClient){
        $request = new DataBaseQuery();

        $query = 'SELECT * FROM t_medicament NATURAL JOIN t_takemeds NATURAL JOIN t_client WHERE t_client.idClient=:id';

        $dataArray = array(
            'id' => $idClient
        );

        return $request->rawQuery($query, $dataArray);
    }
}