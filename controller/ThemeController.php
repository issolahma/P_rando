<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 10.01.2018
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
     * Display theme page
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
     * On modal submit button. Add or update theme
     */
    private function formAjaxAction()
    {
        $themeRepo = new ThemeRepository();
        $output = null; //TODO

        if ($_POST['operation'] == 'Add') {
            //Check if theme exist
            if ($themeRepo->findTheme(htmlentities($_POST['name'])) == null) {
                //Add theme
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
     *  Create the list of theme datas for the table
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
                $sub_array[] = $row["idTheme"];
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

    /**
     * Get the theme name to print it in the modal form
     */
    private function updateAjaxAction(){

        $themeRepo = new ThemeRepository();
        $theme = $themeRepo->findOne($_POST['theme_id']);

        foreach ($theme as $row){
            $output["name"] = $row["theName"];
        }

        echo json_encode($output);
    }

    /**
     *  Delete the theme
     */
    private function deleteAjaxAction(){
        $themeRepo = new ThemeRepository();
        $theme = $themeRepo->hideOne($_POST['theme_id']);
    }
}