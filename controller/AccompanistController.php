<?php
/**
 * Created by PhpStorm.
 * User: issolahma
 * Date: 06.12.2017
 * Time: 15:42
 */

include_once 'classes/AccompanistRepository.php';

class AccompanistController extends Controller
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
        $accRepo = new AccompanistRepository();

        $view = file_get_contents('view/pages/addData/addAccompanist.php');

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
        $accRepo = new AccompanistRepository();
        $output = null; //TODO

        if ($_POST['operation'] == 'Add') {
            //Check if accompanist exist
            if ($accRepo->findAccompanist(htmlentities($_POST['firstname']), htmlentities($_POST['lastname'])) == null) {
                //Add client
                $accRepo->addAccompanist($_POST);
            } else {
                $output['error_msg'] = 'Un accompagnateur avec les mêmes nom et prénom est déjà présent dans la base de donnée'; //TODO utf8
            }
        }elseif ($_POST['operation'] == 'Edit'){
            $accRepo->updateAccompanist($_POST);
        }

        /*
        TODO
        retour erreur msg utile??
        */
        echo json_encode($output);
    }

    /**
     *
     *
     */
    private function listAjaxAction(){
        $accRepo = new AccompanistRepository();
        $listAccompanists = $accRepo->findAll($_POST);

        $filtered_rows = count($listAccompanists); //->rowCount();
        //TODO $allRecords = $listAccompanists->rowCount();

        $data = array();
        foreach ($listAccompanists as $row) {
            //Print only active client
            if($row['accActive'] == 1){
                $sub_array = array();
                $sub_array[] = $row["accLastName"];
                $sub_array[] = $row["accFirstName"];
                $sub_array[] = $row["accLogin"];
                $sub_array[] = $row["accRight"];
                $sub_array[] = '<button type="button" name="update" id="' . $row["idAccompanist"] . '" class="btn btn-warning btn-xs update">Modifier</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["idAccompanist"] . '" class="btn btn-danger btn-xs delete">Supprimer</button>';
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

        $accRepo = new AccompanistRepository();
        $accompanist = $accRepo->findOne($_POST['user_id']);

        foreach ($accompanist as $row){
            $output["firstname"] = $row["accFirstName"];
            $output["lastname"] = $row["accLastName"];
            $output["accRight"] = $row["accRight"];
            $output["login"] = $row["accLogin"];
        }

        echo json_encode($output);
    }

    private function deleteAjaxAction(){
        $accRepo = new AccompanistRepository();
        $accompanist = $accRepo->hideOne($_POST['user_id']);
    }
}