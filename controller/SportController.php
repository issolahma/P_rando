<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

include_once 'classes/SportRepository.php';

class SportController extends Controller {

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
        $sportRepo = new SportRepository();

        $view = file_get_contents('view/pages/addData/addSport.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Get the sport data to create the table
     * ajax -> json
     */
    private function listAjaxAction(){
        $sportRepo = new SportRepository();

        $listSport = $sportRepo->findAll($_POST);

        $filtered_rows = count($listSport); //->rowCount();
        //TODO $allRecords = $listAccompanists->rowCount();

        $data = array();
        foreach ($listSport as $row) {
            //Print only active client
            if($row['spoActive'] == 1){
                $sub_array = array();
                $sub_array[] = $row['idSport'];
                $sub_array[] = $row["spoName"];
                $sub_array[] = '<button type="button" name="update" id="' . $row["idSport"] . '" class="btn btn-warning btn-xs update">Modifier</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["idSport"] . '" class="btn btn-danger btn-xs delete">Supprimer</button>';
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
     * On modal submit button. Add or update sport
     */
    private function formAjaxAction()
    {
        $sportRepo = new SportRepository();
        $output = null; //TODO

        if ($_POST['operation'] == 'Add') {
            //Check if accompanist exist
            if ($sportRepo->findSport(htmlspecialchars($_POST['name'])) == null) {
                //Add client
                $sportRepo->addSport($_POST);
            } else {
                $output['error_msg'] = 'Un sport avec le même nom est déjà présent dans la base de donnée'; //TODO utf8
            }
        } elseif ($_POST['operation'] == 'Edit') {
            $sportRepo->updateSport($_POST);

            /*
            TODO
            retour erreur msg utile??
            */
            echo json_encode($output);
        }
    }

    /**
     * Get the sport data, and link them to the input update modal form
     */
    private function updateAjaxAction(){

        $sportRepo = new SportRepository();
        $sport = $sportRepo->findOne($_POST['sport_id']);

        foreach ($sport as $row){
            $output["name"] = $row["spoName"];
            $output['id'] = $row['idSport'];
        }

        echo json_encode($output);
    }

    /**
     * Delete sport (in fact, set inactive)
     */
    private function deleteAjaxAction(){
        $sportRepo = new SportRepository();
        $sport = $sportRepo->hideOne($_POST['sport_id']);
    }
}