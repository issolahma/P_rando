<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

include_once 'classes/SickRepository.php';

class SicknessController extends Controller {

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
        $sickRepo = new SickRepository();

        $view = file_get_contents('view/pages/addData/addSickness.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Get the sickness data to create the table
     * ajax -> json
     */
    private function listAjaxAction(){
        $sickRepo = new SickRepository();

        $listSickness = $sickRepo->findAll($_POST);

        $filtered_rows = count($listSickness); //->rowCount();
        //TODO $allRecords = $listAccompanists->rowCount();

        $data = array();
        foreach ($listSickness as $row) {
            //Print only active client
            if($row['sicActive'] == 1){
                $sub_array = array();
                $sub_array[] = $row['idSickness'];
                $sub_array[] = $row["sicName"];
                $sub_array[] = '<button type="button" name="update" id="' . $row["idSickness"] . '" class="btn btn-warning btn-xs update">Modifier</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["idSickness"] . '" class="btn btn-danger btn-xs delete">Supprimer</button>';
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
     * On modal submit button. Add or update sickness
     */
    private function formAjaxAction()
    {
        $sickRepo = new SickRepository();
        $output = null; //TODO

        if ($_POST['operation'] == 'Add') {
            //Check if accompanist exist
            if ($sickRepo->findSickness(htmlentities($_POST['name'])) == null) {
                //Add client
                $sickRepo->addSickness($_POST);
            } else {
                $output['error_msg'] = 'Une maladie avec le même nom est déjà présent dans la base de donnée'; //TODO utf8
            }
        } elseif ($_POST['operation'] == 'Edit') {
            $sickRepo->updateSickness($_POST);

            /*
            TODO
            retour erreur msg utile??
            */
            echo json_encode($output);
        }
    }

    /**
     * Get the sickness data, and link them to the input update modal form
     */
    private function updateAjaxAction(){

        $sickRepo = new SickRepository();
        $sickness = $sickRepo->findOne($_POST['sick_id']);

        foreach ($sickness as $row){
            $output["name"] = $row["sicName"];
            $output['id'] = $row['idSickness'];
        }

        echo json_encode($output);
    }

    /**
     * Delete sickness (in fact, set inactive)
     */
    private function deleteAjaxAction(){
        $sickRepo = new SickRepository();
        $sickness = $sickRepo->hideOne($_POST['sick_id']);
    }
}