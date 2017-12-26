<?php
/**
 * Created by PhpStorm.
 * User: issolahma
 * Date: 06.12.2017
 * Time: 15:42
 */

include_once 'classes/ThemeRepository.php';

class ThemeController extends Controller
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
        $themeRepo = new ThemeRepository();

        $view = file_get_contents('view/pages/addData/addTheme.php');

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
        $themeRepo = new ThemeRepository();
        $output = null; //TODO

        if ($_POST['operation'] == 'Add') {
            //Check if theme exist
            if ($themeRepo->findTheme(htmlentities($_POST['name'])) == null) {
                //Add client
                $themeRepo->addTheme($_POST);
            } else {
                $output['error_msg'] = 'Une theme avec le même nom est déjà présente dans la base de donnée'; //TODO utf8
            }
        }elseif ($_POST['operation'] == 'Edit'){
            $themeRepo->updateTheme($_POST);
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
        $themeRepo = new ThemeRepository();
        $listThemes = $themeRepo->findAll($_POST);

        $filtered_rows = count($listThemes); //->rowCount();
        //TODO $allRecords = $listThemes->rowCount();

        $data = array();
        foreach ($listThemes as $row) {
            //Print only active client
            if($row['theActive'] == 1){
                $sub_array = array();
                $sub_array[] = $row["theName"];
                $sub_array[] = '<button type="button" name="update" id="' . $row["idTheme"] . '" class="btn btn-warning btn-xs update">Modifier</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["idTheme"] . '" class="btn btn-danger btn-xs delete">Supprimer</button>';
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

        $themeRepo = new ThemeRepository();
        $theme = $themeRepo->findOne($_POST['user_id']);

        foreach ($theme as $row){
            $output["name"] = $row["theName"];
        }

        echo json_encode($output);
    }

    private function deleteAjaxAction(){
        $themeRepo = new ThemeRepository();
        $theme = $themeRepo->hideOne($_POST['user_id']);
    }
}