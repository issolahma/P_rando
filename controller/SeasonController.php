<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 10.01.2018
 */

include_once 'classes/SeasonRepository.php';

class SeasonController extends Controller
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
     * Display Index Action
     *
     * @return string
     */
    private function showAction()
    {
        $seasonRepo = new SeasonRepository();

        $view = file_get_contents('view/pages/addData/addSeason.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * On modal submit button. Add or update season
     */
    private function formAjaxAction()
    {
        $seasonRepo = new SeasonRepository();
        $output = null; //TODO

        if ($_POST['operation'] == 'Add') {
            //Check if season exist
            if ($seasonRepo->findSeason(htmlspecialchars($_POST['name'])) == null) {
                //Add season
                $seasonRepo->addSeason($_POST);
            } else {
                $output['error_msg'] = 'Une saison avec le même nom est déjà présente dans la base de donnée'; //TODO utf8
            }
        }elseif ($_POST['operation'] == 'Edit'){
            $seasonRepo->updateSeason($_POST);
        }

        /*
        TODO
        retour erreur msg utile??
        */
        echo json_encode($output);
    }

    /**
     * Get all seasons from repo.
     * Also get their theme and season
     *
     */
    private function listAjaxAction(){
        $seasonRepo = new SeasonRepository();    

        $listSeasons = $seasonRepo->findAll($_POST);

        $filtered_rows = count($listSeasons); //->rowCount();
        //TODO $allRecords = $listSeasons->rowCount();

        $data = array();
        foreach ($listSeasons as $row) {
            //Print only active season
            if($row['seaActive'] == 1){
                $sub_array = array();
                $sub_array[] = $row["idSeason"];
                $sub_array[] = $row["seaName"];
                $sub_array[] = '<button type="button" name="update" id="' . $row["idSeason"] . '" class="btn btn-warning btn-xs update">Modifier</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["idSeason"] . '" class="btn btn-danger btn-xs delete">Supprimer</button>';
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

    private function updateAjaxAction(){

        $seasonRepo = new SeasonRepository();
        $season = $seasonRepo->findOne($_POST['sea_id']);

        foreach ($season as $row){
            $output["name"] = $row["seaName"];
            $output['id'] = $row['idSeason'];
        }

        echo json_encode($output);
    }

    private function deleteAjaxAction(){
        $seasonRepo = new SeasonRepository();
        $season = $seasonRepo->hideOne($_POST['sea_id']);
    }
}