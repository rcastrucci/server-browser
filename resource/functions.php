<?PHP
// Polyfill for PHP 4 - PHP 7, safe to utilize with PHP 8
if (!function_exists('str_contains')) {
    function str_contains (string $haystack, string $needle)
    {
        return empty($needle) || strpos($haystack, $needle) !== false;
    }
}

/* AUTHENTICATE USER */
if (isset($_POST['u']) && isset($_POST['p'])) {
    $dbUsers = new Csv();
    $dbUsers->setFile('.users.csv');
    $myDB = $dbUsers->getContent();

    if ($myDB) {
        $_SESSION['user'] = new User();
        $_SESSION['user']->setInputUser(strtolower($_POST['u']));
        $_SESSION['user']->setInputPass($_POST['p']);
        if ($_SESSION['user']->authenticate($myDB)) {
            $_SESSION['user']->getFiles();
        }
    }
}

/* REQUEST LOGOUT */
if (isset($_GET['logout'])) {
    $_SESSION['user']->logout();
}

/* FUNCTION OPEN A FOLDER */
if (isset($_GET['open'])) {
    if (!str_contains($_GET['open'], '../') && !str_contains($_GET['open'], './') && !str_contains($_GET['open'], '..')) {
        $_SESSION['user']->addLevel($_GET['open'] . '/');
    }
}

/* FUNCTION BACK A FOLDER LEVEL */
if (isset($_GET['back'])) {
    $_SESSION['user']->backLevel();
}

/* REQUEST TO DOWNLOAD FILE OR FOLDER */
if(isset($_REQUEST["file"]) && $_SESSION['user']->isLogged()) {
    // Get parameters
    $file = urldecode($_REQUEST["file"]); // Decode URL-encoded string

    $filepath = $_SESSION['user']->getLevel() . $file;

    // Process download if it is not a directory
    if(file_exists($filepath) && !(is_dir($filepath))) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($filepath));
        header('Content-Transfer-Encoding: Binary'); 
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush(); // Flush system output buffer
        readfile($filepath);
        die();
    } else if (is_dir($filepath)) {
        http_response_code(403);
    } else {
        http_response_code(404);
        die();
    }
}

/* FUNCTION TO GET FOLDER SIZE */
function getDirectorySize($path){
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false && $path!='' && file_exists($path)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}
?>