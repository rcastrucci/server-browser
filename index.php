<?PHP
include('./resource/classes.php');
include('./resource/functions.php');

/************************* SET UP PAGE *************************/
$configFile = new Csv();
$configFile->setFile('.config.csv');
$_SESSION['config'] = new Config();
$_SESSION['config']->setConfig($configFile->getContent());
/************************* END SET UP **************************/

/**************************************** RETURN CONTENT OR LOGIN PAGE ************************************************/
if (isset($_SESSION['ACCESS']) && $_SESSION['ACCESS'] && isset($_SESSION['user'])) {
    include('./resource/content.php');
} else {
    include('./resource/login.php');
}
/************************************************ END OF DOCUMENT *****************************************************/
?>