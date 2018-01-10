<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 10.01.2018
 */

include_once 'classes/DifficultyRepository.php';

class DifficultyController extends Controller {

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
        $diffRepo = new DifficultyRepository();

        $view = file_get_contents('view/pages/addData/addDifficulty.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Get the difficulty data to create the table
     * ajax -> json
     */
    private function listAjaxAction(){
        $diffRepo = new DifficultyRepository();

        $listDifficulty = $diffRepo->findAll($_POST);

        $filtered_rows = count($listDifficulty); //->rowCount();
        //TODO $allRecords = $listAccompanists->rowCount();

        $data = array();
        foreach ($listDifficulty as $row) {
            //Print only active client
            if($row['difActive'] == 1){
                $sub_array = array();
                $sub_array[] = $row['idDifficulty'];
                $sub_array[] = $row["difLevel"];
                $sub_array[] = '<button type="button" name="update" id="' . $row["idDifficulty"] . '" class="btn btn-warning btn-xs update">Modifier</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["idDifficulty"] . '" class="btn btn-danger btn-xs delete">Supprimer</button>';
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
     * On modal submit button. Add or update difficulty
     */
    private function formAjaxAction()
    {
        $diffRepo = new DifficultyRepository();
        $output = null; //TODO

        if ($_POST['operation'] == 'Add') {
            //Check if accompanist exist
            if ($diffRepo->findDifficulty(htmlentities($_POST['name'])) == null) {
                //Add client
                $diffRepo->addDifficulty($_POST);
            } else {
                $output['error_msg'] = 'Une difficulté avec le même nom est déjà présent dans la base de donnée'; //TODO utf8
            }
        } elseif ($_POST['operation'] == 'Edit') {
            $diffRepo->updateDifficulty($_POST);

            /*
            TODO
            retour erreur msg utile??
            */
            echo json_encode($output);
        }
    }

        /**
         * Get the difficulty data, and link them to the input update modal form
         */
        private function updateAjaxAction(){

            $diffRepo = new DifficultyRepository();
        $accompanist = $diffRepo->findOne($_POST['dif_id']);

        foreach ($accompanist as $row){
            $output["name"] = $row["difLevel"];
            $output['id'] = $row['idDifficulty'];
        }

        echo json_encode($output);
    }
}