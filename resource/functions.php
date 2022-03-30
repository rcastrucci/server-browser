<?PHP
// Polyfill for PHP 4 - PHP 7, safe to utilize with PHP 8
if (!function_exists('str_contains')) {
    function str_contains (string $haystack, string $needle)
    {
        return empty($needle) || strpos($haystack, $needle) !== false;
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
if(isset($_REQUEST["file"]) && $_SESSION['ACCESS'] && isset($_SESSION['user']) && $_SESSION['user']->isLogged()) {
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
        downloadZip($filepath);
    } else {
        http_response_code(404);
        die();
    }
}

/* AUTHENTICATE USER */
if (isset($_POST['u']) && isset($_POST['p'])) {
    $usersCsvFile = new Csv();
    $usersCsvFile->setFile('.users.csv');

    if ($usersCsvFile->getContent()) {
        $_SESSION['user'] = new User();
        $_SESSION['user']->readFromCsv($usersCsvFile->getContent(), strtolower($_POST['u']), $_POST['p']);
        $_SESSION['user']->getFiles();
    }
}

/* FUNCTION TO ZIPARCHIVE A FOLDER */
function downloadZip($folder) {
    $zip_file = $folder.'.zip';

    // Get real path for our folder
    $rootPath = realpath($folder);

    // Initialize archive object
    $zip = new ZipArchive();
    $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file) {
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Zip archive will be created only after closing object
    $zip->close();
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($zip_file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($zip_file));
    flush(); // Flush system output buffer
    if (readfile($zip_file)) {
        unlink($zip_file);
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