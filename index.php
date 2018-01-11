<?php
session_start();

/**
 * ETML
 * Date: 01.06.2017
 */

$debug = true;

if ($debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

include 'controller/Controller.php';

include 'controller/AccompanistController.php';
include 'controller/AnimationController.php';
include 'controller/ClientsController.php';
include 'controller/DifficultyController.php';
include 'controller/GoToPageController.php';
include 'controller/LodgingController.php';
include 'controller/LoginController.php';
include 'controller/MedicController.php';
include 'controller/SeasonController.php';
include 'controller/SicknessController.php';
include 'controller/ThemeController.php';

date_default_timezone_set('Europe/Zurich');

class MainController {

    /**
     * Constructor for view display
     *
     * @return void 
     */
    public function dispatch() {

        if (!isset($_GET['controller'])) {
            $_GET['controller'] = 'login';
            $_GET['action'] = 'show';
        }


        $currentLink = $this->menuSelected($_GET['controller']);
        $this->viewBuild($currentLink);
    }

    /**
     * Selected the page current
     *
     * @param string $page
     * @return string
     */
    protected function menuSelected ($page) {

        switch($_GET['controller']){
            case 'accompanist':
                $link = new AccompanistController();
                break;
            case 'admin':
                $link = new AdminController();
                break;
            case 'anim':
                $link = new AnimationController();
                break;
            case 'client':
                $link = new ClientsController();
                break;
            case 'diff':
                $link = new DifficultyController();
                break;
            case 'goto':
                $link = new GoToPageController();
                break;
            case 'lodg':
                $link = new LodgingController();
                break;
            case 'login':
                $link = new LoginController();
                break;
            case 'medic':
                $link = new MedicController();
                break;
            case 'season':
                $link = new SeasonController();
                break;
            case 'sick':
                $link = new SicknessController();
                break;
             case 'theme':
                $link = new ThemeController();
                break;
            default:
                $link = new GoToPageController();
                break;
        }
        return $link;
    }

    /**
     * Build the view for display pages
     *
     * @param $currentPage
     * @return void
     */
    protected function viewBuild($currentPage) {

            $content = $currentPage->display();

            if (get_class($currentPage) == 'DownloadController' || !empty($_GET['boolAjax']) || !isset($_SESSION['user'])) {
                echo $content;
            } else {
                include(dirname(__FILE__) . '/view/head.html');
                include(dirname(__FILE__) . '/view/header.php');
                echo $content;
                include(dirname(__FILE__) . '/view/footer.html');
            }
    }
}

/**
 * Display WebSite
 */
$controller = new MainController();
$controller->dispatch();