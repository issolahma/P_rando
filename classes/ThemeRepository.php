<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 10.01.2018
 */

include_once 'database/DataBaseQuery.php';

class ThemeRepository {

    /**
     * Find all data for the theme list
     *
     * @return array|resource
     */
    public function findAll($post) {
        //From the search input
        $searchValue = htmlentities($post["search"]["value"]);

        $query = 'SELECT * FROM t_theme ';


        if(!empty($post["search"]["value"])){
            //Make the search on name
            $query .= 'WHERE theName LIKE "%'.$searchValue.'%" ';
        }

        //Order by the chosen column
        if(!empty($post['order'])){
            $orderCol = htmlentities($post['order']['0']['column']); //Column number

            //Convert column number to column name for the sql query
            switch($orderCol) {
                case 1:
                    $orderCol = 'theName';
                    break;
                default:
                    $orderCol = 'theName';

            }

            //Order direction Asc or Desc
            $orderDir = htmlentities($post['order']['0']['dir']);

            $query .= 'ORDER BY '.$orderCol.' '.$orderDir.' ';
        }
        else{
            $query .= 'ORDER BY theName ASC '; //By default order by name asc
        }

        if($post["length"] != -1){
            $start = htmlentities($post['start']);
            $length = htmlentities($post['length']);

            $query .= 'LIMIT ' . $start . ', ' . $length;
        }

        $request =  new DataBaseQuery();

        return $request->rawQuery($query, null); //Null for no dataArray

    }

    /**
     *  Find one theme by name
     *
     * @param $name
     * @return array
     */
    public function findTheme($name){
        $query = 'SELECT * FROM t_theme WHERE theName=:name';

        $dataArray = array(
            'name' => $name
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }


    /**
     *  Find one theme by id
     *
     * @param $id
     * @return array
     */
    public function findOne($id){
        $query = 'SELECT * FROM t_theme WHERE idTheme=:id AND theActive=1 LIMIT 1';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->rawQuery($query, $dataArray);
    }

    /**
     *  Update theme name
     *
     * @param $values
     * @return bool
     */
    public function updateTheme($values) {
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);
        $id = htmlentities($values['theme_id']);

        $query = 'UPDATE t_theme SET theName=:tName WHERE idTheme=:id';

        $dataArray = array(
            'tName' => $name,
            'id' => $id
        );

        return $request->update($query, $dataArray);
    }

    /**
     *  Add a new theme
     *
     * @param $values
     * @return string
     */
    public function addTheme($values){
        $request = new DataBaseQuery();

        //Values from $_Post
        $name = htmlentities($values['name']);

        $query = 'INSERT INTO t_theme (theName, theCreateBy) VALUES (:tName, :createBy)';

        $dataArray = array(
            'tName' => $name,
            'createBy' => $_SESSION['user']['id']
        );

        return $request->insert($query, $dataArray);
    }

    /**
     *  Hide theme instead of deleting it
     *
     * @param $id
     * @return bool
     */
    public function hideOne($id){
        $query = 'UPDATE t_theme SET theActive=0 WHERE idTheme=:id';

        $dataArray = array(
            'id' => $id
        );

        $request = new DataBaseQuery();
        return $request->update($query, $dataArray);
    }
}