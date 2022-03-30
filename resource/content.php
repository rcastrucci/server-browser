<?PHP
include('./resource/head.php');
?>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="d-flex flex-column justify-content-center align-items-center mt-5">
                <header>
                    <h1 class="display-1 fs-1"><?PHP echo($_SESSION['config']->getTitle()); ?></h1>
                </header>
                <section class="d-flex flex-column col-12 col-sm-9 col-md-9 col-lg-6 text-center">
                    <span class="display-6 text-muted fs-6 mb-4"><?PHP echo($_SESSION['config']->getSubTitle()); ?></span>
                    <div class="d-flex flex-column align-items-start text-muted">
                        <span><?PHP echo($_SESSION['user']->getUserCompany()); ?></span>
                        <span><?PHP echo(ucfirst($_SESSION['user']->getUserFullName())); ?></span>
                        <a href="index.php?logout">logout</a>
                        <a class="text-start text-muted mt-4" href="index.php?back"><span><?PHP echo($_SESSION['user']->getUserName()) ?>://<?PHP echo($_SESSION['user']->getPath()); ?></span></a>
                    </div>
                </section>
                <section class="col-12 col-sm-9 col-md-9 col-lg-6 overflow-auto mb-5">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th class="text-center" scope="col">Size</th>
                            <th class="text-center" scope="col">
                                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            </th>
                            <th scope="col"></th>
                            <th scope="col">
                                <?PHP 
                                    if ($_SESSION['user']->countLevel() > 0) {
                                        ?>
                                <a href="index.php?back"><svg enable-background="new 0 0 32 32" viewBox="0 0 32 32" xml:space="preserve" class="icon"><path d="M31.866,29.046c0.05-0.336,1.109-8.41-3.639-13.966  c-2.917-3.414-7.418-5.283-13.23-5.468l-0.031-5.613c0-0.386-0.223-0.737-0.571-0.903c-0.349-0.163-0.762-0.116-1.061,0.128  L0.367,13.866C0.135,14.056,0,14.34-0.001,14.64c-0.001,0.3,0.133,0.584,0.365,0.774l12.968,10.743  c0.298,0.247,0.715,0.298,1.061,0.132c0.351-0.165,0.573-0.518,0.573-0.904l0.031-5.739c14.009-0.01,15.005,8.966,15.039,9.337  c0.043,0.504,0.362,0.897,0.868,0.913c0.012,0.001,0.023,0.001,0.034,0.001C31.433,29.897,31.792,29.536,31.866,29.046z   M13.261,17.922c-0.188,0.188-0.294,0.442-0.294,0.708v4.638L2.578,14.646l10.389-8.542v4.493c0,0.553,0.447,1,1,1  c5.69,0,10.037,1.648,12.735,4.776c2.029,2.354,2.962,5.235,3.281,7.626c-2.184-2.984-6.008-6.363-16.015-6.369c0,0-0.001,0-0.002,0  C13.702,17.63,13.448,17.735,13.261,17.922z" fill="#121313"/> </svg></a>
                                        <?PHP
                                    }
                                ?>
                            </th>
                            <!-- ADD A SPECIAL COL IF USER IS ADMIN -->
                            <?PHP if ($_SESSION['user']->getUserName() === 'admin') {
                            ?>
                            <th scope="col">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M20.57,9.43A8,8,0,0,0,5.26,10,5,5,0,1,0,5,20h5V18H5a3,3,0,0,1,0-6,3.1,3.1,0,0,1,.79.12l1.12.31.14-1.15a6,6,0,0,1,11.74-.82l.15.54.54.16A3.46,3.46,0,0,1,22,14.5,3.5,3.5,0,0,1,18.5,18H16v2h2.5A5.48,5.48,0,0,0,20.57,9.43Z"/><polygon points="16.71 15.29 13 11.59 9.29 15.29 10.71 16.71 12 15.41 12 20 14 20 14 15.41 15.29 16.71 16.71 15.29"/></svg>
                            </th>
                            <?PHP
                            } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?PHP
                            $files = $_SESSION['user']->getFiles();
                            if ($files) {
                                foreach ($files as $filename) {
                                    if ($filename !== '.' && $filename !== '..' && $filename[0] !== '.') {
                                        $isDir = false;
                                        if (is_dir($_SESSION['user']->getLevel() . $filename)) {
                                            /* $SIZE = round(getDirectorySize($DATA[1] . $filename) / 1024 / 1024, 1); */
                                            $SIZE = 0;
                                            $isDir = true;
                                        } else {
                                            $SIZE = round(filesize($_SESSION['user']->getLevel() . $filename) / 1024 / 1024, 1);
                                        }
                                        /* CHECK SIZE AND FORMAT GB or MB */
                                        if ($SIZE > 1000) {
                                            $SIZE = round($SIZE/1000) . ' GB';
                                        } else if ($SIZE === 0) {
                                            $SIZE = '';
                                        } else {
                                            $SIZE = $SIZE . ' MB';
                                        }
                                        $MODIFIED = date("d/m/Y à\s H:i\h", filemtime($_SESSION['user']->getLevel() . $filename));
                                        ?>
                                        <tr>
                                        <td><?PHP echo($MODIFIED);?></td>
                                        <?PHP
                                        if ($isDir) {
                                        ?>
                                        <td><a class="fw-bold" href="index.php?open=<?PHP echo(urlencode($filename));?>"><?PHP echo($filename);?></a></td>
                                        <?PHP } else {
                                        ?>
                                        <td><?PHP echo($filename); ?></td>
                                        <?PHP
                                        }
                                        ?>
                                        <td class="text-center"><?PHP echo($SIZE); ?></td>
                                        <td class="text-center"></td>
                                        <td><a building-msg="download" href="index.php?file=<?PHP echo(urlencode($filename));?>">
                                            <svg style="enable-background:new 0 0 24 24;" class="icon" viewBox="0 0 24 24" xml:space="preserve"> <path d="M11.2,16.6c0.4,0.5,1.2,0.5,1.6,0l6-6.3C19.3,9.8,18.8,9,18,9h-4c0,0,0.2-4.6,0-7c-0.1-1.1-0.9-2-2-2c-1.1,0-1.9,0.9-2,2 c-0.2,2.3,0,7,0,7H6c-0.8,0-1.3,0.8-0.8,1.4L11.2,16.6z"/><path d="M19,19H5c-1.1,0-2,0.9-2,2v0c0,0.6,0.4,1,1,1h16c0.6,0,1-0.4,1-1v0C21,19.9,20.1,19,19,19z"/> </svg>
                                        </a></td>
                                        <?PHP
                                        if ($isDir) {
                                        ?>
                                        <td><a href="index.php?open=<?PHP echo(urlencode($filename));?>">
                                            <svg class="icon" viewBox="0 0 545.027 545.027" style="enable-background:new 0 0 545.027 545.027;" xml:space="preserve"> <path d="M540.743,281.356c-4.179-8.754-10.568-15.464-19.123-20.12c-8.566-4.665-17.987-6.995-28.264-6.995h-54.819v-45.683 c0-17.511-6.283-32.548-18.843-45.111c-12.566-12.562-27.604-18.842-45.111-18.842H219.268v-9.136 c0-17.511-6.283-32.548-18.842-45.107c-12.564-12.562-27.6-18.846-45.111-18.846H63.953c-17.511,0-32.548,6.283-45.111,18.846 C6.28,102.922,0,117.959,0,135.47v274.088c0,17.508,6.28,32.545,18.842,45.104c12.563,12.565,27.6,18.849,45.111,18.849h310.636 c12.748,0,26.07-3.285,39.971-9.855c13.895-6.563,24.928-14.894,33.113-24.981L531.9,335.037 c8.754-11.037,13.127-22.453,13.127-34.26C545.031,293.923,543.603,287.458,540.743,281.356z M36.547,135.474 c0-7.611,2.663-14.084,7.993-19.414c5.326-5.327,11.799-7.993,19.414-7.993h91.365c7.615,0,14.084,2.663,19.414,7.993 c5.327,5.33,7.993,11.803,7.993,19.414v18.274c0,7.616,2.667,14.087,7.994,19.414s11.798,7.994,19.412,7.994h164.452 c7.611,0,14.089,2.666,19.418,7.993c5.324,5.326,7.99,11.799,7.99,19.414v45.682H182.725c-12.941,0-26.269,3.284-39.973,9.851 c-13.706,6.567-24.744,14.893-33.12,24.986l-73.085,89.931V135.474z M503.345,311.917l-83.939,103.637 c-4.753,5.899-11.512,10.943-20.272,15.125c-8.754,4.189-16.939,6.283-24.551,6.283H63.953c-10.088,0-15.131-3.333-15.131-9.992 c0-3.046,1.713-6.852,5.14-11.427l83.938-103.633c4.949-5.903,11.75-10.896,20.413-14.989c8.658-4.093,16.796-6.14,24.411-6.14 h310.631c10.088,0,15.129,3.333,15.129,9.993C508.485,304.019,506.778,307.728,503.345,311.917z"/> </svg>
                                        </a></td>
                                        <?PHP
                                        } else {
                                            ?><td></td><?PHP
                                        }

                                        /* ADD DELETE FUNCTION IF USER ADMIN */
                                        if ($_SESSION['user']->getUserName() === 'admin') {
                                        ?>
                                        <td><a href="index.php?del=' . urlencode($filename) . '">
                                            <svg class="icon" viewBox="0 0 24 24" fill="none"> <path fill-rule="evenodd" clip-rule="evenodd" d="M11 2C10.4477 2 10 2.44772 10 3V4H14V3C14 2.44772 13.5523 2 13 2H11ZM16 4V3C16 1.34315 14.6569 0 13 0H11C9.34315 0 8 1.34315 8 3V4H3C2.44772 4 2 4.44772 2 5C2 5.55228 2.44772 6 3 6H3.10496L4.80843 21.3313C4.97725 22.8506 6.26144 24 7.79009 24H16.2099C17.7386 24 19.0228 22.8506 19.1916 21.3313L20.895 6H21C21.5523 6 22 5.55228 22 5C22 4.44772 21.5523 4 21 4H16ZM18.8827 6H5.11726L6.7962 21.1104C6.85247 21.6169 7.28054 22 7.79009 22H16.2099C16.7195 22 17.1475 21.6169 17.2038 21.1104L18.8827 6ZM10 9C10.5523 9 11 9.44771 11 10V18C11 18.5523 10.5523 19 10 19C9.44772 19 9 18.5523 9 18V10C9 9.44771 9.44772 9 10 9ZM14 9C14.5523 9 15 9.44771 15 10V18C15 18.5523 14.5523 19 14 19C13.4477 19 13 18.5523 13 18V10C13 9.44771 13.4477 9 14 9Z" fill="#293644"/> </svg>
                                        </a></td>
                                        </tr>
                                        <?PHP
                                        }

                                    }
                                }
                            } else {
                                ?>
                                <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                </tr>
                                <?PHP
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </main>
        </div>
        <!-- END ROW -->
    </div>
    <!-- END CONTAINER FLUID -->

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
    <script src="../js/sha256.js"></script>
    <script src="../js/functions.js"></script>
</body>
</html>