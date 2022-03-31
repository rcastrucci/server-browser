<?PHP
include('./resource/classes.php');
include('./resource/functions.php');

if (isset($_SESSION['user']) && $_SESSION['user']->isLogged()) {
    include('./resource/content.php');
} else {
    include('./resource/login.php');
}
?>