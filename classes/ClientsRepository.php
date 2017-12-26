<?php
/**
 * ETML
 * Date: 01.06.2017
 * Shop
 */

include_once 'database/DataBaseQuery.php';


class ClientsRepository {

    /**
     * Find all entries for the list
     *
     * @return array|resource
     */
		public function findAll($post) {
		    $searchValue = htmlentities($post["search"]["value"]);

            $query = 'SELECT * FROM t_client ';

			
			if(!empty($post["search"]["value"])){
				$query .= 'WHERE cliFirstName LIKE "%'.$searchValue.'%" ';
				$query .= 'OR cliLastName LIKE "%'.$searchValue.'%" ';
			}

			if(!empty($post['order'])){
			    $orderCol = htmlentities($post['order']['0']['column']); // 0/1/2
			    
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
			    	
             $orderDir = htmlentities($post['order']['0']['dir']);

				$query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
			}
			else{
				$query .= 'ORDER BY cliLastName ASC ';
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
    
    public function hideOne($id){
    	$query = 'UPDATE t_client SET cliActive=0 WHERE idClient=:id';
    	
    	  $dataArray = array(
            'id' => $id
        );
        
        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }
    
    
    /**
     * @param $values
     */
    public function addClient($values){
        //echo print_r($values);

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

                $sickId = $this->findSickness($sick)[0]['idSickness'];

                $this->addIsSick($lastClientId, $sickId);
            }
        }

        //If 'other' sick is checked
        if(isset($values['sicknessInput'])){
            // New sick name
            $sick = htmlentities($values['otherSick']);

            $sickId = $this->findSickness($sick)[0]['idSickness'];
            if ($this->findSickness($sick) == null) {
                // Last id
                $sickId = $this->addSickness($sick);
            }

            // Add link client <-> sick
            $this->addIsSick($lastClientId, $sickId);
        }

        //If medicament checkboxes are checked
        if(isset($values['medicament'])){

            foreach ($values['medicament'] as $item){
                $medic = htmlentities($item);

                $medicId = $this->findMedic($medic)[0]['idMedicament'];

                $this->addTakeMedic($lastClientId, $medicId);
            }
        }

        //If other medic is checked
        if(isset($values['medicamentInput'])){
            // New medic name
            $medic = htmlentities($values['otherMed']);

            $medicId = $this->findMedic($medic)[0]['idMedicament'];

            if ($this->findMedic($medic) == null) {
                // Last id
                $medicId = $this->addMedicament($medic);
            }

            // Add link client <-> sick
            $this->addTakeMedic($lastClientId, $medicId);
        }
    }

    private function findSickness($sick){
        $request = new DataBaseQuery();

        $query = 'SELECT * FROM t_sickness WHERE sicName=:name';

        $dataArray = array(
            'name' => $sick
        );

        return $request->rawQuery($query, $dataArray);
    }

        /**
     * Insert new sickness, and return last id
     *
     * @param $sick
     * @return string
     */
    private function addSickness($sick){
        $request = new DataBaseQuery();

        $query = 'INSERT INTO t_sickness (sicName, sicCreateBy) VALUES (:sick, :createBy)';

        $dataArray = array(
            'sick' => $sick,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->insert($query, $dataArray);
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

    private function removeIsSick($idClient){
        $request = new DataBaseQuery();

        $query = 'DELETE FROM t_issick WHERE idClient=:id';

        $dataArray = array(
            'id' => $idClient
        );

        $request->delete($query, $dataArray);
    }

    private function removeTakeMeds($idClient){
        $request = new DataBaseQuery();

        $query = 'DELETE FROM t_takemeds WHERE idClient=:id';

        $dataArray = array(
            'id' => $idClient
        );

        $request->delete($query, $dataArray);
    }

    private function findMedic($medic){
        $request = new DataBaseQuery();

        $query = 'SELECT * FROM t_medicament WHERE medName=:name';

        $dataArray = array(
            'name' => $medic
        );

        return $request->rawQuery($query, $dataArray);
    }

    /**
     * @param $medic
     * @return string
     */
    private function addMedicament($medic){
        $request = new DataBaseQuery();

        $query = 'INSERT INTO t_medicament (medName, medCreateBy) VALUES (:medic, :createBy)';

        $dataArray = array(
            'medic' => $medic,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->insert($query, $dataArray);
    }

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
     * Find all sickness in the table
     *
     * @return array
     */
    public function listSickness(){
        $query = 'SELECT * FROM t_sickness';

        $request = new DataBaseQuery();

        return $request->rawQuery($query, null);
    }

    /**
     * Find all medicaments in the table
     *
     * @return array
     */
    public function listMedicament(){
        $query = 'SELECT * FROM t_medicament';

        $request = new DataBaseQuery();

        return $request->rawQuery($query, null);
    }

    public function updateClient($values){
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

        error_log('lol'.$firstname. " ". $lastname);
        $idClient = $this->findClient($firstname, $lastname)[0]['idClient'];
        error_log('IDCLIENT'.$idClient);

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

                $sickId = $this->findSickness($sick)[0]['idSickness'];

                //$myfile = fopen("testfile.txt", "w");
                //$txt = implode(",", $sickId[0]);
                //fwrite($myfile, $sickId);

                //Check if already in db
                //if ($sickId == null) {
                //    $sickId = $this->addSickness($sick);
                //}

                $this->addIsSick($idClient, $sickId);
            }
        }

        //If 'other' sick is checked
        if(isset($values['sicknessInput'])){
            // New sick name
            $sick = htmlentities($values['otherSick']);

            $sickId = $this->findSickness($sick)[0]['idSickness'];
            if ($this->findSickness($sick) == null) {
                // Last id
                $sickId = $this->addSickness($sick);
            }

            // Add link client <-> sick
            $this->addIsSick($idClient, $sickId);
        }

        //If medicament checkboxes are checked
        if(isset($values['medicament'])){

            foreach ($values['medicament'] as $item){
                $medic = htmlentities($item);

                $medicId = $this->findMedic($medic)[0]['idMedicament'];
                $this->addTakeMedic($idClient, $medicId);
            }
        }

        //If other medic is checked
        if(isset($values['medicamentInput'])){
            // New medic name
            $medic = htmlentities($values['otherMed']);

            $medicId = $this->findMedic($medic)[0]['idMedicament'];

            if ($this->findMedic($medic) == null) {
                // Last id
                $medicId = $this->addMedicament($medic);
            }

            // Add link client <-> sick
            $this->addTakeMedic($idClient, $medicId);
        }
    }

    public function findClientSickness($idClient){
        $request = new DataBaseQuery();

        $query = 'SELECT * FROM t_sickness NATURAL JOIN t_issick NATURAL JOIN t_client WHERE t_client.idClient=:id';

        $dataArray = array(
            'id' => $idClient
        );

        return $request->rawQuery($query, $dataArray);
    }

    public function findClientMedic($idClient){
        $request = new DataBaseQuery();

        $query = 'SELECT * FROM t_medicament NATURAL JOIN t_takemeds NATURAL JOIN t_client WHERE t_client.idClient=:id';

        $dataArray = array(
            'id' => $idClient
        );

        return $request->rawQuery($query, $dataArray);
    }
}