<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

include_once 'classes/MedicRepository.php';

class MedicController extends Controller {

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
     * Display Index Action
     *
     * @return string
     */
    private function showAction()
    {
        $medicRepo = new MedicRepository();

        $view = file_get_contents('view/pages/addData/addMedic.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Get the lodging data to create the table
     * ajax -> json
     */
    private function listAjaxAction(){
        $medicRepo = new MedicRepository();

        $listMedic = $medicRepo->findAll($_POST);

        $filtered_rows = count($listMedic); //->rowCount();
        //TODO $allRecords = $listAccompanists->rowCount();

        $data = array();
        foreach ($listMedic as $row) {
            //Print only active client
            if($row['medActive'] == 1){
                $sub_array = array();
                $sub_array[] = $row['idMedicament'];
                $sub_array[] = $row["medName"];
                $sub_array[] = '<button type="button" name="update" id="' . $row["idMedicament"] . '" class="btn btn-warning btn-xs update">Modifier</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["idMedicament"] . '" class="btn btn-danger btn-xs delete">Supprimer</button>';
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
     * On modal submit button. Add or update lodging
     */
    private function formAjaxAction()
    {
        $medicRepo = new MedicRepository();
        $output = null; //TODO

        if ($_POST['operation'] == 'Add') {
            //Check if accompanist exist
            if ($medicRepo->findMedic(htmlentities($_POST['name'])) == null) {
                //Add client
                $medicRepo->addMedicament($_POST);
            } else {
                $output['error_msg'] = 'Un médicament avec le même nom est déjà présent dans la base de donnée'; //TODO utf8
            }
        } elseif ($_POST['operation'] == 'Edit') {
            $medicRepo->updateMedicament($_POST);

            /*
            TODO
            retour erreur msg utile??
            */
            echo json_encode($output);
        }
    }

    /**
     * Get the lodging data, and link them to the input update modal form
     */
    private function updateAjaxAction(){

        $medicRepo = new MedicRepository();
        $lodging = $medicRepo->findOne($_POST['medic_id']);

        foreach ($lodging as $row){
            $output["name"] = $row["medName"];
            $output['id'] = $row['idMedicament'];
        }

        echo json_encode($output);
    }

    /**
     * Delete lodging (in fact, set inactive)
     */
    private function deleteAjaxAction(){
        $medicRepo = new MedicRepository();
        $lodging = $medicRepo->hideOne($_POST['medic_id']);
    }
}