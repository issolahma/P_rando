<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 08.01.2018
 */

include_once 'classes/ClientsRepository.php';
include_once 'classes/MedicRepository.php';
include_once 'classes/SickRepository.php';

class ClientsController extends Controller
{

    /**
     * Dispatch current action
     *
     * @return mixed
     */
    public function display()
    {

        $action = $_GET['action'] . "Action";

        return call_user_func(array($this, $action));
    }

    /**
     * Display client page
     *
     * @return string
     */
    private function showAction()
    {
        $clientRepo = new ClientsRepository();
        $medicRepo = new MedicRepository();
        $sickRepo = new SickRepository();

        //List of medicament and sickness from db
        $medicList = $medicRepo->listMedicament();
        $sickList = $sickRepo->listSickness();

        $view = file_get_contents('view/pages/addData/addClient.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * On modal submit button. Add or update client
     */
    private function formAjaxAction()
    {
        $clientRepo = new ClientsRepository();
        $output = null; //TODO

        // Add a new client
        if ($_POST['operation'] == 'Add') {
            //Check if client exist
            if ($clientRepo->findClient(htmlspecialchars($_POST['firstname']), htmlspecialchars($_POST['lastname'])) == null) {
                //Add client
                $clientRepo->addClient($_POST);
            } else {
                $output['error_msg'] = 'Un client avec les mêmes nom et prénom est déjà présent dans la base de donnée'; //TODO utf8
            }
        // update a new client
        }elseif ($_POST['operation'] == 'Edit'){
            $clientRepo->updateClient($_POST);
        }

        /*
        TODO
        retour erreur msg utile??
        */
        echo json_encode($output);
    }

    /**
     * Get the clients data to create the table
     * ajax -> json
     */
    private function listAjaxAction(){
        $clientRepo = new ClientsRepository();
        $listClients = $clientRepo->findAll($_POST);

        $filtered_rows = count($listClients); //->rowCount();
        //TODO $allRecords = $listClients->rowCount();			

        $data = array();
        foreach ($listClients as $row) {
            //Print only active client
            if($row['cliActive'] == 1){
                $sub_array = array();
                $sub_array[] = $row["idClient"];
                $sub_array[] = $row["cliLastName"];
                $sub_array[] = $row["cliFirstName"];
                $sub_array[] = $row["cliCity"];                    
                $sub_array[] = '<button type="button" name="update" id="' . $row["idClient"] . '" class="btn btn-warning btn-xs update">Modifier</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["idClient"] . '" class="btn btn-danger btn-xs delete">Supprimer</button>';
                $data[] = $sub_array;
            }
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $filtered_rows,
            //"recordsFiltered"	=>	$allRecords,
            "data" => $data
        );

        echo json_encode($output);
    }

    /**
     * Get the client data, and link them to the input update modal form
     */
    private function updateAjaxAction(){

        $clientRepo = new ClientsRepository();

        // Find the client
        $client = $clientRepo->findOne($_POST['client_id']);

        // Find his sickness
        $sickness = $clientRepo->findClientSickness($_POST['client_id']);

        //Find his medicament
        $medicament = $clientRepo->findClientMedic($_POST['client_id']);

        // Array with client data
        $output["sickness"] = $sickness;
        $output["medicament"] = $medicament;

        foreach ($client as $row){
            $output["client_id"] = $row["idClient"];
            $output["firstname"] = $row["cliFirstName"];
            $output["lastname"] = $row["cliLastName"];
            $output["city"] = $row["cliCity"];
            $output["email"] = $row["cliEmail"];
            $output["cliPhone"] = $row["cliMobilePhone"];
            $output["npa"] = $row["cliNPA"];	
            $output["street"] = $row["cliStreet"];
            $output["streetNb"] = $row["cliStreetNum"];
            $output["urgencyPhone"] = $row["cliUrgencyPhone"];	
        }

        echo json_encode($output);
    }

    /**
     * Delete client (in fact, set inactive)
     */
    private function deleteAjaxAction(){
        $clientRepo = new ClientsRepository();
        $client = $clientRepo->hideOne($_POST['client_id']);
    }
}