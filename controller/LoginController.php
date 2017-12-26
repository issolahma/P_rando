<?php
/**
 * ETML
 * Date: 01.06.2017
 * Shop
 */
include_once 'classes/LoginRepository.php';

class LoginController extends Controller {

    /**
     * Dispatch current action
     *
     * @return mixed
     */
    public function display() {

        $action = $_GET['action'] . "Action";

        return call_user_func(array($this, $action));
    }

    /**
     * Display Index Action
     *
     * @return string
     */
    private function showAction() {

        $view = file_get_contents('view/pages/login.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Display Login Action
     *
     * @return string
     */
    private function loginAction() {

        $login = $_POST['login'];
        $password = md5($_POST['password']);

        $loginRepository = new LoginRepository();
        $result = $loginRepository->login($login, $password);

        if($result == 'false'){
            $text = "Login ou mot de passe incorrect!";
            $view = file_get_contents('view/pages/login.php');
        }
        else{
        		$_SESSION['user']['right'] = $result;
            $_SESSION['user']['login'] = $login;
            $view = file_get_contents('view/pages/home.php');
        }

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Diplay Logout Action
     * 
     * @return string
     */
    private function logoutAction() {
        $_SESSION = null;

        $view = file_get_contents('view/pages/login.php');


        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;

    }

}