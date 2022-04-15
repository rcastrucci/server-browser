<?PHP
include('./resource/head.php');
?> 
  
<body>   
    <!-- MAIN CONTENT -->
    <div class="container-fluid">
        <div class="row">
            <section class="d-flex flex-column pt-bar px-4 px-sm-5 px-md-0 d-none">
                <!-- WINDOW CONTAINER -->
                <div class="window-container col-12 col-sm-12 col-md-9 col-lg-7 col-xl-6 max-width-outer">
                    <!-- HEAD -->
                    <div class="window-head">
                        <!-- WINDOW BUTTONS -->
                        <div>
                            <img id="btn_close" class="px-2 btn-icon" src="./images/icon_close.png" alt="close" data-hover="./images/icon_close_hover.png">
                            <img id="btn_min" class="px-2 btn-icon" src="./images/icon_min.png" alt="minimize" data-hover="./images/icon_min_hover.png">
                            <img id="btn_max" class="px-2 btn-icon" src="./images/icon_max.png" alt="maximize" data-hover="./images/icon_max_hover.png">
                        </div>
                        <!-- PATH AND BACK BUTTON -->
                        <div class="d-flex flex-row justify-content-center align-items-center">
                            <?PHP if ($_SESSION['user']->countLevel() > 0) {
                                ?>
                                <a href="index.php?back"> <span class="px-2 me-4"><?PHP echo($_SESSION['user']->getUserName()); ?>: <?PHP echo($_SESSION['user']->getPath()); ?></span> </a>
                                <a href="index.php?back"><img class="px-2 icon" src="./images/icon_back.png" alt="back"></a>
                                <?PHP
                            } else {
                                ?>
                                <span class="px-2 me-4"><?PHP echo($_SESSION['user']->getUserName()); ?>: <?PHP echo($_SESSION['user']->getPath()); ?></span>
                                <img class="px-2 icon-disabled" src="./images/icon_back.png" alt="back">
                                <?PHP
                            } ?>
                        </div>
                    </div>
                    <!-- LABELS -->
                    <div class="window-labels d-flex flex-row px-3">
                        <div class="col-5 border-right">
                            <span>Name</span>
                        </div>
                        <div class="col-3 border-right">
                            <span class="ms-3">Date</span>
                        </div>
                        <div class="col-2 border-right">
                            <span class="ms-3">Size</span>
                        </div>
                        <div class="col-2">
                            <span class="ms-3">Action</span>
                        </div>
                    </div>
                    <!-- CONTENT -->
                    <div class="window-content d-flex flex-column mt-4">

                    <?PHP
                        $files = $_SESSION['user']->getFiles();
                        if ($files) {
                            foreach ($files as $filename) {
                                $READER = false;
                                /* LIST FILES EXCEPT SYSTEM FILES */
                                if ($filename !== '.' && $filename !== '..' && $filename[0] !== '.') {
                                    /* CHECK IF IT IS DIRECTORY OR A FILE */
                                    $isDir = is_dir($_SESSION['user']->getLevel() . $filename);
                                    if ($isDir) {
                                        $SIZE = 0;
                                        $ICON = 'icon_folder.png';
                                        $ALT = 'folder';
                                        $ACTION = 'icon_open.png';
                                        $ACTION_ALT = 'open';
                                        $LINK = 'index.php?open=';
                                    }
                                    else {
                                        $SIZE = round(filesize($_SESSION['user']->getLevel() . $filename) / 1024 / 1024, 1);
                                        if (str_contains(strtolower($filename), '.jpg') || str_contains(strtolower($filename), '.jpeg') || str_contains(strtolower($filename), '.png')) {
                                            $ICON = 'icon_jpg.png';
                                        } else if (str_contains(strtolower($filename), '.tif') || str_contains(strtolower($filename), '.tiff')) {
                                            $ICON = 'icon_tiff.png';
                                        } else if (str_contains(strtolower($filename), '.zip') || str_contains(strtolower($filename), '.gzip')) {
                                            $ICON = 'icon_zip.png'; }
                                        else if (str_contains(strtolower($filename), '.mp4') || str_contains(strtolower($filename), '.mov')) {
                                                $ICON = 'icon_mp4.png';
                                        } else if (str_contains(strtolower($filename), '.txt') || str_contains(strtolower($filename), '.rtf') || str_contains(strtolower($filename), '.doc')) {
                                            $ICON = 'icon_txt.png';
                                            $READER = true;
                                        } else {
                                            $ICON = 'icon_txt.png';
                                        }
                                        $ALT = 'file';
                                        $ACTION = 'icon_cloud.png';
                                        $ACTION_ALT = 'download';
                                        $LINK = 'index.php?file=';
                                    }

                                    /* CHECK SIZE AND FORMAT GB or MB */
                                    if ($SIZE === 0) $SIZE = '--';
                                    else if ($SIZE > 1000) $SIZE = round($SIZE/1000) . ' GB';
                                    else $SIZE = $SIZE . ' MB';

                                    /* GET THE DATE */
                                    $MODIFIED = date("d/m/Y à\s H:i\h", filemtime($_SESSION['user']->getLevel() . $filename));
                                    ?>
                                    <!-- CONTENT ROW -->
                                    <div class="d-flex flex-row align-items-center col-12 px-2">
                                        <a href="<?PHP echo($LINK . $filename); ?>" class="col-5 d-flex flex-row align-items-center py-2">
                                            <div class="d-flex flex-row justify-content-center icon-box">
                                                <img class="icon-content" src="./images/<?PHP echo($ICON); ?>" alt="<?PHP echo($ALT); ?>">
                                            </div>
                                            <span class="fw-bold"><?PHP echo($filename); ?></span>
                                        </a>
                                        <div class="col-3 ps-2">
                                            <span class="ms-3"><?PHP echo($MODIFIED); ?></span>
                                        </div>
                                        <div class="col-2 ps-2">
                                            <span class="ms-3"><?PHP echo($SIZE); ?></span>
                                        </div>

                                        <!-- ACTION BUTTONS -->
                                        <div class="col-2 d-flex flex-row justify-content-evenly">
                                            <a href="<?PHP echo($LINK . $filename); ?>"> <img class="icon" src="./images/<?PHP echo($ACTION); ?>" alt="<?PHP echo($ACTION_ALT); ?>" title="<?PHP echo(ucfirst($ACTION_ALT)); ?>"> </a>
                                            <?PHP
                                            if ($READER) {
                                                $ACTION = 'icon_open.png';
                                                $ACTION_ALT = 'read file';
                                                $LINK = 'index.php?read='; ?>
                                                <a href="<?PHP echo($LINK . $filename); ?>"> <img class="icon" src="./images/<?PHP echo($ACTION); ?>" alt="<?PHP echo($ACTION_ALT); ?>" title="<?PHP echo(ucfirst($ACTION_ALT)); ?>"> </a>
                                            <?PHP } ?>
                                            <?PHP if ($_SESSION['user']->getUserName() === 'admin') { ?>
                                            <a href="index.php?erase=<?PHP echo($filename); ?>"> <img class="icon" src="./images/icon_trash.png" alt="delete" title="Delete"> </a>
                                            <?PHP } ?>
                                        </div>
                                    </div>
                                    <!-- END CONTENT ROW -->
                                    <?PHP
                                }
                            }
                        } else {
                            /* EMPTY DIRECTORY */
                        }
                        ?>
                    </div>
                    <!-- END CONTENT -->
                </div>
                <!-- END WINDOW CONTAINER -->
                <?PHP
                if (isset($_GET['read']) && file_exists($realPath = $_SESSION['user']->getUserFolder() . $_SESSION['user']->getPath() . $_GET['read'])) {
                    $fileToRead = new FileReader();
                    $fileToRead->setFile($realPath);
                ?>
                    <!-- WINDOW CONTAINER READ FILES -->
                    <div id="windowReader" class="window-container col-12 col-sm-12 col-md-9 col-lg-7 col-xl-6 max-width-outer">
                        <!-- HEAD -->
                        <div id="headReader" class="window-head">
                            <!-- WINDOW BUTTONS -->
                            <div>
                                <img id="btn_close_reader" class="px-2 btn-icon" src="./images/icon_close.png" alt="close" data-hover="./images/icon_close_hover.png">
                                <img id="btn_min_reader" class="px-2 btn-icon" src="./images/icon_min.png" alt="minimize" data-hover="./images/icon_min_hover.png">
                                <img id="btn_max_reader" class="px-2 btn-icon" src="./images/icon_max.png" alt="maximize" data-hover="./images/icon_max_hover.png">
                            </div>
                            <!-- PATH AND BACK BUTTON -->
                            <div class="d-flex flex-row justify-content-center align-items-center">
                                <span class="px-2 me-4"><?PHP echo($fileToRead->getFile()); ?></span>
                            </div>
                        </div>

                        <!-- CONTENT -->
                        <div class="window-content d-flex flex-column mt-4">
                            <!-- CONTENT ROW -->
                            <section class="d-flex flex-row align-items-center col-12 px-2">
                                <div class="col-12 ps-2">
                                    <div><?PHP $fileToRead->read(); ?></div>
                                </div>
                            </section>
                            <!-- END CONTENT ROW -->
                        </div>
                        <!-- END CONTENT -->
                    </div>
                    <!-- END WINDOW CONTAINER -->
                <?PHP
                }
                ?>
            </section>
        </div>
    </div>

    <!-- TOP BAR MENU -->
    <section class="top-bar">
        <span><?PHP echo($_SESSION['config']->getTitle()); ?></span>
        <div>
            <img id="btn_account" class="px-2 icon" src="./images/icon_account.png" alt="User Account" title="Account">
            <a href="index.php?logout"><img class="px-2 icon" src="./images/icon_off.png" alt="Logoff" title="Logout"></a>
        </div>
    </section>

    <!-- TOAST -->   
    <div aria-live="polite" aria-atomic="true" class="fixed-bottom mb-5 d-flex justify-content-center align-items-center">
        <div id="toast" class="toast align-items-center text-white bg-primary border-0 p-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fs-5 text-center"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <!-- END OF TOAST -->

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="./js/sha256.js"></script>
    <script src="./js/functions.js"></script>
</body>
</html>