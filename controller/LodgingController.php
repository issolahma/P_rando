<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

include_once 'classes/LodgingRepository.php';

class LodgingController extends Controller {

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
        $lodgRepo = new LodgingRepository();

        $view = file_get_contents('view/pages/addData/addLodging.php');

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
        $lodgRepo = new LodgingRepository();

        $listLodging = $lodgRepo->findAll($_POST);

        $filtered_rows = count($listLodging); //->rowCount();
        //TODO $allRecords = $listAccompanists->rowCount();

        $data = array();
        foreach ($listLodging as $row) {
            //Print only active client
            if($row['lodActive'] == 1){
                $sub_array = array();
                $sub_array[] = $row['idLodging'];
                $sub_array[] = $row["lodName"];
                $sub_array[] = $row["lodPlace"];
                $sub_array[] = '<button type="button" name="update" id="' . $row["idLodging"] . '" class="btn btn-warning btn-xs update">Modifier</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["idLodging"] . '" class="btn btn-danger btn-xs delete">Supprimer</button>';
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
        $lodgRepo = new LodgingRepository();
        $output = null; //TODO

        if ($_POST['operation'] == 'Add') {
            //Check if accompanist exist
            if ($lodgRepo->findLodging(htmlspecialchars($_POST['name'])) == null) {
                //Add client
                $lodgRepo->addLodging($_POST);
            } else {
                $output['error_msg'] = 'Un logement avec le même nom est déjà présent dans la base de donnée'; //TODO utf8
            }
        } elseif ($_POST['operation'] == 'Edit') {
            $lodgRepo->updateLodging($_POST);

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

        $lodgRepo = new LodgingRepository();
        $lodging = $lodgRepo->findOne($_POST['lodg_id']);

        foreach ($lodging as $row){
            $output["name"] = $row["lodName"];
            $output["place"] = $row["lodPlace"];
            $output['id'] = $row['idLodging'];
        }

        echo json_encode($output);
    }

    /**
     * Delete lodging (in fact, set inactive)
     */
    private function deleteAjaxAction(){
        $lodgRepo = new LodgingRepository();
        $lodging = $lodgRepo->hideOne($_POST['lodg_id']);
    }
}