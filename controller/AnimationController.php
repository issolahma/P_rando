<?php
/**
 * Created by PhpStorm.
 * User: issolahma
 * Date: 06.12.2017
 * Time: 15:42
 */

include_once 'classes/AnimationRepository.php';
include_once 'classes/ThemeRepository.php';
include_once 'classes/SeasonRepository.php';
include_once 'classes/DataRepository.php';

class AnimationController extends Controller
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
        $animRepo = new AnimationRepository();

        $view = file_get_contents('view/pages/addData/addAnimation.php');

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
        $animRepo = new AnimationRepository();
        $output = null; //TODO

        if ($_POST['operation'] == 'Add') {
            //Check if animation exist
            if ($animRepo->findAnimation(htmlentities($_POST['name'])) == null) {
                //Add client
                $animRepo->addAnimation($_POST);
            } else {
                $output['error_msg'] = 'Une animation avec le même nom est déjà présente dans la base de donnée'; //TODO utf8
            }
        }elseif ($_POST['operation'] == 'Edit'){
            $animRepo->updateAnimation($_POST);
        }

        /*
        TODO
        retour erreur msg utile??
        */
        echo json_encode($output);
    }

	 private function getSeasonId(){
	 	
	 }
	
    /**
     * Get all animations from repo.
     * Also get their theme and season
     *
     */
    private function listAjaxAction(){
        $animRepo = new AnimationRepository();
        $themeRepo = new ThemeRepository();
		  $seasonRepo = new SeasonRepository();
		  $datarepo = new Datarepository();        
        
        $listAnimations = $animRepo->findAll($_POST);
			
        $filtered_rows = count($listAnimations); //->rowCount();
        //TODO $allRecords = $listAnimations->rowCount();

        $data = array();
        foreach ($listAnimations as $row) {
            //Print only active animation
            if($row['aniActive'] == 1){
                $sub_array = array();
                $sub_array[] = $row["idAnimation"];
                $sub_array[] = $row["aniName"];
					 $sub_array[] = implode(',',$this->getAllTheme($row['idAnimation'])); //theme
					 $sub_array[] = implode(',',$this->getAllSeason($row['idAnimation'])); //season
                $sub_array[] = '<button type="button" name="update" id="' . $row["idAnimation"] . '" class="btn btn-warning btn-xs update">Modifier</button>';
                $sub_array[] = '<button type="button" name="delete" id="' . $row["idAnimation"] . '" class="btn btn-danger btn-xs delete">Supprimer</button>';
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

	private function getAllTheme($idAnim) {
        $themeRepo = new ThemeRepository();
		  $datarepo = new Datarepository(); 		
				
		$themesIds = $datarepo->themeAnim($idAnim);
		
		$themeList = array();
		foreach($themesIds as $ids){
			$themeList[] = $themeRepo->findOne($ids['idTheme'])[0]['theName'].' ';
			error_log('THEME-LIST: '.$themeList);
		}
		
		return $themeList;
	}

	private function getAllSeason($idAnim) {
        $seasonRepo = new SeasonRepository();
		  $datarepo = new Datarepository(); 		
				
		$seasonIds = $datarepo->seasonanim($idAnim);
		
		$seasonList = array();
		foreach($seasonIds as $ids){
			$seasonList[] = $seasonRepo->findOne($ids['idSeason'])[0]['seaName'].' ';
			error_log('THEME-LIST: '.$seasonList);
		}
		
		return $seasonList;
	}

    private function updateAjaxAction(){

        $animRepo = new AnimationRepository();
        $animation = $animRepo->findOne($_POST['user_id']);

        foreach ($animation as $row){
            $output["firstname"] = $row["accFirstName"];
            $output["lastname"] = $row["accLastName"];
            $output["accRight"] = $row["accRight"];
            $output["login"] = $row["accLogin"];
        }

        echo json_encode($output);
    }

    private function deleteAjaxAction(){
        $animRepo = new AnimationRepository();
        $animation = $animRepo->hideOne($_POST['user_id']);
    }
}