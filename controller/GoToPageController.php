<?php
/**
 * ETML
 * Date: 01.06.2017
 * Shop
 */

class GoToPageController extends Controller {

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
    private function homeAction() {

        $view = file_get_contents('view/pages/home.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }
}