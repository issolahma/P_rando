<?php
/**
 * ETML
 * Date: 01.06.2017
 * Shop
 */

include_once 'database/DataBaseQuery.php';


class LoginRepository {

    /**
     * Find One entry
     *
     * @param $login
     * @return array
     */
    private function findOne($login) {

        $query = 'SELECT * FROM t_accompanist WHERE accLogin=:login';

        $dataArray = array(
            ':login' => $login
        );

        $request =  new DataBaseQuery();

        return $request->rawQuery($query, $dataArray);
    }

    /**
     * Login verification
     *
     * @param $login
     * @param $password
     *
     * @return
     */
    public function login($login, $password) {

        $result = $this->findOne($login);

        //If request return something, and password match
        if(isset($result) && count($result)>0 && $result[0]['accPwd'] == $password){
            //Add user id to $_SESSION variable
            $_SESSION['user']['id'] = $result[0]['idAccompanist'];
            $connect = $result[0]['accRight'];
        } else {
            $_SESSION = null; //Login false -> reset $_SESSION variable
            $connect = 'false';
        }
        
        //Return either false or the user datas
        return $connect;
    }
}