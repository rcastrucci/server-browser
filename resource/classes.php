<?PHP
session_start();

class Csv {
    private $filename;
    private $csvContent;

    function setFile($filename) {
        $this->filename = $filename;
    }

    function getFile(){
        return $this->filename;
    }

    function getContent() {
        if (!is_array($this->csvContent) || count($this->csvContent) <= 0 || $this->csvContent == null) {
            $this->loadCsv();
        }
        return $this->csvContent;
    }

    function loadCsv() {
        if (($this->filename !== null) && file_exists($this->filename)) {
            $this->csvContent = array();
            $csvFiles = fopen($this->filename, "r");
            if ($csvFiles) {
                while(! feof($csvFiles)) {
                    array_push($this->csvContent, fgetcsv($csvFiles, 1000, ","));
                }
            }
            fclose($csvFiles);
        } else {
            $this->csvContent = array();
        }
    }

    function setContent($data) {
        if (($this->filename !== null) && ($data) && is_array($data)) {
            // open csv file for writing
            $file = fopen($this->filename, 'w');
            if ($file) {
                // write each row at a time to a file
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
                // close the file
                fclose($file);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

class User {
    public static $filename = '.users.csv';
    private $userName;
    private $userFullName;
    private $userEmail;
    private $userPass;
    private $userCompany;
    private $userFolder;
    private $inputUser;
    private $inputPass;
    private $userLevel;
    private $userFiles = array();

    function getFiles() {
        if (file_exists($this->getLevel())) {
            $this->userFiles = scandir($this->getLevel());
        }
        return $this->userFiles;
    }
    function countLevel() {
        if (is_array($this->userLevel)) {
            return count($this->userLevel);
        } else {
            return 0;
        }
    }
    function getLevel() {
        $realPath = $this->userFolder;
        if ($this->countLevel() > 0) {
            foreach ($this->userLevel as $folderName) {
                $realPath .= $folderName;
            }
        }
        return $realPath;
    }
    function getPath() {
        $path = '';
        if ($this->countLevel() > 0) {
            foreach ($this->userLevel as $folderName) {
                $path .= $folderName;
            }
        }
        return $path;
    }
    function addLevel($folderName) {
        array_push($this->userLevel, $folderName);
        if (file_exists($this->getLevel())) {
            return true;
        } else {
            array_pop($this->userLevel);
            return false;
        }
    }
    function backLevel() {
        if ($this->countLevel() > 0) {
            array_pop($this->userLevel);
            /* IF FOR SOME REASON THE LEVEL DOESN'T EXIST ANYMORE GOES BACK TO THE USER'S ROOT FOLDER */
            if (!file_exists($this->getLevel())) {
                $this->userLevel = array();
            }
        } else {
            $this->userLevel = array();
        }
    }
    function setInputUser($inputUser) {
        $this->inputUser = $inputUser;
    }
    function setInputPass($inputPass) {
        $this->inputPass = $inputPass;
    }
    function authenticate($csvContent) {
        foreach ($csvContent as $value) {
            if ($value) {
                if ($this->inputUser === $value[3] && ($this->inputPass === $value[5])) {
                    $this->userCompany = $value[0];
                    $this->userFolder = $value[1];
                    $this->userLevel = array();
                    $this->userFullName = $value[2];
                    $this->userName = $value[3];
                    $this->userEmail = $value[4];
                    $this->userPass = $value[5];
                    $this->userFiles = $this->getFiles();
                    return true;
                }
            } else {
                return false;
            }
        }
    }
    function isLogged() {
        return ($this->userName !== null && 
                $this->userName !== '' &&
                $this->userPass !== null && 
                $this->userPass !== '' &&
                $this->userName === $this->inputUser && 
                $this->userPass === $this->inputPass);
    }
    function logout() {
        $this->userCompany = null;
        $this->userFolder = null;
        $this->userLevel = array();
        $this->userFullName = null;
        $this->userName = null;
        $this->userEmail = null;
        $this->userPass = null;
        session_destroy();
    }
    function getUserName() {
        return $this->userName;
    }
    function getUserFullName() {
        return $this->userFullName;
    }
    function getUserEmail() {
        return $this->userEmail;
    }
    function getUserPass() {
        return $this->userPass;
    }
    function getUserCompany() {
        return $this->userCompany;
    }
    function getUserFolder() {
        return $this->userFolder;
    }
}

class Config {
    public static $filename = '.config.csv';
    private $author;
    private $description;
    private $title;
    private $subTitle;

    function getAuthor() {
        if ($this->author === null || $this->author === '') {
            $this->author = 'dev rcastrucci';
        }
        return $this->author;
    }
    function getDescription() {
        if ($this->description === null || $this->description === '') {
            $this->description = 'webcloud clients service';
        }
        return $this->description;
    }
    function getTitle() {
        if ($this->title === null || $this->title === '') {
            $this->title = 'WebCloud';
        }
        return $this->title;
    }
    function getSubTitle() {
        if ($this->subTitle === null) {
            $this->subTitle = '';
        }
        return $this->subTitle;
    }
    function setConfig($csvContent) {
        foreach ($csvContent as $value) {
            if ($value[0] === 'title') {
                $this->title = $value[1];
            }
            if ($value[0] === 'author') {
                $this->author = $value[1];
            }
            if ($value[0] === 'description') {
                $this->description = $value[1];
            }
            if ($value[0] === 'subtitle') {
                $this->subTitle = $value[1];
            }
        }
    }
}

?>