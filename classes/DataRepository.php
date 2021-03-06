<?php
/**
 * Created by PhpStorm.
 * User: issolahma
 * Date: 06.12.2017
 * Time: 15:43
 */
include_once 'database/DataBaseQuery.php';

class DataRepository {

	/**
	* get all season id associate to one animation
	*
	*/
	public function seasonanim($idAnim) {
		$query = 'SELECT * FROM t_seasonanim WHERE idAnimation=:id';
		
		$dataArray = array(
            'id' => $idAnim
        );
		
		$request = new DataBaseQuery();
      return $request->rawQuery($query, $dataArray);    
    }

	/**
	* get all theme id associate to one animation
	*
	*/
	public function themeAnim($idAnim) {
		$query = 'SELECT idTheme FROM t_themeanimation WHERE idAnimation=:id';
		
		$dataArray = array(
            'id' => $idAnim
        );
		
		$request = new DataBaseQuery();
      return $request->rawQuery($query, $dataArray);  
		}
}