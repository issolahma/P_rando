<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

include_once 'database/DataBaseQuery.php';

class AnimationRepository {

    /**
     * Find all entries for the list
     *
     * @return array|resource
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlspecialchars($post["search"]["value"]);

        $query = 'SELECT * FROM t_animation ';

        //Make the search on name
        if(!empty($post["search"]["value"])){
            $query .= 'WHERE aniName LIKE "%'.$searchValue.'%" ';
            $query .= 'OR aniOwner LIKE "%'.$searchValue.'%" ';
            $query .= 'OR aniDuration LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlspecialchars($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 1:
                    $orderCol = 'aniName';
                    break;
                case 2:
                    $orderCol = 'aniOwner';
                    break;
                case 3:
                    $orderCol = 'aniDuration';
                    break;
                default:
                    $orderCol = 'aniName';
            }

            //Order direction Asc or Desc
            $orderDir = htmlspecialchars($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY aniName ASC '; //By default order by name asc
        }

        if($post["length"] != -1){
            $start = htmlspecialchars($post['start']);
            $length = htmlspecialchars($post['length']);

            $query .= 'LIMIT ' . $start . ', ' . $length;
        }

        $request =  new DataBaseQuery();

        return $request->rawQuery($query, null);

    }

    /**
     * Find one animation by name
     *
     * @param $name
     * @return array
     */
    public function findAnimation($name){
        $query = 'SELECT * FROM t_animation WHERE aniName=:aName';

        $dataArray = array(
            'aName' => $name
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }


    /**
     * Find one animation by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_animation WHERE idAnimation=:id LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }

    /**
     * Find animation theme
     *
     * @param $idAnimation
     * @return array
     */
    public function findAnimationTheme($idAnimation){
        $request = new DataBaseQuery();

        $query = 'SELECT * FROM t_theme NATURAL JOIN t_themeanimation WHERE t_themeanimation.idAnimation=:id';

        $dataArray = array(
            'id' => $idAnimation
        );

        return $request->rawQuery($query, $dataArray);
    }

    /**
     * Find season theme
     *
     * @param $idAnimation
     * @return array
     */
    public function findSeasonTheme($idAnimation){
        $request = new DataBaseQuery();

        $query = 'SELECT * FROM t_season NATURAL JOIN t_seasonanim WHERE t_seasonanim.idAnimation=:id';

        $dataArray = array(
            'id' => $idAnimation
        );

        return $request->rawQuery($query, $dataArray);
    }

    /**
     * Update animation
     *
     * @param $values
     */
    public function updateAnimation($values) {
        $request = new DataBaseQuery();

        $themeRepo = new ThemeRepository();
        $seasonRepo = new SeasonRepository();

        //Values from $_Post
        $name = htmlspecialchars($values['name']);
        $duration = htmlspecialchars($values['duration']);
        $list = htmlspecialchars($values['matList']);
        $owner = htmlspecialchars($values['owner']);
        $idAnim = htmlspecialchars($values['id']);

        $query = 'UPDATE t_animation SET aniName=:aName, aniDuration=:duration, aniMatList=:matList, aniCreateBy=:createBy, aniOwner=:owner WHERE idAnimation=:id';

        $dataArray = array(
            'aName' => $name,
            'duration' => $duration,
            'matList' => $list,
            'owner' => $owner,
            'id' => $idAnim,
            'createBy' => $_SESSION['user']['id']
        );

        $request->update($query, $dataArray);

        //Delete linked season and theme
        $this->removeSeasonAnim($idAnim);
        $this->removeThemeAnim($idAnim);

        //If theme checkboxes are checked
        if(isset($values['theme'])){
            //Add each theme checked
            foreach ($values['theme'] as $item){
                $theme = htmlspecialchars($item);
                error_log('THEME '.print_r($themeRepo->findTheme($theme),true));
                $themeId = $themeRepo->findTheme($theme)[0]['idTheme'];

                $this->addThemeAnim($idAnim, $themeId);
            }
        }

        //If 'other' theme is checked
        if(isset($values['themeInput'])){
            // New theme name
            $theme = htmlspecialchars($values['otherTheme']);

            $themeId = $themeRepo->findTheme($theme)[0]['idTheme'];
            if ($themeRepo->findTheme($theme) == null) {
                // Last id
                $themeId = $themeRepo->addTheme($theme);
            }

            // Add link anim <-> theme
            $this->addIsTheme($idAnim, $themeId);
        }

        //If season checkboxes are checked
        if(isset($values['season'])){
            foreach ($values['season'] as $item){
                $season = htmlspecialchars($item);

                $seasonId = $seasonRepo->findSeason($season)[0]['idSeason'];

                $this->addSeasonAnim($idAnim, $seasonId);
            }
        }

        //If other season is checked
        if(isset($values['seasonInput'])){
            // New season name
            $season = htmlspecialchars($values['otherSeason']);

            $seasonId = $seasonRepo->findSeason($season)[0]['idSeason'];

            if ($seasonRepo->findSeason($season) == null) {
                // Last id
                $seasonId = $seasonRepo->addSeason($season);
            }

            // Add link anim <-> season
            $this->addSeasonAnim($idAnim, $seasonId);
        }
    }

    /**
     * Add new animation
     *
     * @param $values
     * @return array
     */
    public function addAnimation($values){
        $themeRepo = new ThemeRepository();
        $seasonRepo = new SeasonRepository();

        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlspecialchars($values['name']);
        $duration = htmlspecialchars($values['duration']);
        $list = htmlspecialchars($values['matList']);
        $owner = htmlspecialchars($values['owner']);

        $query = 'INSERT INTO t_animation (aniName, aniDuration, aniMatList, aniCreateBy, aniOwner) VALUES (:aName, :duration, :matList, :createBy, :owner)';

        $dataArray = array(
            'aName' => $name,
            'duration' => $duration,
            'matList' => $list,
            'owner' => $owner,
            'createBy' => $_SESSION['user']['id']
        );

        $lastAnimId = $request->insert($query, $dataArray);

        //If theme checkboxes are checked
        if(isset($values['theme'])){
            //Add each theme checked
            foreach ($values['theme'] as $item){
                $theme = htmlspecialchars($item);

                $themeId = $themeRepo->findTheme($theme)[0]['idTheme'];

                $this->addThemeAnim($lastAnimId, $themeId);
            }
        }

        //If 'other' theme is checked
        if(isset($values['themeInput'])){
            // New theme name
            $theme = htmlspecialchars($values['otherTheme']);

            $themeId = $themeRepo->findTheme($theme)[0]['idTheme'];
            if ($themeRepo->findTheme($theme) == null) {
                // Last id
                $themeId = $themeRepo->addTheme($theme);
            }

            // Add link anim <-> theme
            $this->addIsTheme($lastAnimId, $themeId);
        }

        //If season checkboxes are checked
        if(isset($values['season'])){
            foreach ($values['season'] as $item){
                $season = htmlspecialchars($item);

                $seasonId = $seasonRepo->findSeason($season)[0]['idSeason'];

                $this->addSeasonAnim($lastAnimId, $seasonId);
            }
        }

        //If other season is checked
        if(isset($values['seasonInput'])){
            // New season name
            $season = htmlspecialchars($values['otherSeason']);

            $seasonId = $seasonRepo->findSeason($season)[0]['idSeason'];

            if ($seasonRepo->findSeason($season) == null) {
                // Last id
                $seasonId = $seasonRepo->addSeason($season);
            }

            // Add link anim <-> season
            $this->addSeasonAnim($lastAnimId, $seasonId);
        }
    }

    /*
    * Hide animation instead of deleting it
    *
	* @param $id of the animation
	* @return
    */
    public function hideOne($id){
        $query = 'UPDATE t_animation SET aniActive=0 WHERE idAnimation=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }

    /**
     * Add link animation <-> theme
     *
     * @param $idAnim
     * @param $idTheme
     */
    private function addThemeAnim($idAnim, $idTheme){
        $request = new DataBaseQuery();

        $query = 'INSERT INTO t_themeanimation (idTheme, idAnimation) VALUES (:theme, :anim)';

        $dataArray = array(
            'theme' => $idTheme,
            'anim' => $idAnim,
        );

        $request->insert($query, $dataArray);
    }

    /**
     * Add link animation <-> season
     *
     * @param $idAnim
     * @param $idSeason
     */
    private function addSeasonAnim($idAnim, $idSeason){
        $request = new DataBaseQuery();

        $query = 'INSERT INTO t_seasonanim (idSeason, idAnimation) VALUES (:season, :anim)';

        $dataArray = array(
            'season' => $idSeason,
            'anim' => $idAnim,
        );

        $request->insert($query, $dataArray);
    }

    /**
     * Remove link animation <-> theme
     *
     * @param $idAnim
     */
    private function removeThemeAnim($idAnim){
        $request = new DataBaseQuery();

        $query = 'DELETE FROM t_themeanimation WHERE idAnimation=:id';

        $dataArray = array(
            'id' => $idAnim
        );

        $request->delete($query, $dataArray);
    }

    /**
     * Remove link animation <-> season
     *
     * @param $idAnim
     */
    private function removeSeasonAnim($idAnim){
        $request = new DataBaseQuery();

        $query = 'DELETE FROM t_seasonanim WHERE idAnimation=:id';

        $dataArray = array(
            'id' => $idAnim
        );

        $request->delete($query, $dataArray);
    }
}