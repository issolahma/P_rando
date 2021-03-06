<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

include_once 'classes/RouteRepository.php';
include_once 'classes/DifficultyRepository.php';
include_once 'classes/SportRepository.php';

class RouteController extends Controller
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
        $routeRepo = new RouteRepository();
        $sportRepo = new SportRepository();
        $diffRepo = new DifficultyRepository();

        //List of sport and difficulty
        $sportList = $sportRepo->listSport();
        $diffList = $diffRepo->listDifficulty();

        $view = file_get_contents('view/pages/addData/addRoute.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * On modal submit button. Add or update route
     */
    private function formAjaxAction()
    {
        $routeRepo = new RouteRepository();
        $output = null; //TODO

        if ($_POST['operation'] == 'Add') {
            //Check if animation exist
            if ($routeRepo->findRoute(htmlspecialchars($_POST['name'])) == null) {
                //Add route
                $routeRepo->addRoute($_POST);
            } else {
                $output['error_msg'] = 'Une route avec le même nom est déjà présente dans la base de donnée'; //TODO utf8
            }
        }elseif ($_POST['operation'] == 'Edit'){
            $routeRepo->updateRoute($_POST);
        }

        /*
        TODO
        retour erreur msg utile??
        */
        echo json_encode($output);
    }

    /**
     * Get all route from repo.
     */
    private function listAjaxAction(){
        $routeRepo = new RouteRepository();
        $sportRepo = new SportRepository();
        $diffRepo = new DifficultyRepository();

        $listRoutes = $routeRepo->findAll($_POST);

        $filtered_rows = count($listRoutes); //->rowCount();
        //TODO $allRecords = $listRoutes->rowCount();

        $data = array();
        foreach ($listRoutes as $row) {
            //Print only active animation
            if($row['rouActive'] == 1){
                $sub_array = array();
                $sub_array[] = $row["idRoute"];
                $sub_array[] = $row["rouName"];
                $sub_array[] = $row['rouLocation'];
                $sub_array[] = $row['rouNbClient'];
                $sub_array[] = $sportRepo->findOne($row['idSport'])[0]['spoName'];
                $sub_array[] = $diffRepo->findOne($row['idDifficulty'])[0]['difLevel'];
                $sub_array[] = '<button type="button" name="update" id="' . $row["idRoute"] . '" class="btn btn-warning btn-xs update">Modifier</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["idRoute"] . '" class="btn btn-danger btn-xs delete">Supprimer</button>';
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

        $routeRepo = new RouteRepository();
        $route = $routeRepo->findOne($_POST['route_id']);

        foreach ($route as $row){
            $output["name"] = $row["rouName"];
            $output["place"] = $row["rouLocation"];
            $output["description"] = $row["rouDescription"];
            $output["dropPos"] = $row["rouPosDrop"];
            $output["dropNeg"] = $row["rouNegDrop"];
            $output["maxAlt"] = $row["rouMaxElevation"];
            $output["nbClient"] = $row["rouNbClient"];
            $output["gps"] = $row["rouGpsFile"];
            $output["duration"] = $row["rouDuration"];
            $output["danger"] = $row["rouDanger"];
            $output["altern"] = $row["rouAltern"];
            $output["ddSport"] = $row["idSport"];
            $output["ddDiff"] = $row["idDifficulty"];
            $output['id'] = $row['idRoute'];
        }

        echo json_encode($output);
    }

    private function deleteAjaxAction(){
        $routeRepo = new RouteRepository();
        $route = $routeRepo->hideOne($_POST['route_id']);
    }
}