<?php
/**
 * ETML
 * Date: 01.06.2017
 * Shop
 */

include_once 'database/DataBaseQuery.php';


class LoginRepository {

    /**
     * Find all entries
     *
     * @return array|resource
     */
   /* public function findAll() {

        $query = 'SELECT accLogin FROM t_accompanist';

        $request =  new DataBaseQuery();

        return $request->rawQuery($query);

    }

    /**
     * Find One entry
     *
     * @param $login
     *
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
     * Login
     *
     * @param $login
     * @param $password
     *
     * @return bool
     */
    public function login($login, $password) {

        $result = $this->findOne($login);

        if(isset($result) && count($result)>0 && $result[0]['accPwd'] == $password){
            $_SESSION['user']['id'] = $result[0]['idAccompanist'];
            $connect = $result[0]['accRight'];
        } else {
            $_SESSION = null;
            $connect = 'false';
        }

        return $connect;
    }
}